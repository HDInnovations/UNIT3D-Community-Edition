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

namespace App\Http\Middleware;

use Closure;

class CheckIfBanned
{
    /**
     * Handle an incoming request.
     *
     * @throws \Exception
     */
    public function handle(\Illuminate\Http\Request $request, Closure $next, ?string $guard = null): mixed
    {
        $user = $request->user();

        if (! $user->hasPrivilegeTo('can_login')) {
            \auth()->logout();
            $request->session()->flush();
            if ($user->hasRole('banned')) {
                return \to_route('login')
                    ->withErrors('This account is Banned!');
            }

            return \to_route('login')
                    ->withErrors('Your account is not allowed to sign in. If you recently registered - Check your email for an Activation link.');
        }

        return $next($request);
    }
}
