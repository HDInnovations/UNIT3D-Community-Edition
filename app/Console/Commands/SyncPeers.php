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
use App\Models\Scopes\ApprovedScope;
use App\Models\Torrent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

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
    protected $description = 'Sync Torrent Seeders/Leechers/Times Completed Count.';

    /**
     * Execute the console command.
     *
     * @throws Throwable
     */
    public function handle(): void
    {
        DB::transaction(function (): void {
            Torrent::withoutGlobalScope(ApprovedScope::class)
                ->leftJoinSub(
                    Peer::query()
                        ->select('torrent_id')
                        ->addSelect(DB::raw('SUM(peers.left = 0 AND peers.active = 1 AND peers.visible = 1) AS updated_seeders'))
                        ->addSelect(DB::raw('SUM(peers.left != 0 AND peers.active = 1 AND peers.visible = 1) AS updated_leechers'))
                        ->groupBy('torrent_id'),
                    'seeders_leechers',
                    fn ($join) => $join->on('torrents.id', '=', 'seeders_leechers.torrent_id')
                )
                ->update([
                    'seeders'    => DB::raw('COALESCE(seeders_leechers.updated_seeders, 0)'),
                    'leechers'   => DB::raw('COALESCE(seeders_leechers.updated_leechers, 0)'),
                    'updated_at' => DB::raw('updated_at')
                ]);
        }, 5);

        DB::transaction(function (): void {
            DB::statement("
                UPDATE torrents
                    SET times_completed = (
                        SELECT COUNT(*)
                        FROM history
                        WHERE `completed_at` IS NOT NULL AND torrent_id = torrents.id
                    )
            ");
        }, 5);

        $this->info('Torrent Seeders/Leechers/Times Completed Count Synced Successfully!');
    }
}
