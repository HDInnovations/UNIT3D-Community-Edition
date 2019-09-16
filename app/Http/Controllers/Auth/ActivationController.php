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

namespace App\Http\Controllers\Auth;

use App\Models\Group;
use App\Models\UserActivation;
use App\Http\Controllers\Controller;

class ActivationController extends Controller
{
    public function activate($token)
    {
        $bannedGroup = Group::select(['id'])->where('slug', '=', 'banned')->first();
        $memberGroup = Group::select(['id'])->where('slug', '=', 'user')->first();

        $activation = UserActivation::with('user')->where('token', '=', $token)->firstOrFail();
        if ($activation->user->id && $activation->user->group->id != $bannedGroup->id) {
            $activation->user->active = 1;
            $activation->user->can_upload = 1;
            $activation->user->can_download = 1;
            $activation->user->can_request = 1;
            $activation->user->can_comment = 1;
            $activation->user->can_invite = 1;
            $activation->user->group_id = $memberGroup->id;
            $activation->user->save();

            // Activity Log
            \LogActivity::addToLog('Member '.$activation->user->username.' has successfully activated his/her account.');

            $activation->delete();

            return redirect()->route('login')
                ->withSuccess(trans('auth.activation-success'));
        } else {
            return redirect()->route('login')
                ->withErrors(trans('auth.activation-error'));
        }
    }
}
