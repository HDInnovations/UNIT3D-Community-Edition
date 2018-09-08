<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\DisableUser;
use App\User;
use App\Group;
use Carbon\Carbon;

class disableInactiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'disableInactiveUsers';

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
        $group = Group::where('slug', '=', 'disabled')->first();

        $current = Carbon::now();
        $users = User::where('created_at', '<', $current->copy()->subDays(config('other.account_age'))->toDateTimeString())
            ->where('last_login', '<', $current->copy()->subDays(config('other.last_login'))->toDateTimeString())
            ->orWhereNull('last_login')
            ->get();

        foreach ($users as $user) {
            $user->group_id = $group->id;
            $user->can_upload = 0;
            $user->can_download = 0;
            $user->can_comment = 0;
            $user->can_invite = 0;
            $user->can_request = 0;
            $user->can_chat = 0;
            $user->save();

            // Send Email
            Mail::to($user->email)->send(new DisableUser($user));
        }
    }
}
