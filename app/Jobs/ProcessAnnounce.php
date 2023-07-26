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
use App\Models\History;
use App\Models\Peer;
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
        protected array $queries,
        protected array $user,
        protected array $group,
        protected array $torrent,
        protected array $peers,
    ) {
    }

    /**
     * Get the middleware the job should pass through.
     */
    public function middleware(): array
    {
        return [(new WithoutOverlapping($this->user['id'].':'.$this->torrent['id']))->releaseAfter(30)];
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
        // Flag is tripped if new session is created but client reports up/down > 0
        $ghost = false;

        // Set Variables
        $realUploaded = $this->queries['uploaded'];
        $realDownloaded = $this->queries['downloaded'];
        $event = strtolower($this->queries['event']);
        $peerId = base64_decode($this->queries['peer_id']);
        $ipAddress = base64_decode($this->queries['ip-address']);

        // Get The Current Peer
        $peer = null;

        foreach ($this->peers as $p) {
            if ($p['peer_id'] === $peerId && $p['user_id'] === $this->user['id']) {
                $peer = new Peer($p);

                break;
            }
        }

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
                'torrent_id' => $this->torrent['id'],
                'user_id'    => $this->user['id'],
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

        // Find the last time this torrent was announced by this user (used to calculate seedtime)
        if ($history->updated_at !== null && $history->updated_at->timestamp > now()->subHours(2)->timestamp && $history->seeder && $this->queries['left'] == 0) {
            $oldUpdate = $history->updated_at->timestamp;
        } else {
            $oldUpdate = now()->timestamp;
        }

        // Modification of Upload and Download (Check cache but in case redis data was lost hit DB)
        $personalFreeleech = cache()->has('personal_freeleech:'.$this->user['id']);
        $freeleechToken = cache()->get('freeleech_token:'.$this->user['id'].':'.$this->torrent['id']) ??
            FreeleechToken::query()
                ->where('user_id', '=', $this->user['id'])
                ->where('torrent_id', '=', $this->torrent['id'])
                ->exists();

        if (
            $personalFreeleech
            || $this->group['is_freeleech']
            || $freeleechToken
            || config('other.freeleech')
        ) {
            $modDownloaded = 0;
        } elseif ($this->torrent['free'] >= 1) {
            // FL value in DB are from 0% to 100%.
            // Divide it by 100 and multiply it with "downloaded" to get discount download.
            $fl_discount = $downloaded * $this->torrent['free'] / 100;
            $modDownloaded = $downloaded - $fl_discount;
        } else {
            $modDownloaded = $downloaded;
        }

        if (
            $this->torrent['doubleup']
            || $this->group['is_double_upload']
            || config('other.doubleup')
        ) {
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
        $peer->torrent_id = $this->torrent['id'];
        $peer->user_id = $this->user['id'];
        $peer->updateConnectableStateIfNeeded();
        $peer->updated_at = now();
        $peer->active = $event !== 'stopped';

        $history->agent = $this->queries['user-agent'];
        $history->seeder = $this->queries['left'] == 0;
        $history->client_uploaded = $realUploaded;
        $history->client_downloaded = $realDownloaded;
        $history->active = $event !== 'stopped';

        switch ($event) {
            case 'started':
                $history->immune = ($history->exists ? $history->immune && $this->group['is_immune'] : $this->group['is_immune']);

                break;
            case 'completed':
                $history->completed_at = now();

                // no break
            case 'stopped':
            default:
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
                $userUpdates = [];

                if ($modUploaded > 0) {
                    $userUpdates['uploaded'] = DB::raw('uploaded + '.(int) $modUploaded);
                }

                if ($modDownloaded > 0) {
                    $userUpdates['downloaded'] = DB::raw('downloaded + '.(int) $modDownloaded);
                }

                if ($modDownloaded !== []) {
                    DB::table('users')->where('id', '=', $this->user['id'])->update($userUpdates);
                }
        }

        // Add to write-back caches

        if (config('announce.connectable_check')) {
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
        } else {
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
                    'active'
                ]))
            ]);
        }

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

        // Update torrent seeders, leechers, and/or times_completed

        $seeders = (int) ($this->queries['left'] == 0 && $event !== 'stopped');
        $leechers = (int) ($this->queries['left'] > 0 && $event !== 'stopped');

        foreach ($this->peers as $peer) {
            $seeders += (int) ($peer['left'] == 0 && $peer['peer_id'] !== $peerId);
            $leechers += (int) ($peer['left'] > 0 && $peer['peer_id'] !== $peerId);
        }

        $torrentUpdates = [];

        if ($this->torrent['seeders'] !== $seeders) {
            $torrentUpdates['seeders'] = $seeders;
        }

        if ($this->torrent['leechers'] !== $leechers) {
            $torrentUpdates['leechers'] = $leechers;
        }

        if ($event === 'completed') {
            $torrentUpdates['times_completed'] = DB::raw('times_completed + 1');
        }

        if ($torrentUpdates !== []) {
            DB::table('torrents')->where('id', '=', $this->torrent['id'])->update($torrentUpdates);
        }
    }
}
