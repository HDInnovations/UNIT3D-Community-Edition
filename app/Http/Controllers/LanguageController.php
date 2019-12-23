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

namespace App\Http\Controllers;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Routing\Redirector;
use App\Models\Language;
use Illuminate\Http\Request;

final class LanguageController extends Controller
{
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $configRepository;
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    private $guard;
    /**
     * @var \Illuminate\Contracts\Routing\UrlGenerator
     */
    private $urlGenerator;
    /**
     * @var \Illuminate\Routing\Redirector
     */
    private $redirector;
    public function __construct(Repository $configRepository, Guard $guard, UrlGenerator $urlGenerator, Redirector $redirector)
    {
        $this->configRepository = $configRepository;
        $this->guard = $guard;
        $this->urlGenerator = $urlGenerator;
        $this->redirector = $redirector;
    }
    /**
     * Set locale if it's allowed.
     *
     * @param string                   $locale
     * @param \Illuminate\Http\Request $request
     **/
    private function setLocale(string $locale, Request $request): void
    {
        // Check if is allowed and set default locale if not
        if (! Language::allowed($locale)) {
            $locale = $this->configRepository->get('app.locale');
        }

        if ($this->guard->check()) {
            $this->guard->user()->setAttribute('locale', $locale)->save();
        } else {
            $request->session()->put('locale', $locale);
        }
    }

    /**
     * Set locale and return home url.
     *
     * @param string                   $locale
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     **/
    public function home(string $locale, Request $request): string
    {
        $this->setLocale($locale, $request);

        $url = $this->configRepository->get('language.url') ? $this->urlGenerator->to('/'.$locale) : $this->urlGenerator->to('/');

        return $this->redirector->back($url)
            ->withSuccess('Language Changed!');
    }

    /**
     * Set locale and return back.
     *
     * @param string                   $locale
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     **/
    public function back(string $locale, Request $request): string
    {
        $this->setLocale($locale, $request);

        $session = $request->session();
        if ($this->configRepository->get('language.url')) {
            $previous_url = substr(str_replace(env('APP_URL'), '', $session->previousUrl()), 7);
            if (strlen($previous_url) === 3) {
                $previous_url = substr($previous_url, 3);
            } else {
                $previous_url = substr($previous_url, strrpos($previous_url, '/') + 1);
            }
            $url = rtrim(env('APP_URL'), '/').'/'.$locale.'/'.ltrim($previous_url, '/');
            $session->setPreviousUrl($url);
        }

        return $this->redirector->back($session->previousUrl())
            ->withSuccess('Language Changed!');
    }
}
