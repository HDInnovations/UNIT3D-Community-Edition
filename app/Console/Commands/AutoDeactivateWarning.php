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

use App\Models\Warning;
use App\Notifications\UserManualWarningExpire;
use App\Notifications\UserWarningExpire;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * @see \Tests\Unit\Console\Commands\AutoDeactivateWarningTest
 */
class AutoDeactivateWarning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:deactivate_warning';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Deactivates User Warnings If Expired';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $current = Carbon::now();
        $warnings = Warning::with(['warneduser', 'torrenttitle'])
            ->where('active', '=', 1)
            ->get();

        foreach ($warnings as $warning) {
            if ($warning->expires_on <= $current || ($warning->torrenttitle && $warning->torrenttitle->history()->where('user_id', '=', $warning->warneduser->id)->first()->seedtime >= config('hitrun.seedtime'))) {
                // Set Records Active To 0 in warnings table
                $warning->active = false;
                $warning->save();

                // Send Notifications
                if ($warning->torrenttitle) {
                    $warning->warneduser->notify(new UserWarningExpire($warning->warneduser, $warning->torrenttitle));
                } else {
                    $warning->warneduser->notify(new UserManualWarningExpire($warning->warneduser, $warning));
                }
            }
        }

        $this->comment('Automated Warning Deativation Command Complete');
    }
}
