<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Console\Commands;

use App\User;
use App\Group;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Jobs\SendDisableUserMail;

class AutoDisableInactiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:disable_inactive_users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'User Account Must Be Atleast x Days Old & User Account x Days Of Inactivity To Be Disabled';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (config('pruning.user_pruning') == true) {
            $disabledGroup = Group::where('slug', '=', 'disabled')->select('id')->first();
            $current = Carbon::now();

            $matches = User::whereIn('group_id', [config('pruning.group_ids')]);

            $users = $matches->where('created_at', '<', $current->copy()->subDays(config('pruning.account_age'))->toDateTimeString())
                ->where('last_login', '<', $current->copy()->subDays(config('pruning.last_login'))->toDateTimeString())
                ->get();

            foreach ($users as $user) {
                if ($user->getSeeding() !== 0) {
                    $user->group_id = $disabledGroup->id;
                    $user->can_upload = 0;
                    $user->can_download = 0;
                    $user->can_comment = 0;
                    $user->can_invite = 0;
                    $user->can_request = 0;
                    $user->can_chat = 0;
                    $user->disabled_at = Carbon::now();
                    $user->save();

                    // Send Email
                    dispatch(new SendDisableUserMail($user));
                }
            }
        }
    }
}
