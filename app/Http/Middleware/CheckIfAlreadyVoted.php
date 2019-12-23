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

use App\Models\Option;
use App\Models\Voter;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

final class CheckIfAlreadyVoted
{
    /**
     * @var \Illuminate\Routing\Redirector
     */
    private $redirector;

    public function __construct(Redirector $redirector)
    {
        $this->redirector = $redirector;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //If user hasn't selected any options, carry on to form validation / rejection
        if (! $request->input('option.0')) {
            return $next($request);
        }

        $poll = Option::findOrFail($request->input('option.0'))->poll;

        //if we already have this user's IP stored and linked to the poll they are trying to vote on
        if ($poll->ip_checking == 1) {
            if (Voter::where('ip_address', '=', $request->ip())->where('poll_id', '=', $poll->id)->exists()) {
                return $this->redirector->back('poll/'.$poll->slug.'/result')
                    ->withErrors('There is already a vote on this poll from your IP. Your vote has not been counted.');
            }
        }

        return $next($request);
    }
}
