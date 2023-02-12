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

use App\Models\History;
use App\Models\Torrent;
use Illuminate\Console\Command;

class AutoRefundDownload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:refund_download';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refunds Download To Users Based On Seed Time.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        History::query()
            ->with(['user', 'torrent'])
            ->where('active', '=', 1)
            ->where('seeder', '=', 1)
            ->where('seedtime', '>', config('hitrun.seedtime'))
            ->chunkById(100, function ($histories): void {
                foreach ($histories as $history) {
                    if ((config('other.refundable') || $history->torrent->refundable == 1 || $history->user->group->is_refundable == 1) && ($history->torrent->user_id != $history->user->id)) {
                        // TODO: Rework Logic

                        // One week seedtime equals quater refund based on torrent size


                        // Two weeks seedtime equals half refund based on torrent size


                        // Three weeks seedtime equals three quater refund based on torrent size


                        // One month seedtime equals full refund based on torrent size


                        if (isset($refund_amount, $mod_download) && $mod_download != 0 && $refund_amount != 0) {
                            $history->refunded_download += $refund_amount;
                            $history->save();

                            $history->user->downloaded -= $mod_download;
                            $history->user->save();
                        }
                    }
                }
            });
        $this->comment('Automated Download Refund Command Complete');
    }
}
