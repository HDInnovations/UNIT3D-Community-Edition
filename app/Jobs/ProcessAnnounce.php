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
use App\Models\PersonalFreeleech;
use App\Models\Torrent;
use App\Models\User;
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
     *
     * @param array{
     *     is_freeleech: boolean,
     *     is_double_upload: boolean,
     *     is_immune: boolean
     * } $group
     * @param array{
     *     id: int,
     *     free: int,
     *     doubleup: bool,
     * } $torrent
     * @param ?array{
     *     uploaded: int,
     *     downloaded: int,
     *     left: int,
     * } $peer
     */
    public function __construct(
        protected $queries,
        protected $userId,
        protected array $group,
        protected array $torrent,
        protected ?array $peer,
    ) {
    }

    /**
     * Get the middleware the job should pass through.
     */
    public function middleware(): array
    {
        return [(new WithoutOverlapping($this->userId.':'.$this->torrent['id']))->releaseAfter(30)];
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

        $isNewPeer = $this->peer === null;

        // Calculate the change in upload/download compared to the last announce
        $uploadedDelta = max($realUploaded - ($this->peer['uploaded'] ?? 0), 0);
        $downloadedDelta = max($realDownloaded - ($this->peer['downloaded'] ?? 0), 0);

        // If no peer record found then set deltas to 0 and change to `started` event
        if ($isNewPeer) {
            if ($this->queries['uploaded'] > 0 || $this->queries['downloaded'] > 0) {
                $event = 'started';
                $uploadedDelta = 0;
                $downloadedDelta = 0;
            }
        }

        // Check if user currently has a personal freeleech
        $personalFreeleech = cache()->rememberForever(
            'personal_freeleech:'.$this->userId,
            fn () => PersonalFreeleech::query()
                ->where('user_id', '=', $this->userId)
                ->exists()
        );

        // Check if user has a freeleech token on this torrent
        $freeleechToken = cache()->rememberForever(
            'freeleech_token:'.$this->userId.':'.$this->torrent['id'],
            fn () => FreeleechToken::query()
                ->where('user_id', '=', $this->userId)
                ->where('torrent_id', '=', $this->torrent['id'])
                ->exists(),
        );

        // Calculate credited Download
        if (
            $personalFreeleech
            || $this->group['is_freeleech']
            || $freeleechToken
            || config('other.freeleech')
        ) {
            $creditedDownloadedDelta = 0;
        } elseif ($this->torrent['free'] >= 1) {
            // FL value in DB are from 0% to 100%.
            // Divide it by 100 and multiply it with "downloaded" to get discount download.
            $fl_discount = $downloadedDelta * $this->torrent['free'] / 100;
            $creditedDownloadedDelta = $downloadedDelta - $fl_discount;
        } else {
            $creditedDownloadedDelta = $downloadedDelta;
        }

        // Calculate credited upload
        if (
            $this->torrent['doubleup']
            || $this->group['is_double_upload']
            || config('other.doubleup')
        ) {
            $creditedUploadedDelta = $uploadedDelta * 2;
        } else {
            $creditedUploadedDelta = $uploadedDelta;
        }

        // User Updates

        if (($creditedUploadedDelta > 0 || $creditedDownloadedDelta > 0) && $event !== 'stopped') {
            User::whereKey($this->userId)->update([
                'uploaded'   => DB::raw('uploaded + '.(int) $creditedUploadedDelta),
                'downloaded' => DB::raw('downloaded + '.(int) $creditedDownloadedDelta),
            ]);
        }

        // Check if peer is connectable

        $connectable = false;

        if (config('announce.connectable_check')) {
            $tmp_ip = inet_ntop(pack('A'.\strlen($ipAddress), $ipAddress));

            // IPv6 Check
            if (filter_var($tmp_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $tmp_ip = '['.$tmp_ip.']';
            }

            $key = $tmp_ip.'-'.$this->queries['port'].'-'.$this->queries['agent'];

            if (cache()->has(':peers:connectable-timer:'.$key)) {
                $connectable = cache()->get(':peers:connectable:'.$key) === true;
            } else {
                // This connection has to be made asynchronously from the
                // announce route due to the timeout, as it extends the
                // request's processing time significantly, and we don't want
                // to block other announces from being processed. Since this
                // job is processed via the queue, the queue may get backed up,
                // but that won't cause any issues besides maybe needing to
                // increase the worker count.
                $connection = @fsockopen($tmp_ip, $this->queries['port'], $_, $_, 1);

                if ($connectable = \is_resource($connection)) {
                    fclose($connection);
                }

                // 5400 is the maximum announce interval. 60 is some leeway.
                cache()->put(':peers:connectable:'.$key, $connectable, 5400 + 60 + config('announce.connectable_check_interval'));
                cache()->remember(':peers:connectable-timer:'.$key, config('announce.connectable_check_interval'), fn () => true);
            }
        }

        /**
         * Peer batch upsert.
         *
         * @see \App\Console\Commands\AutoUpsertPeers
         */
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
                'torrent_id'  => $this->torrent['id'],
                'user_id'     => $this->userId,
                'connectable' => $connectable,
                'active'      => $event !== 'stopped',
            ])
        ]);

        /**
         * History batch upsert.
         *
         * @see \App\Console\Commands\AutoUpsertHistories
         */
        Redis::connection('announce')->command('RPUSH', [
            config('cache.prefix').':histories:batch',
            serialize([
                'user_id'           => $this->userId,
                'torrent_id'        => $this->torrent['id'],
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
                'immune'            => $this->group['is_immune'],
                'completed_at'      => $event === 'completed' ? now() : null,
            ])
        ]);

        // Torrent updates

        $isDeadPeer = $event === 'stopped';
        $isSeeder = $this->queries['left'] == 0;

        $newSeed = $isNewPeer && ! $isDeadPeer && $isSeeder;
        $newLeech = $isNewPeer && ! $isDeadPeer && ! $isSeeder;
        $stoppedSeed = ! $isNewPeer && $isDeadPeer && $isSeeder;
        $stoppedLeech = ! $isNewPeer && $isDeadPeer && ! $isSeeder;
        $leechBecomesSeed = ! $isNewPeer && ! $isDeadPeer && $isSeeder && $this->peer['left'] > 0;
        $seedBecomesLeech = ! $isNewPeer && ! $isDeadPeer && ! $isSeeder && $this->peer['left'] === 0;

        $seederCountDelta = ($newSeed || $leechBecomesSeed) <=> ($stoppedSeed || $seedBecomesLeech);
        $leecherCountDelta = ($newLeech || $seedBecomesLeech) <=> ($stoppedLeech || $leechBecomesSeed);
        $completedCountDelta = (int) ($event === 'completed');

        if ($seederCountDelta !== 0 || $leecherCountDelta !== 0 || $completedCountDelta !== 0) {
            Torrent::whereKey($this->torrent['id'])->update([
                'seeders'         => DB::raw('seeders + '.$seederCountDelta),
                'leechers'        => DB::raw('leechers + '.$leecherCountDelta),
                'times_completed' => DB::raw('times_completed + '.$completedCountDelta),
            ]);
        }
    }
}
