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
        $banned_group = cache()->rememberForever('banned_group', function () {
            return Group::where('slug', '=', 'banned')->pluck('id');
        });
        $validating_group = cache()->rememberForever('validating_group', function () {
            return Group::where('slug', '=', 'validating')->pluck('id');
        });
        $leech_group = cache()->rememberForever('leech_group', function () {
            return Group::where('slug', '=', 'leech')->pluck('id');
        });
        $disabled_group = cache()->rememberForever('disabled_group', function () {
            return Group::where('slug', '=', 'disabled')->pluck('id');
        });
        $pruned_group = cache()->rememberForever('pruned_group', function () {
            return Group::where('slug', '=', 'pruned')->pluck('id');
        });

        User::whereNotIn('group_id', [$banned_group[0], $validating_group[0], $leech_group[0], $disabled_group[0], $pruned_group[0]])->update(['can_download' => '1', 'can_request' => '1']);
        User::whereIn('group_id', [$banned_group[0], $validating_group[0], $leech_group[0], $disabled_group[0], $pruned_group[0]])->update(['can_download' => '0', 'can_request' => '0']);

        $warning = Warning::with('warneduser')->select(DB::raw('user_id, count(*) as value'))->where('active', '=', 1)->groupBy('user_id')->having('value', '>=', config('hitrun.revoke'))->get();

        foreach ($warning as $deny) {
            if ($deny->warneduser->can_download == 1 && $deny->warneduser->can_request == 1) {
                $deny->warneduser->can_download = 0;
                $deny->warneduser->can_request = 0;
                $deny->warneduser->save();
            }
        }
        $this->comment('Automated User Permissions Revoke Command Complete');
    }
}
