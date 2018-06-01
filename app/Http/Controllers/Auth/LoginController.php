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
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use \Toastr;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public $maxAttempts = 5; // Max Attempts Until Lockout
    public $decayMinutes = 30; // Minutes

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function username()
    {
        return 'username';
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->active == 0 || $user->group_id == 1) {
            auth()->logout();
            $request->session()->flush();
            return redirect()->route('login')
                ->with(Toastr::error('This account has not been activated and is still in validating group, Please check your email for activation link. If you did not receive the activation code, please click "forgot password" and complete the steps.', 'Whoops!', ['options']));
        }
        if ($user->group_id == 5) {
            auth()->logout();
            $request->session()->flush();
            return redirect()->route('login')
                ->with(Toastr::error('This account is Banned!', 'Whoops!', ['options']));
        }
        return redirect('/');
    }
}
