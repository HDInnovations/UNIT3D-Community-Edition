<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @credits    Rhilip <https://github.com/Rhilip> Roardom <roardom@protonmail.com>
 */

namespace App\Jobs;

use App\Models\FreeleechToken;
use App\Models\Group;
use App\Models\History;
use App\Models\Peer;
use App\Models\Torrent;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class ProcessAnnounce implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly array $queries,
        private readonly array $userArray,
        private readonly array $groupArray,
        private readonly array $torrentArray,
        private readonly array $peersArray
    ) {
    }

    /**
     * Get the middleware the job should pass through.
     */
    public function middleware(): array
    {
        return [(new WithoutOverlapping($this->userArray['id'].':'.$this->torrentArray['id']))->releaseAfter(30)];
    }

    /**
     * Execute the job.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function handle(): void
    {
        // We can't pass the models directly into the constructor, otherwise laravel will serialize them
        // and upon deserialization will fetch the models from the database again, causing extra unneeded queries.
        $torrent = new Torrent($this->torrentArray);
        $user = new User($this->userArray);
        $group = new Group($this->groupArray);

        $peers = collect(array_map(fn ($peer) => new Peer($peer), $this->peersArray));

        // Flag is tripped if new session is created but client reports up/down > 0
        $ghost = false;

        // Set Variables
        $realUploaded = $this->queries['uploaded'];
        $realDownloaded = $this->queries['downloaded'];
        $event = strtolower($this->queries['event']);
        $peerId = base64_decode($this->queries['peer_id']);
        $ipAddress = base64_decode($this->queries['ip-address']);

        // Get The Current Peer
        $peer = $peers
            ->where('peer_id', '=', $peerId)
            ->firstWhere('user_id', '=', $user->id);

        // If no Peer record found then create one
        if ($peer === null) {
            if ($this->queries['uploaded'] > 0 || $this->queries['downloaded'] > 0) {
                $ghost = true;
                $event = 'started';
            }

            $peer = new Peer();
        }

        // Get history information
        $history = History::firstOrNew(
            [
                'torrent_id' => $torrent->id,
                'user_id'    => $user->id,
            ],
            [
                'uploaded'          => 0,
                'actual_uploaded'   => 0,
                'downloaded'        => 0,
                'actual_downloaded' => 0,
                'seedtime'          => 0,
                'immune'            => 0,
                'completed_at'      => null,
            ]
        );

        // Check Ghost Flag
        if ($ghost) {
            $uploaded = ($realUploaded >= $history->client_uploaded) ? ($realUploaded - $history->client_uploaded) : 0;
            $downloaded = ($realDownloaded >= $history->client_downloaded) ? ($realDownloaded - $history->client_downloaded) : 0;
        } else {
            $uploaded = ($realUploaded >= $peer->uploaded) ? ($realUploaded - $peer->uploaded) : 0;
            $downloaded = ($realDownloaded >= $peer->downloaded) ? ($realDownloaded - $peer->downloaded) : 0;
        }

        if ($history->updated_at !== null && $history->updated_at->timestamp > now()->subHours(2)->timestamp && $history->seeder && $this->queries['left'] == 0) {
            $oldUpdate = $history->updated_at->timestamp;
        } else {
            $oldUpdate = now()->timestamp;
        }

        // Modification of Upload and Download (Check cache but in case redis data was lost hit DB)
        $personalFreeleech = cache()->has('personal_freeleech:'.$user->id);
        $freeleechToken = cache()->get('freeleech_token:'.$user->id.':'.$torrent->id) ??
            FreeleechToken::query()
                ->where('user_id', '=', $user->id)
                ->where('torrent_id', '=', $torrent->id)
                ->exists();

        if ($personalFreeleech ||
            $group->is_freeleech == 1 ||
            $freeleechToken ||
            config('other.freeleech') == 1) {
            $modDownloaded = 0;
        } elseif ($torrent->free >= 1) {
            // FL value in DB are from 0% to 100%.
            // Divide it by 100 and multiply it with "downloaded" to get discount download.
            $fl_discount = $downloaded * $torrent->free / 100;
            $modDownloaded = $downloaded - $fl_discount;
        } else {
            $modDownloaded = $downloaded;
        }

        if ($torrent->doubleup == 1 ||
            $group->is_double_upload == 1 ||
            config('other.doubleup') == 1) {
            $modUploaded = $uploaded * 2;
        } else {
            $modUploaded = $uploaded;
        }

        // Common Parts Extracted From Switch
        $peer->peer_id = $peerId;
        $peer->ip = $ipAddress;
        $peer->port = $this->queries['port'];
        $peer->agent = $this->queries['user-agent'];
        $peer->uploaded = $realUploaded;
        $peer->downloaded = $realDownloaded;
        $peer->seeder = $this->queries['left'] == 0;
        $peer->left = $this->queries['left'];
        $peer->torrent_id = $torrent->id;
        $peer->user_id = $user->id;
        $peer->updateConnectableStateIfNeeded();
        $peer->updated_at = now();

        $history->agent = $this->queries['user-agent'];
        $history->seeder = (int) ($this->queries['left'] == 0);
        $history->client_uploaded = $realUploaded;
        $history->client_downloaded = $realDownloaded;

        switch ($event) {
            case 'started':
                $peer->active = true;

                $history->active = 1;
                $history->immune = (int) ($history->exists ? $history->immune && $group->is_immune : $group->is_immune);

                break;
            case 'completed':
                $peer->active = true;

                $history->active = 1;
                $history->uploaded += $modUploaded;
                $history->actual_uploaded += $uploaded;
                $history->downloaded += $modDownloaded;
                $history->actual_downloaded += $downloaded;
                $history->completed_at = now();

                // Seedtime allocation
                if ($this->queries['left'] == 0) {
                    $newUpdate = $peer->updated_at->timestamp;
                    $diff = $newUpdate - $oldUpdate;
                    $history->seedtime += $diff;
                }

                // User Update
                if ($modUploaded > 0 || $modDownloaded > 0) {
                    $user->update([
                        'uploaded'   => DB::raw('uploaded + '.(int) $modUploaded),
                        'downloaded' => DB::raw('downloaded + '.(int) $modDownloaded),
                    ]);
                }
                // End User Update

                // Torrent Completed Update
                $torrent->times_completed += 1;

                break;
            case 'stopped':
                $peer->active = false;

                $history->active = 0;
                $history->uploaded += $modUploaded;
                $history->actual_uploaded += $uploaded;
                $history->downloaded += $modDownloaded;
                $history->actual_downloaded += $downloaded;

                // Seedtime allocation
                if ($this->queries['left'] == 0) {
                    $newUpdate = $peer->updated_at->timestamp;
                    $diff = $newUpdate - $oldUpdate;
                    $history->seedtime += $diff;
                }

                // User Update
                if ($modUploaded > 0 || $modDownloaded > 0) {
                    $user->update([
                        'uploaded'   => DB::raw('uploaded + '.(int) $modUploaded),
                        'downloaded' => DB::raw('downloaded + '.(int) $modDownloaded),
                    ]);
                }
                // End User Update
                break;
            default:
                $peer->active = true;

                $history->active = 1;
                $history->uploaded += $modUploaded;
                $history->actual_uploaded += $uploaded;
                $history->downloaded += $modDownloaded;
                $history->actual_downloaded += $downloaded;

                // Seedtime allocation
                if ($this->queries['left'] == 0) {
                    $newUpdate = $peer->updated_at->timestamp;
                    $diff = $newUpdate - $oldUpdate;
                    $history->seedtime += $diff;
                }

                // User Update
                if ($modUploaded > 0 || $modDownloaded > 0) {
                    $user->update([
                        'uploaded'   => DB::raw('uploaded + '.(int) $modUploaded),
                        'downloaded' => DB::raw('downloaded + '.(int) $modDownloaded),
                    ]);
                }
                // End User Update
        }

        Redis::connection('announce')->command('LPUSH', [
            config('cache.prefix').':peers:batch',
            serialize($peer->only([
                'peer_id',
                'ip',
                'port',
                'agent',
                'uploaded',
                'downloaded',
                'left',
                'seeder',
                'torrent_id',
                'user_id',
                'connectable',
                'active'
            ]))
        ]);

        Redis::connection('announce')->command('LPUSH', [
            config('cache.prefix').':histories:batch',
            serialize($history->only([
                'user_id',
                'torrent_id',
                'agent',
                'uploaded',
                'actual_uploaded',
                'client_uploaded',
                'downloaded',
                'actual_downloaded',
                'client_downloaded',
                'seeder',
                'active',
                'seedtime',
                'immune',
                'completed_at',
            ]))
        ]);

        $otherSeeders = $peers
            ->where('left', '=', 0)
            ->where('peer_id', '!=', $peerId)
            ->count();
        $otherLeechers = $peers
            ->where('left', '>', 0)
            ->where('peer_id', '!=', $peerId)
            ->count();

        $newSeeders = $otherSeeders + (int) ($this->queries['left'] == 0 && strtolower($this->queries['event']) !== 'stopped');
        $newLeechers = $otherLeechers + (int) ($this->queries['left'] > 0 && strtolower($this->queries['event']) !== 'stopped');

        if ($torrent->seeders !== $newSeeders || $torrent->leechers !== $newLeechers || $event === 'completed') {
            Torrent::whereKey($torrent->id)->update([
                'seeders'         => $newSeeders,
                'leechers'        => $newLeechers,
                'times_completed' => DB::raw('times_completed + '.(int) ($event === 'completed')),
            ]);
        }
    }
}
