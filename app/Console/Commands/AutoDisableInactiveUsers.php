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

use App\Jobs\SendDisableUserMail;
use App\Models\Group;
use App\Models\User;
use App\Services\Unit3dAnnounce;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Exception;

/**
 * @see \Tests\Unit\Console\Commands\AutoDisableInactiveUsersTest
 */
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
    protected $description = 'User account must be at least x days old & user account x days Of inactivity to be disabled';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle(): void
    {
        if (config('pruning.user_pruning')) {
            $disabledGroup = cache()->rememberForever('disabled_group', fn () => Group::where('slug', '=', 'disabled')->pluck('id'));

            $current = Carbon::now();

            $matches = User::whereIntegerInRaw('group_id', config('pruning.group_ids'))->get();

            $users = $matches->where('created_at', '<', $current->copy()->subDays(config('pruning.account_age'))->toDateTimeString())
                ->where('last_login', '<', $current->copy()->subDays(config('pruning.last_login'))->toDateTimeString())
                ->all();

            foreach ($users as $user) {
                if ($user->seedingTorrents()->doesntExist()) {
                    $user->group_id = $disabledGroup[0];
                    $user->can_upload = false;
                    $user->can_download = false;
                    $user->can_comment = false;
                    $user->can_invite = false;
                    $user->can_request = false;
                    $user->can_chat = false;
                    $user->disabled_at = Carbon::now();
                    $user->save();

                    cache()->forget('user:'.$user->passkey);
                    Unit3dAnnounce::addUser($user);

                    // Send Email
                    dispatch(new SendDisableUserMail($user));
                }
            }
        }

        $this->comment('Automated User Disable Command Complete');
    }
}
