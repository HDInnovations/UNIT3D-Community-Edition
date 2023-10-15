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
use Illuminate\Support\Facades\Redis;

class UpdateLastAction
{
    /**
     * Handle an incoming request.
     */
    public function handle(\Illuminate\Http\Request $request, Closure $next): mixed
    {
        $user = $request->user();

        if (null === $user) {
            return $next($request);
        }

        Redis::command('LPUSH', [config('cache.prefix').':user-last-actions:batch', $user->id]);

        return $next($request);
    }
}
