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

use App\Traits\TwoStep;
use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Session\SessionManager;

final class TwoStepAuth
{
    use TwoStep;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private Repository $configRepository;
    /**
     * @var \Illuminate\Session\SessionManager
     */
    private SessionManager $sessionManager;
    /**
     * @var \Illuminate\Routing\Redirector
     */
    private Redirector $redirector;

    public function __construct(Repository $configRepository, SessionManager $sessionManager, Redirector $redirector)
    {
        $this->configRepository = $configRepository;
        $this->sessionManager = $sessionManager;
        $this->redirector = $redirector;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request  $request
     * @param Closure  $next
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $uri = $request->path();
        $nextUri = $this->configRepository->get('app.url').'/'.$uri;
        $user = $request->user();

        switch ($uri) {
            case 'twostep/needed':
            case 'password/reset':
            case 'register':
            case 'logout':
            case 'login':
                break;

            default:
                $this->sessionManager->put(['nextUri' => $nextUri]);

                if ($this->configRepository->get('auth.TwoStepEnabled') && $user->twostep == 1) {
                    if (! $this->twoStepVerification()) {
                        return $this->redirector->route('verificationNeeded');
                    }
                }

                break;
        }

        return $response;
    }
}
