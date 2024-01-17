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

use App\Models\Torrent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
     */
    public function handle(): void
    {
        $histories = DB::table('history')
            ->select('torrent_id')
            ->selectRaw('SUM(actual_uploaded) - SUM(actual_downloaded) AS balance')
            ->groupBy('torrent_id')
            ->get();

        $torrents = Torrent::whereIn('id', $histories->pluck('torrent_id'))->get();

        foreach ($histories as $history) {
            $torrent = $torrents->firstWhere('id', $history->torrent_id);

            if ($torrent) {
                $torrent->balance = $history->balance;
                $torrent->save();
            }
        }

        $this->comment('Torrent balance calculations completed.');
    }
}
