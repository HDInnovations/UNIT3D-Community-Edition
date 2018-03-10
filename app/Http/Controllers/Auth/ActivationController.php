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

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\UserActivation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use \Toastr;

class ActivationController extends Controller
{
    protected $group_id = 3;

    public function activate($token)
    {
        $activation = UserActivation::with('user')->where('token', $token)->firstOrFail();
        if (!empty($activation->user->id) && $activation->user->group->id != 5) {
            $activation->user->active = true;
            $activation->user->group_id = $this->group_id;
            $activation->user->save();

            // Activity Log
            \LogActivity::addToLog("Member " . $activation->user->username . " has successfully activated his/her account.");

            $activation->delete();
            return redirect()->route('login')->with(Toastr::success('Account Confirmed! You May Now Login!', 'Yay!', ['options']));
        } else {
            return redirect()->route('login')->with(Toastr::error('Banned or Invalid Token Or Account Already Confirmed!', 'Whoops!', ['options']));
        }
    }
}
