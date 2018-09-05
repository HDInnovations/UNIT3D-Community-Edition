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
use App\Rules\Captcha;
use \Toastr;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // Upon Successful Login
    protected $redirectTo = '/';

    // Max Attempts Until Lockout
    public $maxAttempts = 5;

    // Minutes Lockout
    public $decayMinutes = 60;

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function username()
    {
        return 'username';
    }

    /**
     * Validate The User Login Request
     *
     * @param \Illuminate\Http\Request $request
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
            'g-recaptcha-response' => new Captcha()
        ]);
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
