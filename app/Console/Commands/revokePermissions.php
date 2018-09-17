<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Mr.G
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\PrivateMessage;
use App\Warning;
use App\User;

class revokePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'revokePermissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revokes certain permissions of users who have above x active warnings';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $bannedGroup = Group::where('slug', '=', 'banned')->first();
        $validatingGroup = Group::where('slug', '=', 'validating')->first();
        $leechGroup = Group::where('slug', '=', 'leech')->first();
        $disabledGroup = Group::where('slug', '=', 'disabled')->first();
        $prunedGroup = Group::where('slug', '=', 'pruned')->first();

        User::whereNotIn('group_id', [$bannedGroup,$validatingGroup,$leechGroup,$disabledGroup,$prunedGroup])->update(['can_download' => '1', 'can_request' => '1']);
        User::whereIn('group_id', [$bannedGroup,$validatingGroup,$leechGroup,$disabledGroup,$prunedGroup])->update(['can_download' => '0', 'can_request' => '0']);

        $warning = Warning::with('warneduser')->select(DB::raw('user_id, count(*) as value'))->where('active', 1)->groupBy('user_id')->having('value', '>=', config('hitrun.revoke'))->get();

        foreach ($warning as $deny) {
            if ($deny->warneduser->can_download == 1 && $deny->warneduser->can_request == 1) {
                $deny->warneduser->can_download = 0;
                $deny->warneduser->can_request = 0;
                $deny->warneduser->save();
            }
        }
    }
}
