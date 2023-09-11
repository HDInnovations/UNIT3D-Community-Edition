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

use App\Models\BlockedIp;
use Closure;
use Illuminate\Http\Request;

class BlockIpAddress
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
    {
        // Check Passkey Against Users Table
        $ipAddresses = cache()->remember(config('cache.prefix').'blocked-ips', 8 * 3600, fn () => BlockedIp::query()->pluck('ip_address')->toArray());

        if (\in_array($request->ip(), $ipAddresses)) {
            abort(403, 'You access to '.config('app.name').' has been restricted.');
        }

        return $next($request);
    }
}
