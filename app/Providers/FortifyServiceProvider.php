<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\FailedLoginAttempt;
use App\Models\Group;
use App\Models\User;
use App\Notifications\FailedLogin;
use App\Services\Unit3dAnnounce;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterViewResponse;
use Laravel\Fortify\Contracts\VerifyEmailResponse;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Responses\FailedPasswordResetLinkRequestResponse;
use Laravel\Fortify\Http\Responses\SuccessfulPasswordResetLinkRequestResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Handle redirects after successful login
        $this->app->instance(LoginResponse::class, new class () implements LoginResponse {
            public function toResponse($request): \Illuminate\Http\RedirectResponse
            {
                $user = $request->user();

                // Check if user is disabled
                $disabledGroup = cache()->rememberForever('disabled_group', fn () => Group::query()->where('slug', '=', 'disabled')->pluck('id'));
                $memberGroup = cache()->rememberForever('member_group', fn () => Group::query()->where('slug', '=', 'user')->pluck('id'));

                if ($user->group_id == $disabledGroup[0]) {
                    $user->group_id = $memberGroup[0];
                    $user->can_upload = 1;
                    $user->can_download = 1;
                    $user->can_comment = 1;
                    $user->can_invite = 1;
                    $user->can_request = 1;
                    $user->can_chat = 1;
                    $user->disabled_at = null;
                    $user->save();

                    return to_route('home.index')
                        ->withSuccess('auth.welcome-restore');
                }

                // Check if user has read the rules
                if ($request->user()->read_rules == 0) {
                    return redirect()->to(config('other.rules_url'))
                        ->withWarning(trans('auth.require-rules'));
                }

                // Fix for issue described in https://github.com/laravel/framework/pull/46133
                if ($rootUrlOverride = config('unit3d.root_url_override')) {
                    $url = redirect()->getIntendedUrl();

                    return redirect(
                        rtrim(
                            rtrim($rootUrlOverride, '/')
                            .parse_url($url, PHP_URL_PATH)
                            .'?'.parse_url($url, PHP_URL_QUERY),
                        )
                    );
                }

                return redirect()->intended()
                    ->withSuccess(trans('auth.welcome'));
            }
        });

        // Handle redirects before the registration form is shown
        $this->app->instance(RegisterViewResponse::class, new class () implements RegisterViewResponse {
            public function toResponse($request): \Illuminate\Http\RedirectResponse|\Illuminate\View\View
            {
                return view('auth.register', ['code' => $request->query('code')]);
            }
        });

        $this->app->instance(VerifyEmailResponse::class, new class () implements VerifyEmailResponse {
            public function toResponse($request): \Illuminate\Http\RedirectResponse|\Illuminate\View\View
            {
                $bannedGroup = cache()->rememberForever('banned_group', fn () => Group::query()->where('slug', '=', 'banned')->pluck('id'));
                $memberGroup = cache()->rememberForever('member_group', fn () => Group::query()->where('slug', '=', 'user')->pluck('id'));

                $user = $request->user();

                if ($user->id && $user->group->id != $bannedGroup[0]) {
                    $user->active = 1;
                    $user->can_upload = 1;
                    $user->can_download = 1;
                    $user->can_request = 1;
                    $user->can_comment = 1;
                    $user->can_invite = 1;
                    $user->group_id = $memberGroup[0];
                    $user->save();

                    Unit3dAnnounce::addUser($user);

                    return to_route('login')
                        ->withSuccess(trans('auth.activation-success'));
                }

                return to_route('login')
                    ->withErrors(trans('auth.activation-error'));
            }
        });

        $this->app->extend(FailedPasswordResetLinkRequestResponse::class, fn () => new SuccessfulPasswordResetLinkRequestResponse(Password::RESET_LINK_SENT));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));
        RateLimiter::for('two-factor', fn (Request $request) => Limit::perMinute(5)->by($request->session()->get('login.id')));
        RateLimiter::for('fortify-login-get', fn (Request $request) => Limit::perMinute(3)->by($request->ip()));
        RateLimiter::for('fortify-register-get', fn (Request $request) => Limit::perMinute(3)->by($request->ip()));
        RateLimiter::for('fortify-register-post', fn (Request $request) => Limit::perMinute(3)->by($request->ip()));
        RateLimiter::for('fortify-forgot-password-get', fn (Request $request) => Limit::perMinute(3)->by($request->ip()));
        RateLimiter::for('fortify-forgot-password-post', fn (Request $request) => Limit::perMinute(3)->by($request->ip()));
        RateLimiter::for('fortify-reset-password-get', fn (Request $request) => Limit::perMinute(3)->by($request->ip()));
        RateLimiter::for('fortify-reset-password-post', fn (Request $request) => Limit::perMinute(3)->by($request->ip()));

        Fortify::loginView(fn () => view('auth.login'));
        Fortify::requestPasswordResetLinkView(fn () => view('auth.passwords.email'));
        Fortify::resetPasswordView(fn (Request $request) => view('auth.passwords.reset', ['request' => $request]));
        Fortify::confirmPasswordView(fn () => view('auth.confirm-password'));
        Fortify::twoFactorChallengeView(fn () => view('auth.two-factor-challenge'));
        Fortify::verifyEmailView(fn () => view('auth.verify-email'));

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::authenticateUsing(function (Request $request): \Illuminate\Database\Eloquent\Model {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
                'captcha'  => Rule::when(config('captcha.enabled'), 'hiddencaptcha')
            ]);

            $user = User::query()->where('username', $request->username)->first();

            if ($user === null) {
                throw ValidationException::withMessages([
                    Fortify::username() => __('auth.failed'),
                ]);
            }

            $password = Hash::check($request->password, $user->password);

            if ($password === false) {
                FailedLoginAttempt::create([
                    'user_id'    => $user->id,
                    'username'   => $request->username,
                    'ip_address' => $request->ip(),
                ]);

                $user->notify(new FailedLogin(
                    $request->ip()
                ));

                throw ValidationException::withMessages([
                    Fortify::username() => __('auth.failed'),
                ]);
            }

            if ($password === true) {
                // Check if user is activated
                $validatingGroup = cache()->rememberForever('validating_group', fn () => Group::query()->where('slug', '=', 'validating')->pluck('id'));

                if ($user->active == 0 || $user->group_id === $validatingGroup[0]) {
                    $request->session()->invalidate();

                    throw ValidationException::withMessages([
                        Fortify::username() => __('auth.not-activated'),
                    ]);
                }

                // Check if user is banned
                $bannedGroup = cache()->rememberForever('banned_group', fn () => Group::query()->where('slug', '=', 'banned')->pluck('id'));

                if ($user->group_id === $bannedGroup[0]) {
                    $request->session()->invalidate();

                    throw ValidationException::withMessages([
                        Fortify::username() => __('auth.banned'),
                    ]);
                }

                return $user;
            }
        });
    }
}
