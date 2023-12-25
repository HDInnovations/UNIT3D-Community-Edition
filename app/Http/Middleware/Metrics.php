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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class Metrics
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Use an exponential moving average algorithm to calculate the current
        // requests per second the site is receiving
        Redis::connection('cache')->command('eval', [
            <<<'LUA'
                local updated_at = redis.call("SET", KEYS[1], KEYS[3], "GET") or KEYS[3]
                local old_rate = redis.call("get", KEYS[2]) or 0
                local elapsed_secs = KEYS[3] - updated_at

                if elapsed_secs >= 0 then
                    local interval_secs = 10
                    local new_rate = 1 + old_rate * math.exp(-1 * elapsed_secs / interval_secs)
                    redis.call("SET", KEYS[2], new_rate)
                end
            LUA,
            [
                config('cache.prefix').':metrics:requests-per-second-updated-at',
                config('cache.prefix').':metrics:requests-per-second-rate',
                microtime(true),
            ],
            3,
        ]);

        return $next($request);
    }
}
