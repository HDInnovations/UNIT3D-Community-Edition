<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @credits    Rhilip <https://github.com/Rhilip> Roardom <roardom@protonmail.com>
 */

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
    public function __construct(
        public string $peerId,
        public string $ip,
        public int $port,
        public string $agent,
        public int $uploaded,
        public int $downloaded,
        public int $left,
        public bool $seeder,
        public int $torrentId,
        public int $userId,
        public bool $active,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /**
         * Peer batch upsert.
         *
         * @see \App\Console\Commands\AutoUpsertPeers
         */
        Redis::connection('announce')->command('RPUSH', [
            config('cache.prefix').':peers:batch',
            serialize([
                'peer_id'     => hex2bin($this->peerId),
                'ip'          => hex2bin($this->ip),
                'port'        => $this->port,
                'agent'       => hex2bin($this->agent),
                'uploaded'    => $this->uploaded,
                'downloaded'  => $this->downloaded,
                'left'        => $this->left,
                'seeder'      => $this->seeder,
                'torrent_id'  => $this->torrentId,
                'user_id'     => $this->userId,
                'active'      => $this->active,
                'connectable' => $this->getConnectableStatus(),
            ]),
        ]);
    }

    /**
     * Check if peer is connectable.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function getConnectableStatus(): bool
    {
        if (!config('announce.connectable_check')) {
            return false;
        }

        // Unhex
        $ip = hex2bin($this->ip);

        if ($ip === false) {
            return false;
        }

        // Pack
        $ip = inet_ntop(pack('A'.\strlen($ip), $ip));

        if ($ip === false) {
            return false;
        }

        // IPv6 Check
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $ip = '['.$ip.']';
        }

        $key = $ip.'-'.$this->port.'-'.hex2bin($this->agent);

        // Check cache
        if (cache()->has('peers:connectable-timer:'.$key)) {
            return cache()->get('peers:connectable:'.$key) === true;
        }

        // Connect
        $connection = @fsockopen($ip, $this->port, $_, $_, 1);

        if ($connectable = \is_resource($connection)) {
            fclose($connection);
        }

        cache()->put('peers:connectable:'.$key, $connectable, config('announce.connectable_check_interval'));
        cache()->remember('peers:connectable-timer:'.$key, config('announce.connectable_check_interval'), fn () => true);

        return $connectable;
    }
}
