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

use Illuminate\Contracts\Config\Repository;
use Illuminate\Routing\Redirector;
use Illuminate\Translation\Translator;
use Illuminate\Contracts\Auth\Guard;
use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

final class LoginController extends Controller
{
    use AuthenticatesUsers;

    // Upon Successful Login
    /**
     * @var string
     */
    protected string $redirectTo = '/';

    // Max Attempts Until Lockout
    /**
     * @var int
     */
    public int $maxAttempts = 3;

    // Minutes Lockout
    /**
     * @var int
     */
    public int $decayMinutes = 60;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $configRepository;
    /**
     * @var \Illuminate\Routing\Redirector
     */
    private $redirector;
    /**
     * @var \Illuminate\Translation\Translator
     */
    private $translator;
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    private $guard;

    /**
     * LoginController Constructor.
     */
    public function __construct(Repository $configRepository, Redirector $redirector, Translator $translator, Guard $guard)
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->configRepository = $configRepository;
        $this->redirector = $redirector;
        $this->translator = $translator;
        $this->guard = $guard;
    }

    public function username(): string
    {
        return 'username';
    }

    /**
     * Validate The User Login Request.
     *
     * @param \Illuminate\Http\Request $request
     */
    protected function validateLogin(Request $request): void
    {
        if ($this->configRepository->get('captcha.enabled') == true) {
            $this->validate($request, [
                $this->username()      => 'required|string',
                'password'             => 'required|string',
                'captcha' => 'hiddencaptcha',
            ]);
        } else {
            $this->validate($request, [
                $this->username() => 'required|string',
                'password' => 'required|string',
            ]);
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $banned_group = cache()->rememberForever('banned_group', fn() => Group::where('slug', '=', 'banned')->pluck('id'));
        $validating_group = cache()->rememberForever('validating_group', fn() => Group::where('slug', '=', 'validating')->pluck('id'));
        $disabled_group = cache()->rememberForever('disabled_group', fn() => Group::where('slug', '=', 'disabled')->pluck('id'));
        $member_group = cache()->rememberForever('member_group', fn() => Group::where('slug', '=', 'user')->pluck('id'));

        if ($user->active == 0 || $user->group_id == $validating_group[0]) {
            $this->guard()->logout();
            $request->session()->invalidate();

            return $this->redirector->route('login')
                ->withErrors($this->translator->trans('auth.not-activated'));
        }

        if ($user->group_id == $banned_group[0]) {
            $this->guard()->logout();
            $request->session()->invalidate();

            return $this->redirector->route('login')
                ->withErrors($this->translator->trans('auth.banned'));
        }

        if ($user->group_id == $disabled_group[0]) {
            $user->group_id = $member_group[0];
            $user->can_upload = 1;
            $user->can_download = 1;
            $user->can_comment = 1;
            $user->can_invite = 1;
            $user->can_request = 1;
            $user->can_chat = 1;
            $user->disabled_at = null;
            $user->save();

            return $this->redirector->route('home.index')
                ->withSuccess($this->translator->trans('auth.welcome-restore'));
        }

        if ($this->guard->viaRemember() && $user->group_id == $disabled_group[0]) {
            $user->group_id = $member_group[0];
            $user->can_upload = 1;
            $user->can_download = 1;
            $user->can_comment = 1;
            $user->can_invite = 1;
            $user->can_request = 1;
            $user->can_chat = 1;
            $user->disabled_at = null;
            $user->save();

            return $this->redirector->route('home.index')
                ->withSuccess($this->translator->trans('auth.welcome-restore'));
        }

        if ($user->read_rules == 0) {
            return $this->redirector->to($this->configRepository->get('other.rules_url'))
                ->withWarning($this->translator->trans('auth.require-rules'));
        }

        return $this->redirector->route('home.index')
            ->withSuccess($this->translator->trans('auth.welcome'));
    }
}
