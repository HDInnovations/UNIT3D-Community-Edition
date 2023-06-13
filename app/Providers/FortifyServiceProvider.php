<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\Group;
use App\Models\User;
use App\Models\UserActivation;
use App\Services\Unit3dAnnounce;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterViewResponse;
use Laravel\Fortify\Contracts\VerifyEmailResponse;
use Laravel\Fortify\Fortify;

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

                // Redirect to home page

                return redirect()->intended()
                    ->withSuccess(trans('auth.welcome'));

            }
        });

        // Handle redirects before the registration form is shown
        $this->app->instance(RegisterViewResponse::class, new class () implements RegisterViewResponse {
            public function toResponse($request): \Illuminate\Http\RedirectResponse|\Illuminate\View\View
            {
                // Make sure open reg is off, invite code is not present and application signups enabled
                if (! $request->has('code') && config('other.invite-only') && config('other.application_signups')) {
                    return to_route('application.create')
                        ->withInfo(trans('auth.allow-invite-appl'));
                }

                // Make sure open reg is off and invite code is not present
                if (! $request->has('code') && config('other.invite-only')) {
                    return to_route('login')
                        ->withWarning(trans('auth.allow-invite'));
                }

                return view('auth.register', ['code' => $request->query('code')]);
            }
        });

        $this->app->instance(VerifyEmailResponse::class, new class () implements VerifyEmailResponse {
            public function toResponse($request): \Illuminate\Http\RedirectResponse|\Illuminate\View\View
            {
                $bannedGroup = cache()->rememberForever('banned_group', fn () => Group::query()->where('slug', '=', 'banned')->pluck('id'));
                $memberGroup = cache()->rememberForever('member_group', fn () => Group::query()->where('slug', '=', 'user')->pluck('id'));

                $activation = UserActivation::with('user')->where('token', '=', $request->token)->firstOrFail();
                if ($activation->user->id && $activation->user->group->id != $bannedGroup[0]) {
                    $activation->user->active = 1;
                    $activation->user->can_upload = 1;
                    $activation->user->can_download = 1;
                    $activation->user->can_request = 1;
                    $activation->user->can_comment = 1;
                    $activation->user->can_invite = 1;
                    $activation->user->group_id = $memberGroup[0];
                    $activation->user->save();

                    $activation->delete();

                    Unit3dAnnounce::addUser($activation->user);

                    return to_route('login')
                        ->withSuccess(trans('auth.activation-success'));
                }

                return to_route('login')
                    ->withErrors(trans('auth.activation-error'));
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
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

        Fortify::authenticateUsing(function (Request $request): User|\Illuminate\Database\Eloquent\ModelNotFoundException {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
                'captcha'  => Rule::when(config('captcha.enabled'), 'hiddencaptcha')
            ]);

            $user = User::query()->where('username', $request->username)->sole();

            // Check if user is activated

            $validatingGroup = cache()->rememberForever('validating_group', fn () => Group::query()->where('slug', '=', 'validating')->pluck('id'));

            if ($user->active == 0 || $user->group_id == $validatingGroup[0]) {
                $request->session()->invalidate();

                throw ValidationException::withMessages([
                    Fortify::username() => trans('auth.not-activated'),
                ]);
            }

            // Check if user is banned

            $bannedGroup = cache()->rememberForever('banned_group', fn () => Group::query()->where('slug', '=', 'banned')->pluck('id'));

            if ($user->group_id == $bannedGroup[0]) {
                $request->session()->invalidate();

                throw ValidationException::withMessages([
                    Fortify::username() => trans('auth.banned'),
                ]);
            }

            return $user;
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email.$request->ip());
        });

        RateLimiter::for('two-factor', fn (Request $request) => Limit::perMinute(5)->by($request->session()->get('login.id')));
    }
}
