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

namespace App\Http\Middleware;

use App\Models\Group;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

final class CheckIfBanned
{
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    private $guard;
    /**
     * @var \Illuminate\Routing\Redirector
     */
    private $redirector;

    public function __construct(Guard $guard, Redirector $redirector)
    {
        $this->guard = $guard;
        $this->redirector = $redirector;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next, ?string $guard = null)
    {
        $user = $request->user();
        $banned_group = cache()->rememberForever('banned_group', fn () => Group::where('slug', '=', 'banned')->pluck('id'));

        if ($user && $user->group_id == $banned_group[0]) {
            $this->guard->logout();
            $request->session()->flush();

            return $this->redirector->route('login')
                ->withErrors('This account is Banned!');
        }

        return $next($request);
    }
}
