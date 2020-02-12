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

use App\Models\Option;
use App\Models\Voter;
use Closure;

class CheckIfAlreadyVoted
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
        //If user hasn't selected any options, carry on to form validation / rejection
        if (!$request->input('option.0')) {
            return $next($request);
        }

        $poll = Option::findOrFail($request->input('option.0'))->poll;

        if (Voter::where('ip_address', '=', $request->ip())->where('poll_id', '=', $poll->id)->exists()) {
            return redirect('poll/'.$poll->slug.'/result')
                ->withErrors('There is already a vote on this poll from your IP. Your vote has not been counted.');
        }

        return $next($request);
    }
}
