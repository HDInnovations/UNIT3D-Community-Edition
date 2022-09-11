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

use App\Models\Peer;
use App\Models\Torrent;
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
        Torrent::withAnyStatus()
            ->leftJoinSub(
                Peer::query()
                    ->select('torrent_id')
                    ->addSelect(DB::raw('sum(case when peers.left = 0 then 1 else 0 end) as updated_seeders'))
                    ->addSelect(DB::raw('sum(case when peers.left <> 0 then 1 else 0 end) as updated_leechers'))
                    ->groupBy('torrent_id'),
                'seeders_leechers',
                fn ($join) => $join->on('torrents.id', '=', 'seeders_leechers.torrent_id')
            )
            ->update([
                'seeders'  => DB::raw('COALESCE(seeders_leechers.updated_seeders, 0)'),
                'leechers' => DB::raw('COALESCE(seeders_leechers.updated_leechers, 0)'),
            ]);

        $this->comment('Torrent Peer Syncing Command Complete');
    }
}
