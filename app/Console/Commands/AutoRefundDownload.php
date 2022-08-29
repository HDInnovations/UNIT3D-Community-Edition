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
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

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
        $current = Carbon::now();
        // grace-in-seconds * (Months / seedtime-requirement)
        $grace = ((\config('hitrun.grace') * 24 * 60 * 60) * (2_592_000 / \config('hitrun.seedtime')));

        History::query()
            ->where('active', '=', 1)
            ->where('seeder', '=', 1)
            ->where('seedtime', '>=', (2_592_000 + \config('hitrun.seedtime'))
            ->where('created_at', '>=', $current->copy()->subSeconds(2_592_000 + $grace)->toDateTimeString()))
            ->chunkById(100, function ($histories) use ($current) {
                foreach ($histories as $history) {
                    $user = User::where('id', '=', $history->user_id)->first();
                    $torrent = Torrent::where('id', '=', $history->torrent_id)->first();
                    if ((\config('other.refundable') || $torrent->refundable == 1 || $user->group->is_refundable == 1) && ($torrent->user_id != $user->id)) {
                        $monthly_refund = ($torrent->size / 12);

                        // One month refund
                        $grace = ((\config('hitrun.grace') * 24 * 60 * 60) * (2_592_000 / \config('hitrun.seedtime')));
                        if ($history->seedtime >= 2_592_000 + \config('hitrun.seedtime') && $history->seedtime < (2_592_000 * 2) + \config('hitrun.seedtime') && $history->created_at >= $current->copy()->subSeconds(2_592_000 + $grace)->toDateTimeString()) {
                            $mod_download = $history->refunded_download != 0 && $monthly_refund >= $history->refunded_download ? ($monthly_refund - $history->refunded_download) : $monthly_refund;
                            $refund_amount = $history->refunded_download != $monthly_refund ? $monthly_refund : 0;
                        }

                        // Two months refund
                        $grace = ((\config('hitrun.grace') * 24 * 60 * 60) * (5_184_000 / \config('hitrun.seedtime')));
                        if ($history->seedtime >= (2_592_000 * 2) + \config('hitrun.seedtime') && $history->seedtime < (2_592_000 * 3) + \config('hitrun.seedtime') && $history->created_at >= $current->copy()->subSeconds(5_184_000 + $grace)->toDateTimeString()) {
                            $mod_download = $history->refunded_download != 0 && ($monthly_refund * 2) >= $history->refunded_download ? (($monthly_refund * 2) - $history->refunded_download) : ($monthly_refund * 2);
                            $refund_amount = $history->refunded_download != ($monthly_refund * 2) ? ($monthly_refund * 2) : 0;
                        }

                        // Three months refund
                        $grace = ((\config('hitrun.grace') * 24 * 60 * 60) * (7_776_000 / \config('hitrun.seedtime')));
                        if ($history->seedtime >= (2_592_000 * 3) + \config('hitrun.seedtime') && $history->seedtime < (2_592_000 * 4) + \config('hitrun.seedtime') && $history->created_at >= $current->copy()->subSeconds(7_776_000 + $grace)->toDateTimeString()) {
                            $mod_download = $history->refunded_download != 0 && ($monthly_refund * 3) >= $history->refunded_download ? (($monthly_refund * 3) - $history->refunded_download) : ($monthly_refund * 3);
                            $refund_amount = $history->refunded_download != ($monthly_refund * 3) ? ($monthly_refund * 3) : 0;
                        }

                        // Four months refund
                        $grace = ((\config('hitrun.grace') * 24 * 60 * 60) * (10_368_000 / \config('hitrun.seedtime')));
                        if ($history->seedtime >= (2_592_000 * 4) + \config('hitrun.seedtime') && $history->seedtime < (2_592_000 * 5) + \config('hitrun.seedtime') && $history->created_at >= $current->copy()->subSeconds(10_368_000 + $grace)->toDateTimeString()) {
                            $mod_download = $history->refunded_download != 0 && ($monthly_refund * 4) >= $history->refunded_download ? (($monthly_refund * 4) - $history->refunded_download) : ($monthly_refund * 4);
                            $refund_amount = $history->refunded_download != ($monthly_refund * 4) ? ($monthly_refund * 4) : 0;
                        }

                        // Five months refund
                        $grace = ((\config('hitrun.grace') * 24 * 60 * 60) * (12_960_000 / \config('hitrun.seedtime')));
                        if ($history->seedtime >= (2_592_000 * 5) + \config('hitrun.seedtime') && $history->seedtime < (2_592_000 * 6) + \config('hitrun.seedtime') && $history->created_at >= $current->copy()->subSeconds(12_960_000 + $grace)->toDateTimeString()) {
                            $mod_download = $history->refunded_download != 0 && ($monthly_refund * 5) >= $history->refunded_download ? (($monthly_refund * 5) - $history->refunded_download) : ($monthly_refund * 5);
                            $refund_amount = $history->refunded_download != ($monthly_refund * 5) ? ($monthly_refund * 5) : 0;
                        }

                        // Six months refund
                        $grace = ((\config('hitrun.grace') * 24 * 60 * 60) * (15_552_000 / \config('hitrun.seedtime')));
                        if ($history->seedtime >= (2_592_000 * 6) + \config('hitrun.seedtime') && $history->seedtime < (2_592_000 * 7) + \config('hitrun.seedtime') && $history->created_at >= $current->copy()->subSeconds(15_552_000 + $grace)->toDateTimeString()) {
                            $mod_download = $history->refunded_download != 0 && ($monthly_refund * 6) >= $history->refunded_download ? (($monthly_refund * 6) - $history->refunded_download) : ($monthly_refund * 6);
                            $refund_amount = $history->refunded_download != ($monthly_refund * 6) ? ($monthly_refund * 6) : 0;
                        }

                        // Seven months refund
                        $grace = ((\config('hitrun.grace') * 24 * 60 * 60) * (18_144_000 / \config('hitrun.seedtime')));
                        if ($history->seedtime >= (2_592_000 * 7) + \config('hitrun.seedtime') && $history->seedtime < (2_592_000 * 8) + \config('hitrun.seedtime') && $history->created_at >= $current->copy()->subSeconds(18_144_000 + $grace)->toDateTimeString()) {
                            $mod_download = $history->refunded_download != 0 && ($monthly_refund * 7) >= $history->refunded_download ? (($monthly_refund * 7) - $history->refunded_download) : ($monthly_refund * 7);
                            $refund_amount = $history->refunded_download != ($monthly_refund * 7) ? ($monthly_refund * 7) : 0;
                        }

                        // Eight months refund
                        $grace = ((\config('hitrun.grace') * 24 * 60 * 60) * (20_736_000 / \config('hitrun.seedtime')));
                        if ($history->seedtime >= (2_592_000 * 8) + \config('hitrun.seedtime') && $history->seedtime < (2_592_000 * 9) + \config('hitrun.seedtime') && $history->created_at >= $current->copy()->subSeconds(20_736_000 + $grace)->toDateTimeString()) {
                            $mod_download = $history->refunded_download != 0 && ($monthly_refund * 8) >= $history->refunded_download ? (($monthly_refund * 8) - $history->refunded_download) : ($monthly_refund * 8);
                            $refund_amount = $history->refunded_download != ($monthly_refund * 8) ? ($monthly_refund * 8) : 0;
                        }

                        // Nine months refund
                        $grace = ((\config('hitrun.grace') * 24 * 60 * 60) * (23_328_000 / \config('hitrun.seedtime')));
                        if ($history->seedtime >= (2_592_000 * 9) + \config('hitrun.seedtime') && $history->seedtime < (2_592_000 * 10) + \config('hitrun.seedtime') && $history->created_at >= $current->copy()->subSeconds(23_328_000 + $grace)->toDateTimeString()) {
                            $mod_download = $history->refunded_download != 0 && ($monthly_refund * 9) >= $history->refunded_download ? (($monthly_refund * 9) - $history->refunded_download) : ($monthly_refund * 9);
                            $refund_amount = $history->refunded_download != ($monthly_refund * 9) ? ($monthly_refund * 9) : 0;
                        }

                        // Ten months refund
                        $grace = ((\config('hitrun.grace') * 24 * 60 * 60) * (25_920_000 / \config('hitrun.seedtime')));
                        if ($history->seedtime >= (2_592_000 * 10) + \config('hitrun.seedtime') && $history->seedtime < (2_592_000 * 11) + \config('hitrun.seedtime') && $history->created_at >= $current->copy()->subSeconds(25_920_000 + $grace)->toDateTimeString()) {
                            $mod_download = $history->refunded_download != 0 && ($monthly_refund * 10) >= $history->refunded_download ? (($monthly_refund * 10) - $history->refunded_download) : ($monthly_refund * 10);
                            $refund_amount = $history->refunded_download != ($monthly_refund * 10) ? ($monthly_refund * 10) : 0;
                        }

                        // Eleven months refund
                        $grace = ((\config('hitrun.grace') * 24 * 60 * 60) * (28_512_000 / \config('hitrun.seedtime')));
                        if ($history->seedtime >= (2_592_000 * 11) + \config('hitrun.seedtime') && $history->seedtime < (2_592_000 * 12) + \config('hitrun.seedtime') && $history->created_at >= $current->copy()->subSeconds(28_512_000 + $grace)->toDateTimeString()) {
                            $mod_download = $history->refunded_download != 0 && ($monthly_refund * 11) >= $history->refunded_download ? (($monthly_refund * 11) - $history->refunded_download) : ($monthly_refund * 11);
                            $refund_amount = $history->refunded_download != ($monthly_refund * 11) ? ($monthly_refund * 11) : 0;
                        }

                        // Twelve months refund
                        $grace = ((\config('hitrun.grace') * 24 * 60 * 60) * (31_104_000 / \config('hitrun.seedtime')));
                        if ($history->seedtime >= (2_592_000 * 12) + \config('hitrun.seedtime') && $history->created_at >= $current->copy()->subSeconds(31_104_000 + $grace)->toDateTimeString()) {
                            $mod_download = $history->refunded_download != 0 && $torrent->size >= $history->refunded_download ? ($torrent->size - $history->refunded_download) : $torrent->size;
                            $refund_amount = $history->refunded_download != $torrent->size ? $torrent->size : 0;
                        }

                        if (isset($refund_amount) && $refund_amount != 0) {
                            $history->refunded_download += $refund_amount;
                            $history->save();
                            $user->downloaded -= $mod_download;
                            $user->save();
                        }
                    }
                }
            });
        $this->comment('Automated Download Refund Command Complete');
    }
}