<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
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
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Illuminate\Translation\Translator;

final class RegisterController extends Controller
{
    /**
     * @var ChatRepository
     */
    private ChatRepository $chat;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private Repository $configRepository;
    /**
     * @var \Illuminate\Routing\Redirector
     */
    private Redirector $redirector;
    /**
     * @var \Illuminate\Translation\Translator
     */
    private Translator $translator;
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private Factory $viewFactory;
    /**
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    private Hasher $hasher;

    /**
     * RegisterController Constructor.
     *
     * @param  ChatRepository  $chat
     * @param  \Illuminate\Contracts\Config\Repository  $configRepository
     * @param  \Illuminate\Routing\Redirector  $redirector
     * @param  \Illuminate\Translation\Translator  $translator
     * @param  \Illuminate\Contracts\View\Factory  $viewFactory
     * @param  \Illuminate\Contracts\Hashing\Hasher  $hasher
     */
    public function __construct(ChatRepository $chat, Repository $configRepository, Redirector $redirector, Translator $translator, Factory $viewFactory, Hasher $hasher)
    {
        $this->chat = $chat;
        $this->configRepository = $configRepository;
        $this->redirector = $redirector;
        $this->translator = $translator;
        $this->viewFactory = $viewFactory;
        $this->hasher = $hasher;
    }

    /**
     * Registration Form.
     *
     * @param $code
     * @return mixed|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registrationForm($code = null)
    {
        // Make sure open reg is off, invite code is not present and application signups enabled
        if ($code === 'null' && $this->configRepository->get('other.invite-only') == 1 && $this->configRepository->get('other.application_signups') == true) {
            return $this->redirector->route('application.create')
                ->withInfo($this->translator->trans('auth.allow-invite-appl'));
        }

        // Make sure open reg is off and invite code is not present
        if ($code === 'null' && $this->configRepository->get('other.invite-only') == 1) {
            return $this->redirector->route('login')
                ->withWarning($this->translator->trans('auth.allow-invite'));
        }

        return $this->viewFactory->make('auth.register', ['code' => $code]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  null  $code
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function register(Request $request, $code = null)
    {
        // Make sure open reg is off and invite code exist and has not been used already
        $key = Invite::where('code', '=', $code)->first();
        if ($this->configRepository->get('other.invite-only') == 1 && (! $key || $key->accepted_by !== null)) {
            return $this->redirector->route('registrationForm', ['code' => $code])
                ->withErrors($this->translator->trans('auth.invalid-key'));
        }

        $validating_group = cache()->rememberForever('validating_group', fn () => Group::where('slug', '=', 'validating')->pluck('id'));

        $user = new User();
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = $this->hasher->make($request->input('password'));
        $user->passkey = md5(uniqid().time().microtime());
        $user->rsskey = md5(uniqid().time().microtime().$user->password);
        $user->uploaded = $this->configRepository->get('other.default_upload');
        $user->downloaded = $this->configRepository->get('other.default_download');
        $user->style = $this->configRepository->get('other.default_style', 0);
        $user->locale = $this->configRepository->get('app.locale');
        $user->group_id = $validating_group[0];

        if ($this->configRepository->get('email-white-blacklist.enabled') === 'allow' && $this->configRepository->get('captcha.enabled') == true) {
            $v = validator($request->all(), [
                'username'             => 'required|alpha_dash|min:3|max:20|unique:users',
                'email'                => 'required|email|max:255|unique:users|email_list:allow', // Whitelist
                'password'             => 'required|min:8',
                'captcha' => 'hiddencaptcha',
            ]);
        } elseif ($this->configRepository->get('email-white-blacklist.enabled') === 'allow') {
            $v = validator($request->all(), [
                'username' => 'required|alpha_dash|min:3|max:20|unique:users',
                'email'    => 'required|email|max:255|unique:users|email_list:allow', // Whitelist
                'password' => 'required|min:8',
            ]);
        } elseif ($this->configRepository->get('email-white-blacklist.enabled') === 'block' && $this->configRepository->get('captcha.enabled') == true) {
            $v = validator($request->all(), [
                'username'             => 'required|alpha_dash|min:3|max:20|unique:users',
                'email'                => 'required|email|max:255|unique:users|email_list:block', // Blacklist
                'password'             => 'required|min:8',
                'captcha' => 'hiddencaptcha',
            ]);
        } elseif ($this->configRepository->get('email-white-blacklist.enabled') === 'block') {
            $v = validator($request->all(), [
                'username' => 'required|alpha_dash|min:3|max:20|unique:users',
                'email'    => 'required|email|max:255|unique:users|email_list:block', // Blacklist
                'password' => 'required|min:8',
            ]);
        } elseif ($this->configRepository->get('captcha.enabled') == true) {
            $v = validator($request->all(), [
                'username'             => 'required|alpha_dash|min:3|max:20|unique:users',
                'email'                => 'required|email|max:255|unique:users',
                'password'             => 'required|min:8',
                'captcha' => 'hiddencaptcha',
            ]);
        } else {
            $v = validator($request->all(), [
                'username' => 'required|alpha_dash|min:3|max:20|unique:users', //Default
                'email'    => 'required|email|max:255|unique:users',
                'password' => 'required|min:8',
            ]);
        }

        if ($v->fails()) {
            return $this->redirector->route('registrationForm', ['code' => $code])
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
            $token = hash_hmac('sha256', $user->username.$user->email.Str::random(16), $this->configRepository->get('app.key'));
            $activation = new UserActivation();
            $activation->user_id = $user->id;
            $activation->token = $token;
            $activation->save();
            $this->dispatch(new SendActivationMail($user, $token));

            // Select A Random Welcome Message
            $profile_url = hrefProfile($user);

            $welcomeArray = [
                sprintf('[url=%s]%s[/url], Welcome to ', $profile_url, $user->username).$this->configRepository->get('other.title').'! Hope you enjoy the community :rocket:',
                sprintf('[url=%s]%s[/url], We\'ve been expecting you :space_invader:', $profile_url, $user->username),
                sprintf('[url=%s]%s[/url] has arrived. Party\'s over. :cry:', $profile_url, $user->username),
                sprintf('It\'s a bird! It\'s a plane! Nevermind, it\'s just [url=%s]%s[/url].', $profile_url, $user->username),
                sprintf('Ready player [url=%s]%s[/url].', $profile_url, $user->username),
                sprintf('A wild [url=%s]%s[/url] appeared.', $profile_url, $user->username),
                'Welcome to '.$this->configRepository->get('other.title').sprintf(' [url=%s]%s[/url]. We were expecting you ( ͡° ͜ʖ ͡°)', $profile_url, $user->username),
            ];
            $selected = mt_rand(0, count($welcomeArray) - 1);

            $this->chat->systemMessage(
                sprintf('%s', $welcomeArray[$selected])
            );

            // Send Welcome PM
            $pm = new PrivateMessage();
            $pm->sender_id = 1;
            $pm->receiver_id = $user->id;
            $pm->subject = $this->configRepository->get('welcomepm.subject');
            $pm->message = $this->configRepository->get('welcomepm.message');
            $pm->save();

            return $this->redirector->route('login')
                ->withSuccess($this->translator->trans('auth.register-thanks'));
        }
    }
}
