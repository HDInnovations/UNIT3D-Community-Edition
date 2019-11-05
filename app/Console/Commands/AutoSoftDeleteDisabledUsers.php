<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Console\Commands;

use App\Jobs\SendDeleteUserMail;
use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoSoftDeleteDisabledUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:softdelete_disabled_users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'User Account Must Be In Disabled Group For Atleast x Days';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (config('pruning.user_pruning') == true) {
            $disabledGroup = Group::select(['id'])->where('slug', '=', 'disabled')->first();
            $prunedGroup = Group::select(['id'])->where('slug', '=', 'pruned')->first();

            $current = Carbon::now();
            $users = User::where('group_id', '=', $disabledGroup->id)
                ->where('disabled_at', '<', $current->copy()->subDays(config('pruning.soft_delete'))->toDateTimeString())
                ->get();

            foreach ($users as $user) {
                // Send Email
                dispatch(new SendDeleteUserMail($user));

                $user->can_upload = 0;
                $user->can_download = 0;
                $user->can_comment = 0;
                $user->can_invite = 0;
                $user->can_request = 0;
                $user->can_chat = 0;
                $user->group = $prunedGroup->id;
                $user->deleted_by = 1;
                $user->save();
                $user->delete();
            }
        }
    }
}
