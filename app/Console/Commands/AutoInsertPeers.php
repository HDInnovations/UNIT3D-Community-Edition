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
 */

namespace App\Console\Commands;

use App\Models\Peer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

/**
 * @see \Tests\Unit\Console\Commands\AutoFlushPeersTest
 */
class AutoInsertPeers extends Command
{
    /**
     * MySql can handle a max of 65k placeholders per query,
     * and there are 13 fields on each peer that are updated
     */
    public const PEERS_PER_CYCLE = 65_000 / 13;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:insert_peers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserts peers in batches';

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle(): void
    {
        $key = \config('cache.prefix').':peers:batch';
        $peerCount = Redis::connection('peer')->command('LRANGE', [$key, '0', '-1']);
        $cycles = \ceil($peerCount / self::PEERS_PER_CYCLE);

        for ($i = 0; $i < $cycles; $i++) {
            $peers = Redis::connection('peer')->command('RPOP', [$key, self::PEERS_PER_CYCLE]);
            $peers = \array_map('unserialize', [$peers]);

            Peer::upsert(
                $peers,
                ['user_id', 'torrent_id', 'peer_id'],
                [
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
                    'connectable'
                ],
            );
        }

        $this->comment('Automated insert peers command complete');
    }
}