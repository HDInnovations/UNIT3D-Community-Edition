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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Requests\ValidateSecretRequest;
use App\Jobs\SendActivationMail;
use App\UserActivation;
use App\User;
use App\Shoutbox;
use App\PrivateMessage;
use App\Group;
use App\Invite;
use App\Rules\Captcha;
use \Toastr;
use Carbon\Carbon;
use Cache;

class RegisterController extends Controller
{
    public function register(Request $request, $code = null)
    {
        $current = Carbon::now();
        $user = new User();

        // Make sure open reg is off and ivite code is present
        if (config('other.invite-only') == true && $code == null) {
            return view('auth.login')->with(Toastr::error('Open Reg Closed! You Must Be Invited To Register! You Have Been Redirected To Login Page!', 'Whoops!', ['options']));
        }

        if ($request->isMethod('post')) {
            // Make sure open reg is off and ivite code exsist and has not been used already
            $key = Invite::where('code', '=', $code)->first();
            if (config('other.invite-only') == true && (!$key || $key->accepted_by !== null)) {
                return view('auth.register', ['code' => $code])->with(Toastr::error('Invalid or Expired Invite Key!', 'Whoops!', ['options']));
            }

            $v = validator($request->all(), [
                'username' => 'required|alpha_dash|min:3|max:20|unique:users',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6',
                'g-recaptcha-response' => new Captcha()
            ]);

            if ($v->fails()) {
                $errors = $v->messages();
                return redirect()->route('register', ['code' => $code])->with(Toastr::error('Either The Username/Email is already in use or you missed a field. Make sure password is also min 6 charaters!', 'Whoops!', ['options']));
            } else {
                // Create The User
                $group = Group::where('slug', '=', 'validating')->first();
                $user->username = $request->input('username');
                $user->email = $request->input('email');
                $user->password = Hash::make($request->input('password'));
                $user->passkey = md5(uniqid() . time() . microtime());
                $user->rsskey = md5(uniqid() . time() . microtime() . $user->password);
                $user->uploaded = config('other.default_upload');
                $user->downloaded = config('other.default_download');
                $user->style = config('other.default_style', 0);
                $user->group_id = $group->id;
                $user->save();

                if ($key) {
                    // Update The Invite Record
                    $key->accepted_by = $user->id;
                    $key->accepted_at = new Carbon();
                    $key->save();
                }

                // Handle The Activation System
                $token = hash_hmac('sha256', $user->username . $user->email . str_random(16), config('app.key'));
                UserActivation::create([
                    'user_id' => $user->id,
                    'token' => $token,
                ]);
                $this->dispatch(new SendActivationMail($user, $token));

                $appurl = config('app.url');
                // Post To Shoutbox
                Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "Welcome [url={$appurl}/" . $user->username . "." . $user->id . "]" . $user->username . "[/url] hope you enjoy the community :rocket:"]);
                Cache::forget('shoutbox_messages');

                // Send Welcome PM
                PrivateMessage::create(['sender_id' => "1", 'reciever_id' => $user->id, 'subject' => config('welcomepm.subject'), 'message' => config('welcomepm.message')]);

                // Activity Log
                \LogActivity::addToLog("Member " . $user->username . " has successfully registered to site.");

                return redirect()->route('login')->with(Toastr::success('Thanks for signing up! Please check your email to Validate your account', 'Yay!', ['options']));
            }
        }
        return view('auth.register', ['code' => $code]);
    }
}
