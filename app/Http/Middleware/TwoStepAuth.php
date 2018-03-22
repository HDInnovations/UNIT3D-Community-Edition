<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use App\Traits\TwoStep;

class TwoStepAuth
{
    use TwoStep;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $response
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response   = $next($request);
        $uri        = $request->path();
        $nextUri    = config('app.url') . '/' .  $uri;
        $user = auth()->user();

        switch ($uri) {
            case 'verification/needed':
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
