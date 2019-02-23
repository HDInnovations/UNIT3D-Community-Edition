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
use Brian2694\Toastr\Toastr;
use App\Models\UserActivation;
use App\Http\Controllers\Controller;

class ActivationController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * ActivationController Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    public function activate($token)
    {
        $bannedGroup = Group::where('slug', '=', 'banned')->select('id')->first();
        $memberGroup = Group::where('slug', '=', 'user')->select('id')->first();

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

            return redirect()->route('login')->with($this->toastr->success('Account Confirmed! You May Now Login!', 'Yay!', ['options']));
        } else {
            return redirect()->route('login')->with($this->toastr->error('Banned or Invalid Token Or Account Already Confirmed!', 'Whoops!', ['options']));
        }
    }
}
