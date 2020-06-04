<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendActivationMail;
use App\Models\Group;
use App\Models\Invite;
use App\Models\PrivateMessage;
use App\Models\User;
use App\Models\UserActivation;
use App\Models\UserNotification;
use App\Models\UserPrivacy;
use App\Repositories\ChatRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

        $validating_group = cache()->rememberForever('validating_group', fn () => Group::where('slug', '=', 'validating')->pluck('id'));

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
        $user->group_id = $validating_group[0];

        if (config('email-blacklist.enabled') == true) {
            if (config('captcha.enabled') == false) {
                $v = validator($request->all(), [
                    'username' => 'required|alpha_dash|string|between:3,25|unique:users',
                    'password' => 'required|string|between:8,16',
                    'email'    => 'required|string|email|max:70|blacklist|unique:users',
                ]);
            } else {
                $v = validator($request->all(), [
                    'username' => 'required|alpha_dash|string|between:3,25|unique:users',
                    'password' => 'required|string|between:8,16',
                    'email'    => 'required|string|email|max:70|blacklist|unique:users',
                    'captcha'  => 'hiddencaptcha',
                ]);
            }
        } elseif (config('captcha.enabled') == false) {
            $v = validator($request->all(), [
                'username' => 'required|alpha_dash|string|between:3,25|unique:users',
                'password' => 'required|string|between:8,16',
                'email'    => 'required|string|email|max:70|unique:users',
            ]);
        } else {
            $v = validator($request->all(), [
                'username' => 'required|alpha_dash|string|between:3,25|unique:users',
                'password' => 'required|string|between:6,16',
                'email'    => 'required|string|email|max:70|unique:users',
                'captcha'  => 'hiddencaptcha',
            ]);
        }

        if ($v->fails()) {
            return redirect()->route('registrationForm', ['code' => $code])
                ->withErrors($v->errors());
        }
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
        $profile_url = href_profile($user);
        $welcomeArray = [
            sprintf('[url=%s]%s[/url], Welcome to ', $profile_url, $user->username).config('other.title').'! Hope you enjoy the community :rocket:',
            sprintf("[url=%s]%s[/url], We've been expecting you :space_invader:", $profile_url, $user->username),
            sprintf("[url=%s]%s[/url] has arrived. Party's over. :cry:", $profile_url, $user->username),
            sprintf("It's a bird! It's a plane! Nevermind, it's just [url=%s]%s[/url].", $profile_url, $user->username),
            sprintf('Ready player [url=%s]%s[/url].', $profile_url, $user->username),
            sprintf('A wild [url=%s]%s[/url] appeared.', $profile_url, $user->username),
            'Welcome to '.config('other.title').sprintf(' [url=%s]%s[/url]. We were expecting you ( ͡° ͜ʖ ͡°)', $profile_url, $user->username),
        ];
        $selected = mt_rand(0, count($welcomeArray) - 1);
        $this->chat->systemMessage(
            $welcomeArray[$selected]
        );
        // Send Welcome PM
        $pm = new PrivateMessage();
        $pm->sender_id = 1;
        $pm->receiver_id = $user->id;
        $pm->subject = config('welcomepm.subject');
        $pm->message = config('welcomepm.message');
        $pm->save();

        return redirect()->route('login')
            ->withSuccess(trans('auth.register-thanks'));
    }
}
