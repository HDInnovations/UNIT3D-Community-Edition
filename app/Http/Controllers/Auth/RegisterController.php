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
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as IlluminateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Requests\ValidateSecretRequest;
use Illuminate\Support\Facades\Input;
use App\Jobs\SendActivationMail;
use App\UserActivation;
use App\User;
use App\Mail\WelcomeUser;
use App\Shoutbox;
use App\PrivateMessage;
use App\Group;
use App\Invite;
use \Toastr;
use Carbon\Carbon;
use Cache;

class RegisterController extends Controller
{
    public function register($code = null)
    {
        // Make sure open reg is off and ivite code exsists and is not used or expired
        if (config('other.invite-only') == true && $code == null) {
            return View::make('auth.login')->with(Toastr::warning('Open Reg Closed! You Must Be Invited To Register!', 'Error', ['options']));
        }

        if (Request::isMethod('post')) {
            $key = Invite::where('code', '=', $code)->first();
            if (config('other.invite-only') == true && !$key) {
                return View::make('auth.register', array('code' => $code))->with(Toastr::warning('Invalid or Expired Invite Key!', 'Error', ['options']));
            }

            $current = Carbon::now();

            $input = Request::all();
            $user = new User();
            $v = Validator::make($input, $user->rules);
            if ($v->fails()) {
                $errors = $v->messages();
                return Redirect::route('register', array('code' => $code))->with(Toastr::warning('Either The Username/Email is already in use or you missed a field. Make sure password is also min 8 charaters!', 'Error', ['options']));
            } else {
                // Create The User
                $group = Group::where('slug', '=', 'validating')->first();
                $user->username = $input['username'];
                $user->email = $input['email'];
                $user->password = Hash::make($input['password']);
                $user->passkey = md5(uniqid() . time() . microtime());
                $user->rsskey = md5(uniqid() . time() . microtime() . $user->password);
                $user->uploaded = 53687091200; // 50GB
                $user->downloaded = 1073741824; // 1GB
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

                // Post To Shoutbox
                Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "Welcome [url={{ route('home') }}/" . $user->username . "." . $user->id . "]" . $user->username . "[/url] hope you enjoy the community :rocket:"]);
                Cache::forget('shoutbox_messages');

                // Send Welcome PM
                PrivateMessage::create(['sender_id' => "0", 'reciever_id' => $user->id, 'subject' => config('welcomepm.subject'), 'message' => config('welcomepm.message')]);

                // Activity Log
                \LogActivity::addToLog("Member " . $user->username . " has successfully registered to site.");

                return Redirect::route('login')->with(Toastr::info('Thanks for signing up! Please check your email to Validate your account', 'Yay!', ['options']));
            }
        }
        return View::make('auth.register', array('code' => $code));
    }
}
