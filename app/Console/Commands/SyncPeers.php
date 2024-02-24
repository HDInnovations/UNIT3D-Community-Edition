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

use App\Models\Scopes\ApprovedScope;
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
        // Split up the update into 25 separate updates to avoid deadlocks
        $maxId = Torrent::withoutGlobalScope(ApprovedScope::class)->max('id');
        $interval = (int) ceil(Torrent::count() / 25);
        $lowerId = 0;

        while ($lowerId < $maxId) {
            $upperId = Torrent::withoutGlobalScope(ApprovedScope::class)
                ->where('id', '>', $lowerId)
                ->offset($interval)
                ->value('id') ?? $maxId;

            Torrent::withoutTimestamps(
                static fn () => Torrent::withoutGlobalScope(ApprovedScope::class)
                    ->where('id', '>', $lowerId)
                    ->where('id', '<=', $upperId)
                    ->update([
                        'seeders'  => DB::raw('(SELECT COUNT(*) FROM peers WHERE `left` = 0 AND active AND visible and torrent_id = torrents.id)'),
                        'leechers' => DB::raw('(SELECT COUNT(*) FROM peers WHERE `left` > 0 AND active AND visible and torrent_id = torrents.id)'),
                    ])
            );

            $lowerId = $upperId;
        }

        $this->comment('Torrent Peer Syncing Command Complete');
    }
}
