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

namespace App\Http\Controllers\Staff;

use App\Models\User;
use App\Models\Group;

class MassActionController extends Controller
{
    /**
     * Mass Validate Unvalidated Users.
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function validate()
    {
        $validatingGroup = Group::select(['id'])->where('slug', '=', 'validating')->first();
        $memberGroup = Group::select(['id'])->where('slug', '=', 'user')->first();
        $users = User::where('active', '=', 0)->where('group_id', '=', $validatingGroup->id)->get();

        foreach ($users as $user) {
            $user->group_id = $memberGroup->id;
            $user->active = 1;
            $user->can_upload = 1;
            $user->can_download = 1;
            $user->can_request = 1;
            $user->can_comment = 1;
            $user->can_invite = 1;
            $user->save();
        }

        return redirect()->route('staff.dashboard.index')
            ->withSuccess('Unvalidated Accounts Are Now Validated');
    }
}
