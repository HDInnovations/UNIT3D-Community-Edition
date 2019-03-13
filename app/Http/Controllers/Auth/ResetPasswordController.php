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
use Illuminate\Support\Str;
use App\Models\UserActivation;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function resetPassword($user, $password)
    {
        $validatingGroup = Group::select(['id'])->where('slug', '=', 'validating')->first();
        $memberGroup = Group::select(['id'])->where('slug', '=', 'user')->first();
        $user->password = bcrypt($password);
        $user->remember_token = Str::random(60);

        if ($user->group_id === $validatingGroup->id) {
            $user->group_id = $memberGroup->id;
        }

        $user->active = true;
        $user->save();

        // Activity Log
        \LogActivity::addToLog('Member '.$user->username.' has successfully reset his/her password.');

        UserActivation::where('user_id', '=', $user->id)->delete();

        $this->guard()->login($user);
    }
}
