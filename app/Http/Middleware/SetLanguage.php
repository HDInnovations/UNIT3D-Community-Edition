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

use App;
use Closure;
use App\Language;
use Carbon\Carbon;

class SetLanguage
{
    /**
     * This function checks if language to set is an allowed lang of config.
     *
     * @param string $locale
     **/
    private function setLocale($locale)
    {
        // Check if is allowed and set default locale if not
        if (!Language::allowed($locale)) {
            $locale = config('app.locale');
        }

        // Set app language
        App::setLocale($locale);

        // Set carbon language
        if (config('language.carbon')) {
            // Carbon uses only language code
            if (config('language.mode.code') == 'long') {
                $locale = explode('-', $locale)[0];
            }

            Carbon::setLocale($locale);
        }

        // Set date language
        if (config('language.date')) {
            // Date uses only language code
            if (config('language.mode.code') == 'long') {
                $locale = explode('-', $locale)[0];
            }

            \Date::setLocale($locale);
        }
    }

    public function setDefaultLocale()
    {
        $this->setLocale(config('app.locale'));
    }

    public function setUserLocale()
    {
        $user = auth()->user();

        if ($user->locale) {
            $this->setLocale($user->locale);
        } else {
            $this->setDefaultLocale();
        }
    }

    public function setSystemLocale($request)
    {
        if ($request->session()->has('locale')) {
            $this->setLocale(session('locale'));
        } else {
            $this->setDefaultLocale();
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $this->setUserLocale();
        } else {
            $this->setSystemLocale($request);
        }

        return $next($request);
    }
}
