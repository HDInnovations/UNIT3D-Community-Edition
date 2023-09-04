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
use App\Models\Peer;
use App\Models\PersonalFreeleech;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class ProcessAnnounce implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $queries, protected $user, protected $group, protected $torrent)
    {
    }

    /**
     * Get the middleware the job should pass through.
     */
    public function middleware(): array
    {
        return [(new WithoutOverlapping($this->user->id.':'.$this->torrent->id))->releaseAfter(30)];
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
        // Set Variables
        $realUploaded = $this->queries['uploaded'];
        $realDownloaded = $this->queries['downloaded'];
        $event = $this->queries['event'];
        $peerId = base64_decode($this->queries['peer_id']);
        $ipAddress = base64_decode($this->queries['ip-address']);

        // Get The Current Peer
        $peer = $this->torrent->peers
            ->where('peer_id', '=', $peerId)
            ->where('user_id', '=', $this->user->id)
            ->first();

        $isNewPeer = $peer === null;

        $uploadedDelta = max($realUploaded - ($peer?->uploaded ?? 0), 0);
        $downloadedDelta = max($realDownloaded - ($peer?->downloaded ?? 0), 0);

        // If no Peer record found then create one
        if ($isNewPeer) {
            if ($this->queries['uploaded'] > 0 || $this->queries['downloaded'] > 0) {
                $event = 'started';
                $uploadedDelta = 0;
                $downloadedDelta = 0;
            }

            $peer = new Peer();
        }

        // Modification of Upload and Download (Check cache but in case redis data was lost hit DB)
        $personalFreeleech = cache()->rememberForever(
            'personal_freeleech:'.$this->user->id,
            fn () => PersonalFreeleech::query()
                ->where('user_id', '=', $this->user->id)
                ->exists()
        );

        $freeleechToken = cache()->rememberForever(
            'freeleech_token:'.$this->user->id.':'.$this->torrent->id,
            fn () => FreeleechToken::query()
                ->where('user_id', '=', $this->user->id)
                ->where('torrent_id', '=', $this->torrent->id)
                ->exists(),
        );

        if (
            $personalFreeleech
            || $this->group->is_freeleech
            || $freeleechToken
            || config('other.freeleech')
        ) {
            $creditedDownloadedDelta = 0;
        } elseif ($this->torrent->free >= 1) {
            // FL value in DB are from 0% to 100%.
            // Divide it by 100 and multiply it with "downloaded" to get discount download.
            $fl_discount = $downloadedDelta * $this->torrent->free / 100;
            $creditedDownloadedDelta = $downloadedDelta - $fl_discount;
        } else {
            $creditedDownloadedDelta = $downloadedDelta;
        }

        if (
            $this->torrent->doubleup
            || $this->group->is_double_upload
            || config('other.doubleup')
        ) {
            $creditedUploadedDelta = $uploadedDelta * 2;
        } else {
            $creditedUploadedDelta = $uploadedDelta;
        }

        $peer->updateConnectableStateIfNeeded();

        if (($creditedUploadedDelta > 0 || $creditedDownloadedDelta > 0) && $event !== 'stopped') {
            $this->user->update([
                'uploaded'   => DB::raw('uploaded + '.(int) $creditedUploadedDelta),
                'downloaded' => DB::raw('downloaded + '.(int) $creditedDownloadedDelta),
            ]);
        }

        Redis::connection('announce')->command('RPUSH', [
            config('cache.prefix').':peers:batch',
            serialize([
                'peer_id'     => $peerId,
                'ip'          => $ipAddress,
                'port'        => $this->queries['port'],
                'agent'       => $this->queries['user-agent'],
                'uploaded'    => $realUploaded,
                'downloaded'  => $realDownloaded,
                'left'        => $this->queries['left'],
                'seeder'      => $this->queries['left'] == 0,
                'torrent_id'  => $this->torrent->id,
                'user_id'     => $this->userId,
                'connectable' => $peer->connectable,
                'active'      => $event !== 'stopped',
            ])
        ]);

        Redis::connection('announce')->command('RPUSH', [
            config('cache.prefix').':histories:batch',
            serialize([
                'user_id'           => $this->user->id,
                'torrent_id'        => $this->torrent->id,
                'agent'             => $this->queries['user-agent'],
                'uploaded'          => $event === 'started' ? 0 : $creditedUploadedDelta,
                'actual_uploaded'   => $event === 'started' ? 0 : $uploadedDelta,
                'client_uploaded'   => $realUploaded,
                'downloaded'        => $event === 'started' ? 0 : $creditedDownloadedDelta,
                'actual_downloaded' => $event === 'started' ? 0 : $downloadedDelta,
                'client_downloaded' => $realDownloaded,
                'seeder'            => $this->queries['left'] == 0,
                'active'            => $event !== 'stopped',
                'seedtime'          => 0,
                'immune'            => $this->group->is_immune,
                'completed_at'      => $event === 'completed' ? now() : null,
            ])
        ]);

        $isDeadPeer = $event === 'stopped';
        $isSeeder = $this->queries['left'] == 0;

        $newSeed = $isNewPeer && ! $isDeadPeer && $isSeeder;
        $newLeech = $isNewPeer && ! $isDeadPeer && ! $isSeeder;
        $stoppedSeed = ! $isNewPeer && $isDeadPeer && $isSeeder;
        $stoppedLeech = ! $isNewPeer && $isDeadPeer && ! $isSeeder;
        $leechBecomesSeed = ! $isNewPeer && ! $isDeadPeer && $isSeeder && $peer->left > 0;
        $seedBecomesLeech = ! $isNewPeer && ! $isDeadPeer && ! $isSeeder && $peer->left === 0;

        $seederCountDelta = ($newSeed || $leechBecomesSeed) <=> ($stoppedSeed || $seedBecomesLeech);
        $leecherCountDelta = ($newLeech || $seedBecomesLeech) <=> ($stoppedLeech || $leechBecomesSeed);
        $completedCountDelta = (int) ($event === 'completed');

        if ($seederCountDelta !== 0 || $leecherCountDelta !== 0 || $completedCountDelta !== 0) {
            $this->torrent->update([
                'seeders'         => DB::raw('seeders + '.$seederCountDelta),
                'leechers'        => DB::raw('leechers + '.$leecherCountDelta),
                'times_completed' => DB::raw('times_completed + '.$completedCountDelta),
            ]);
        }
    }
}
