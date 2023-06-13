<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Middleware;

use Closure;

class CheckForWeakPassword
{
    /**
     * Handle an incoming request.
     */
    public function handle(\Illuminate\Http\Request $request, Closure $next): mixed
    {
        $response = $next($request);
        $uri = $request->path();
        $nextUri = config('app.url').'/'.$uri;
        $user = $request->user();

        switch ($uri) {
            case route('users.password.edit', ['user' => $user]):
            case route('users.passkey.update', ['user' => $user]):
                break;

            default:
                session(['nextUri' => $nextUri]);

                if ($user->weak_password) {
                    return to_route('users.password.edit');
                }

                break;
        }

        return $response;
    }
}
