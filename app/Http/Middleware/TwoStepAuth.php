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
use App\Traits\TwoStep;
use Illuminate\Http\Request;

class TwoStepAuth
{
    use TwoStep;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $uri = $request->path();
        $nextUri = config('app.url').'/'.$uri;
        $user = auth()->user();

        switch ($uri) {
            case 'twostep/needed':
            case 'password/reset':
            case 'register':
            case 'logout':
            case 'login':
                break;

            default:
                session(['nextUri' => $nextUri]);

                if (config('auth.TwoStepEnabled') && $user->twostep == 1) {
                    if ($this->twoStepVerification($request) !== true) {
                        return redirect('twostep/needed');
                    }
                }

                break;
        }

        return $response;
    }
}
