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

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \Toastr;

class LockAccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'lock']);
    }

    public function lockscreen()
    {
        session(['locked' => 'true']);
        return view('user.lockscreen');
    }

    public function unlock(Request $request)
    {

        $password = $request->password;

        $this->validate($request, [
            'password' => 'required|string',
        ]);

        if (\Hash::check($password, \Auth::user()->password)) {
            $request->session()->forget('locked');
            return redirect()->route('home')->with(Toastr::success('Your Account Has Been Unlocked Successfully!', 'Yay!', ['options']));
        }
        return back()->with(Toastr::error('Your Password Is Incorrect', 'Whoops!', ['options']));
    }

}
