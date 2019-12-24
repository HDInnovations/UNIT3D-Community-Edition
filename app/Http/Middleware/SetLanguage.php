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

use App\Models\Language;
use Carbon\Carbon;
use Closure;
use Date;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\App;

final class SetLanguage
{
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private Repository $configRepository;
    /**
     * @var \Illuminate\Foundation\Application
     */
    private Application $application;
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    private Guard $guard;
    /**
     * @var \Illuminate\Session\SessionManager
     */
    private SessionManager $sessionManager;

    public function __construct(Repository $configRepository, Application $application, Guard $guard, SessionManager $sessionManager)
    {
        $this->configRepository = $configRepository;
        $this->application = $application;
        $this->guard = $guard;
        $this->sessionManager = $sessionManager;
    }

    /**
     * This function checks if language to set is an allowed lang of config.
     *
     * @param string $locale
     **/
    private function setLocale(string $locale): void
    {
        // Check if is allowed and set default locale if not
        if (! Language::allowed($locale)) {
            $locale = $this->configRepository->get('app.locale');
        }

        // Set app language
        $this->application->setLocale($locale);

        // Set carbon language
        if ($this->configRepository->get('language.carbon')) {
            // Carbon uses only language code
            if ($this->configRepository->get('language.mode.code') == 'long') {
                $locale = explode('-', $locale)[0];
            }

            Carbon::setLocale($locale);
        }

        // Set date language
        if ($this->configRepository->get('language.date')) {
            // Date uses only language code
            if ($this->configRepository->get('language.mode.code') == 'long') {
                $locale = explode('-', $locale)[0];
            }

            Date::setLocale($locale);
        }
    }

    public function setDefaultLocale(): void
    {
        $this->setLocale($this->configRepository->get('app.locale'));
    }

    public function setUserLocale(): void
    {
        $user = $this->guard->user();

        if ($user->locale) {
            $this->setLocale($user->locale);
        } else {
            $this->setDefaultLocale();
        }
    }

    public function setSystemLocale($request): void
    {
        if ($request->session()->has('locale')) {
            $this->setLocale($this->sessionManager->get('locale'));
        } else {
            $this->setDefaultLocale();
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('lang')) {
            $this->setLocale($request->get('lang'));
        } elseif ($this->guard->check()) {
            $this->setUserLocale();
        } else {
            $this->setSystemLocale($request);
        }

        return $next($request);
    }
}
