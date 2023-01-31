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
use App\Models\Warning;
use App\Notifications\UserWarning;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * @see \Tests\Unit\Console\Commands\AutoWarningTest
 */
class AutoWarning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:warning';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Post Warnings To Users Accounts and Warnings Table';

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle(): void
    {
        if (\config('hitrun.enabled') === true) {
            $carbon = new Carbon();
            $hitrun = History::with(['user', 'torrent'])
                ->where('actual_downloaded', '>', 0)
                ->where('prewarn', '=', 1)
                ->where('hitrun', '=', 0)
                ->where('immune', '=', 0)
                ->where('active', '=', 0)
                ->where('seedtime', '<', \config('hitrun.seedtime'))
                ->where('updated_at', '<', $carbon->copy()->subDays(\config('hitrun.grace'))->toDateTimeString())
                ->get();

            foreach ($hitrun as $hr) {
                if (! $hr->user->group->is_immune && $hr->actual_downloaded > ($hr->torrent->size * (\config('hitrun.buffer') / 100))) {
                    $exsist = Warning::withTrashed()
                        ->where('torrent', '=', $hr->torrent->id)
                        ->where('user_id', '=', $hr->user->id)
                        ->first();
                    // Insert Warning Into Warnings Table if doesnt already exsist
                    if ($exsist === null) {
                        $warning = new Warning();
                        $warning->user_id = $hr->user->id;
                        $warning->warned_by = '1';
                        $warning->torrent = $hr->torrent->id;
                        $warning->reason = \sprintf('Hit and Run Warning For Torrent %s', $hr->torrent->name);
                        $warning->expires_on = $carbon->copy()->addDays(\config('hitrun.expire'));
                        $warning->active = '1';
                        $warning->save();

                        // Add +1 To Users Warnings Count In Users Table
                        $hr->hitrun = 1;
                        $hr->user->hitandruns++;
                        $hr->user->save();

                        // Send Notifications
                        $hr->user->notify(new UserWarning($hr->user, $hr->torrent));

                        $hr->save();
                    }
                }
            }

            // Calculate User Warning Count and Disable DL Priv If Required.
            $warnings = Warning::with('warneduser')->select(DB::raw('user_id, count(*) as value'))->where('active', '=', 1)->groupBy('user_id')->having('value', '>=', \config('hitrun.max_warnings'))->get();

            foreach ($warnings as $warning) {
                if ($warning->warneduser->can_download === 1) {
                    $warning->warneduser->can_download = 0;
                    $warning->warneduser->save();

                    \cache()->forget('user:'.$warning->warneduser->passkey);
                }
            }
        }

        $this->comment('Automated User Warning Command Complete');
    }
}
