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

use App\Models\Group;
use Closure;

class CheckIfBanned
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $user = $request->user();
        $banned_group = cache()->rememberForever('banned_group', function () {
            return Group::where('slug', '=', 'banned')->pluck('id');
        });

        if ($user && $user->group_id == $banned_group[0]) {
            auth()->logout();
            $request->session()->flush();

            return redirect()->route('login')
                ->withErrors('This account is Banned!');
        }

        return $next($request);
    }
}
