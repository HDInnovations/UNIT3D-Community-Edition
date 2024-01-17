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
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * @see \Tests\Unit\Console\Commands\SyncPeersTest
 */
class SyncPeers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:sync_peers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Torrent Seeders/Leechers (Peers) Count.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        DB::statement(
            'UPDATE torrents
        LEFT JOIN (
            SELECT torrent_id,
            SUM(IF(left = 0 AND active = 1, 1, 0)) AS updated_seeders,
            SUM(IF(left != 0 AND active = 1, 1, 0)) AS updated_leechers
            FROM peers
            GROUP BY torrent_id
        ) AS seeders_leechers ON torrents.id = seeders_leechers.torrent_id
        SET seeders = COALESCE(seeders_leechers.updated_seeders, 0),
        leechers = COALESCE(seeders_leechers.updated_leechers, 0)'
        );

        $this->comment('Torrent Peer Syncing Command Complete');
    }
}
