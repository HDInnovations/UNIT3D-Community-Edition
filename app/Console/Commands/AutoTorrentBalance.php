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

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Class AutoTorrentBalance.
 *
 * This class is a Laravel command that is responsible for calculating the balance for all torrents.
 * It is a console command that can be executed manually or scheduled.
 */
class AutoTorrentBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:torrent_balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate balance for all torrents.';

    /**
     * Execute the console command.
     *
     * This method is the entry point of the command. It wraps the balance calculation
     * in a database transaction and retries up to 5 times in case of a deadlock.
     *
     * @throws Exception|Throwable If there is an error during the execution of the command.
     */
    public function handle(): void
    {
        // Join the 'torrents' table with a subquery on the 'history' table.
        // The subquery calculates the balance for each torrent.
        DB::transaction(static function (): void {
            DB::table('torrents')->joinSub(
                DB::table('history')
                    ->select('torrent_id')
                    ->selectRaw('SUM(actual_uploaded) - SUM(actual_downloaded) AS balance')
                    ->groupBy('torrent_id'),
                'balances',
                static fn ($join) => $join->on('balances.torrent_id', '=', 'torrents.id')
            )
                // Update the balance and updated_at fields in the 'torrents' table.
                ->update([
                    'torrents.balance' => DB::raw('balances.balance'),
                    'updated_at'       => DB::raw('updated_at'),
                ]);
        }, 5); // 5 is the number of attempts if deadlock occurs.

        // Output a success message to the console.
        $this->comment('Torrent balance calculations completed.');
    }
}
