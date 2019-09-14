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
 * @author     Mr.G
 */

namespace App\Console\Commands;

use App\Models\Group;
use App\Models\User;
use App\Models\Warning;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoRevokePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:revoke_permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revokes Certain Permissions Of Users Who Have Above X Active Warnings';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $bannedGroup = Group::select(['id'])->where('slug', '=', 'banned')->first();
        $validatingGroup = Group::select(['id'])->where('slug', '=', 'validating')->first();
        $leechGroup = Group::select(['id'])->where('slug', '=', 'leech')->first();
        $disabledGroup = Group::select(['id'])->where('slug', '=', 'disabled')->first();
        $prunedGroup = Group::select(['id'])->where('slug', '=', 'pruned')->first();

        User::whereNotIn('group_id', [$bannedGroup->id, $validatingGroup->id, $leechGroup->id, $disabledGroup->id, $prunedGroup->id])->update(['can_download' => '1', 'can_request' => '1']);
        User::whereIn('group_id', [$bannedGroup->id, $validatingGroup->id, $leechGroup->id, $disabledGroup->id, $prunedGroup->id])->update(['can_download' => '0', 'can_request' => '0']);

        $warning = Warning::with('warneduser')->select(DB::raw('user_id, count(*) as value'))->where('active', '=', 1)->groupBy('user_id')->having('value', '>=', config('hitrun.revoke'))->get();

        foreach ($warning as $deny) {
            if ($deny->warneduser->can_download == 1 && $deny->warneduser->can_request == 1) {
                $deny->warneduser->can_download = 0;
                $deny->warneduser->can_request = 0;
                $deny->warneduser->save();
            }
        }
    }
}
