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

use App\User;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\UsernameReminder;

class ForgotUsernameController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * ForgotUsernameController Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Forgot Username Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showForgotUsernameForm()
    {
        return view('auth.username');
    }

    /**
     * Send Username Reminder.
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function sendUsernameReminder(Request $request)
    {
        $email = $request->get('email');

        $v = validator($request->all(), [
            'email' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('username.request')
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $user = User::where('email', '=', $email)->first();

            if (empty($user)) {
                return redirect()->route('username.request')
                    ->with($this->toastr->error('We could not find this email in our system!', 'Whoops!', ['options']));
            }

            //send username reminder notification
            $user->notify(new UsernameReminder());

            return redirect()->route('login')
                ->with($this->toastr->success('Your username has been sent to your email address!', 'Yay!', ['options']));
        }
    }
}
