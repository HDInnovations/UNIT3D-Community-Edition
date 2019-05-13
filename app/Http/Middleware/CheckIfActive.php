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

namespace App\Http\Middleware;

use Closure;
use App\Models\Group;

class CheckIfActive
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
        $validatingGroup = Group::select(['id'])->where('slug', '=', 'validating')->first();

        if ($user && $user->group_id == $validatingGroup->id || $user->active == 0) {
            auth()->logout();
            $request->session()->flush();

            return redirect()->to('login')
                ->withWarning('This account has not been activated and is still in validating group, Please check your email for activation link. If you did not receive the activation code, please click "forgot password" and complete the steps.');
        }

        return $next($request);
    }
}
