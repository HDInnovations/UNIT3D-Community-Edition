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

        $uploaded = max($realUploaded - ($peer?->uploaded ?? 0), 0);
        $downloaded = max($realDownloaded - ($peer?->downloaded ?? 0), 0);

        // If no Peer record found then create one
        if ($isNewPeer) {
            if ($this->queries['uploaded'] > 0 || $this->queries['downloaded'] > 0) {
                $event = 'started';
                $uploaded = 0;
                $downloaded = 0;
            }
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

        if ($personalFreeleech ||
            $this->group->is_freeleech == 1 ||
            $freeleechToken ||
            config('other.freeleech') == 1) {
            $modDownloaded = 0;
        } elseif ($this->torrent->free >= 1) {
            // FL value in DB are from 0% to 100%.
            // Divide it by 100 and multiply it with "downloaded" to get discount download.
            $fl_discount = $downloaded * $this->torrent->free / 100;
            $modDownloaded = $downloaded - $fl_discount;
        } else {
            $modDownloaded = $downloaded;
        }

        if ($this->torrent->doubleup == 1 ||
            $this->group->is_double_upload == 1 ||
            config('other.doubleup') == 1) {
            $modUploaded = $uploaded * 2;
        } else {
            $modUploaded = $uploaded;
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
                $connection = @fsockopen($tmp_ip, $this->queries['port'], $_, $_, 1);

                if ($connectable = \is_resource($connection)) {
                    fclose($connection);
                }

                // 5400 is the maximum announce interval. 60 is some leeway.
                cache()->put(':peers:connectable:'.$key, $connectable, 5400 + 60 + config('announce.connectable_check_interval'));
                cache()->remember(':peers:connectable-timer:'.$key, config('announce.connectable_check_interval'), fn () => true);
            }
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
                'user_id'     => $this->user->id,
                'connectable' => $connectable,
                'active'      => $event !== 'stopped',
            ])
        ]);

        Redis::connection('announce')->command('RPUSH', [
            config('cache.prefix').':histories:batch',
            serialize([
                'user_id'           => $this->user->id,
                'torrent_id'        => $this->torrent->id,
                'agent'             => $this->queries['user-agent'],
                'uploaded'          => $event === 'started' ? 0 : $modUploaded,
                'actual_uploaded'   => $event === 'started' ? 0 : $uploaded,
                'client_uploaded'   => $realUploaded,
                'downloaded'        => $event === 'started' ? 0 : $modDownloaded,
                'actual_downloaded' => $event === 'started' ? 0 : $downloaded,
                'client_downloaded' => $realDownloaded,
                'seeder'            => $this->queries['left'] == 0,
                'active'            => $event !== 'stopped',
                'seedtime'          => 0,
                'immune'            => $this->group->is_immune,
                'completed_at'      => $event === 'completed' ? now() : null,
            ])
        ]);

        // User updates

        if (($modUploaded > 0 || $modDownloaded > 0) && $event !== 'started') {
            DB::table('users')
                ->where('id', '=', $this->user->id)
                ->update([
                    'uploaded'   => DB::raw('uploaded + '.(int) $modUploaded),
                    'downloaded' => DB::raw('downloaded + '.(int) $modDownloaded),
                ]);
        }

        // Torrent updates

        $torrentUpdates = [];

        if ($event === 'completed') {
            $torrentUpdates['times_completed'] = DB::raw('times_completed + 1');
        }

        $isDeadPeer = $this->queries['event'] === 'stopped';
        $isSeeder = $this->queries['left'] == 0;

        $newSeed = $isNewPeer && ! $isDeadPeer && $isSeeder;
        $newLeech = $isNewPeer && ! $isDeadPeer && $isSeeder;
        $stoppedSeed = ! $isNewPeer && $isDeadPeer && $isSeeder;
        $stoppedLeech = ! $isNewPeer && $isDeadPeer && ! $isSeeder;
        $leechBecomesSeed = ! $isNewPeer && ! $isDeadPeer && $isSeeder && $peer->left > 0;
        $seedBecomesLeech = ! $isNewPeer && ! $isDeadPeer && ! $isSeeder && $peer->left === 0;

        if ($newLeech || $seedBecomesLeech) {
            $torrentUpdates['leechers'] = DB::raw('leechers + 1');
        } elseif ($stoppedLeech || $leechBecomesSeed) {
            $torrentUpdates['leechers'] = DB::raw('leechers - 1');
        }

        if ($newSeed || $leechBecomesSeed) {
            $torrentUpdates['seeders'] = DB::raw('seeders + 1');
        } elseif ($stoppedSeed || $seedBecomesLeech) {
            $torrentUpdates['seeders'] = DB::raw('seeders - 1');
        }

        if ($torrentUpdates !== []) {
            DB::table('torrents')
                ->where('id', '=', $this->torrent->id)
                ->update($torrentUpdates);
        }
    }
}
