<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
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
        if (!$user->active) {
            $this->guard()->logout();

            return redirect('login')->with(Toastr::warning('This account is not activated, Please check your email for activation link. If you did not receive the activation code, please click "forgot password" link on the login page.', 'Whoops!', ['options']));
        }
        if ($user->group_id == 5) {
            $this->guard()->logout();

            return redirect('login')->with(Toastr::error('This account is Banned!', 'Whoops!', ['options']));
        }
        return redirect('/');
    }
}
