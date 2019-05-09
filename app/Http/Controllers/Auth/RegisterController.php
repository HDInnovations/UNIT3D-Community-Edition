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

use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Group;
use App\Models\Invite;
use App\Rules\Captcha;
use App\Models\UserPrivacy;
use Illuminate\Http\Request;
use App\Models\PrivateMessage;
use App\Models\UserActivation;
use App\Jobs\SendActivationMail;
use App\Models\UserNotification;
use App\Http\Controllers\Controller;
use App\Repositories\ChatRepository;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * @var ChatRepository
     */
    private $chat;

    /**
     * RegisterController Constructor.
     *
     * @param ChatRepository $chat
     */
    public function __construct(ChatRepository $chat)
    {
        $this->chat = $chat;
    }

    /**
     * Registration Form.
     *
     * @param $code
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registrationForm($code = null)
    {
        // Make sure open reg is off, invite code is not present and application signups enabled
        if ($code === 'null' && config('other.invite-only') == 1 && config('other.application_signups') == true) {
            return redirect()->route('application.create')
                ->withInfo(trans('auth.allow-invite-appl'));
        }

        // Make sure open reg is off and invite code is not present
        if ($code === 'null' && config('other.invite-only') == 1) {
            return redirect()->route('login')
                ->withWarning(trans('auth.allow-invite'));
        }

        return view('auth.register', ['code' => $code]);
    }

    public function register(Request $request, $code = null)
    {
        // Make sure open reg is off and invite code exist and has not been used already
        $key = Invite::where('code', '=', $code)->first();
        if (config('other.invite-only') == 1 && (! $key || $key->accepted_by !== null)) {
            return redirect()->route('registrationForm', ['code' => $code])
                ->withErrors(trans('auth.invalid-key'));
        }

        $validatingGroup = Group::select(['id'])->where('slug', '=', 'validating')->first();
        $user = new User();
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->passkey = md5(uniqid().time().microtime());
        $user->rsskey = md5(uniqid().time().microtime().$user->password);
        $user->uploaded = config('other.default_upload');
        $user->downloaded = config('other.default_download');
        $user->style = config('other.default_style', 0);
        $user->locale = config('app.locale');
        $user->group_id = $validatingGroup->id;

        if (config('email-white-blacklist.enabled') === 'allow' && config('captcha.enabled') == true) {
            $v = validator($request->all(), [
                'username'             => 'required|alpha_dash|min:3|max:20|unique:users',
                'email'                => 'required|email|max:255|unique:users|email_list:allow', // Whitelist
                'password'             => 'required|min:8',
                'g-recaptcha-response' => 'required|recaptcha',
            ]);
        } elseif (config('email-white-blacklist.enabled') === 'allow') {
            $v = validator($request->all(), [
                'username' => 'required|alpha_dash|min:3|max:20|unique:users',
                'email'    => 'required|email|max:255|unique:users|email_list:allow', // Whitelist
                'password' => 'required|min:8',
            ]);
        } elseif (config('email-white-blacklist.enabled') === 'block' && config('captcha.enabled') == true) {
            $v = validator($request->all(), [
                'username'             => 'required|alpha_dash|min:3|max:20|unique:users',
                'email'                => 'required|email|max:255|unique:users|email_list:block', // Blacklist
                'password'             => 'required|min:8',
                'g-recaptcha-response' => 'required|recaptcha',
            ]);
        } elseif (config('email-white-blacklist.enabled') === 'block') {
            $v = validator($request->all(), [
                'username' => 'required|alpha_dash|min:3|max:20|unique:users',
                'email'    => 'required|email|max:255|unique:users|email_list:block', // Blacklist
                'password' => 'required|min:8',
            ]);
        } elseif (config('captcha.enabled') == true) {
            $v = validator($request->all(), [
                'username'             => 'required|alpha_dash|min:3|max:20|unique:users',
                'email'                => 'required|email|max:255|unique:users',
                'password'             => 'required|min:8',
                'g-recaptcha-response' => 'required|recaptcha',
            ]);
        } else {
            $v = validator($request->all(), [
                'username' => 'required|alpha_dash|min:3|max:20|unique:users', //Default
                'email'    => 'required|email|max:255|unique:users',
                'password' => 'required|min:8',
            ]);
        }

        if ($v->fails()) {
            return redirect()->route('registrationForm', ['code' => $code])
                ->withErrors($v->errors());
        } else {
            $user->save();

            $privacy = new UserPrivacy();
            $privacy->setDefaultValues();
            $privacy->user_id = $user->id;
            $privacy->save();

            $notification = new UserNotification();
            $notification->setDefaultValues();
            $notification->user_id = $user->id;
            $notification->save();

            if ($key) {
                // Update The Invite Record
                $key->accepted_by = $user->id;
                $key->accepted_at = new Carbon();
                $key->save();
            }

            // Handle The Activation System
            $token = hash_hmac('sha256', $user->username.$user->email.Str::random(16), config('app.key'));
            $activation = new UserActivation();
            $activation->user_id = $user->id;
            $activation->token = $token;
            $activation->save();
            $this->dispatch(new SendActivationMail($user, $token));

            // Select A Random Welcome Message
            $profile_url = hrefProfile($user);

            $welcomeArray = [
                "[url={$profile_url}]{$user->username}[/url], Welcome to ".config('other.title').'! Hope you enjoy the community :rocket:',
                "[url={$profile_url}]{$user->username}[/url], We've been expecting you :space_invader:",
                "[url={$profile_url}]{$user->username}[/url] has arrived. Party's over. :cry:",
                "It's a bird! It's a plane! Nevermind, it's just [url={$profile_url}]{$user->username}[/url].",
                "Ready player [url={$profile_url}]{$user->username}[/url].",
                "A wild [url={$profile_url}]{$user->username}[/url] appeared.",
                'Welcome to '.config('other.title')." [url={$profile_url}]{$user->username}[/url]. We were expecting you ( ͡° ͜ʖ ͡°)",
            ];
            $selected = mt_rand(0, count($welcomeArray) - 1);

            $this->chat->systemMessage(
                "{$welcomeArray[$selected]}"
            );

            // Send Welcome PM
            $pm = new PrivateMessage();
            $pm->sender_id = 1;
            $pm->receiver_id = $user->id;
            $pm->subject = config('welcomepm.subject');
            $pm->message = config('welcomepm.message');
            $pm->save();

            // Activity Log
            \LogActivity::addToLog('Member '.$user->username.' has successfully registered to site.');

            return redirect()->route('login')
                ->withSuccess(trans('auth.register-thanks'));
        }
    }
}
