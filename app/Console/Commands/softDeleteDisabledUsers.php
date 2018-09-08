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
use App\Mail\DeleteUser;
use App\User;
use App\Group;
use Carbon\Carbon;

class softDeleteDisabledUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'softDeleteDisabledUsers';

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
        $disabledGroup = Group::where('slug', '=', 'disabled')->first();
        $prunedGroup = Group::where('slug', '=', 'pruned')->first();

        $current = Carbon::now();
        $users = User::where('group_id', '=', $disabledGroup->id)
            ->where('disabled_at', '<', $current->copy()->subDays(config('other.soft_delete'))->toDateTimeString())
            ->get();

        foreach ($users as $user) {

            // Send Email
            Mail::to($user->email)->send(new DeleteUser($user));

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
