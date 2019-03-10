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
use Brian2694\Toastr\Toastr;

class CheckIfActive
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * CheckIfActive Middleware Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

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
        $user = auth()->user();
        $validatingGroup = Group::where('slug', '=', 'validating')->select('id')->first();

        if ($user && $user->group_id == $validatingGroup->id || $user->active == 0) {
            auth()->logout();
            $request->session()->flush();

            return redirect('login')
                ->with($this->toastr->warning('This account has not been activated and is still in validating group, Please check your email for activation link. If you did not receive the activation code, please click "forgot password" and complete the steps.', 'Whoops!', ['options']));
        }

        return $next($request);
    }
}
