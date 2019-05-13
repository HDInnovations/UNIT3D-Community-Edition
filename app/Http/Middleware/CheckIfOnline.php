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
use Carbon\Carbon;
use App\Models\Group;

class CheckIfOnline
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        $bannedGroup = Group::select(['id'])->where('slug', '=', 'banned')->first();

        if ($request->user() && $user->group_id != $bannedGroup->id) {
            $expiresAt = Carbon::now()->addMinutes(60);
            cache()->put('user-is-online-'.$user->id, true, $expiresAt);
        }

        return $next($request);
    }
}
