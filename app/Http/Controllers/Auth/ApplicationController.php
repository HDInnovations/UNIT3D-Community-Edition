<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationImageProof;
use App\Models\ApplicationUrlProof;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;
use Illuminate\Translation\Translator;

final class ApplicationController extends Controller
{
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private $viewFactory;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $configRepository;
    /**
     * @var \Illuminate\Routing\Redirector
     */
    private $redirector;
    /**
     * @var \Illuminate\Translation\Translator
     */
    private $translator;

    public function __construct(Factory $viewFactory, Repository $configRepository, Redirector $redirector, Translator $translator)
    {
        $this->viewFactory = $viewFactory;
        $this->configRepository = $configRepository;
        $this->redirector = $redirector;
        $this->translator = $translator;
    }

    /**
     * Application Add Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(): Factory
    {
        return $this->viewFactory->make('auth.application.create');
    }

    /**
     * Store A New Application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function store(Request $request)
    {
        $application = new Application();
        $application->type = $request->input('type');
        $application->email = $request->input('email');
        $application->referrer = $request->input('referrer');

        if ($this->configRepository->get('email-white-blacklist.enabled') === 'allow') {
            if ($this->configRepository->get('captcha.enabled') == false) {
                $v = validator($request->all(), [
                    'type' => 'required',
                    'email' => 'required|email|unique:invites|unique:users|unique:applications|email_list:allow',
                    'referrer' => 'required',
                    'images.*' => 'filled',
                    'images'   => 'min:2',
                    'links.*' => 'filled',
                    'links'   => 'min:2',
                ]);
            } else {
                $v = validator($request->all(), [
                    'type' => 'required',
                    'email' => 'required|email|unique:invites|unique:users|unique:applications|email_list:allow',
                    'referrer' => 'required',
                    'images.*' => 'filled',
                    'images'   => 'min:2',
                    'links.*' => 'filled',
                    'links'   => 'min:2',
                    'captcha' => 'hiddencaptcha',
                ]);
            }
        } elseif ($this->configRepository->get('email-white-blacklist.enabled') === 'block') {
            if ($this->configRepository->get('captcha.enabled') == false) {
                $v = validator($request->all(), [
                    'type' => 'required',
                    'email' => 'required|email|unique:invites|unique:users|unique:applications|email_list:block',
                    'referrer' => 'required',
                    'images.*' => 'filled',
                    'images'   => 'min:2',
                    'links.*' => 'filled',
                    'links'   => 'min:2',
                ]);
            } else {
                $v = validator($request->all(), [
                    'type' => 'required',
                    'email' => 'required|email|unique:invites|unique:users|unique:applications|email_list:block',
                    'referrer' => 'required',
                    'images.*' => 'filled',
                    'images'   => 'min:2',
                    'links.*' => 'filled',
                    'links'   => 'min:2',
                    'captcha' => 'hiddencaptcha',
                ]);
            }
        } elseif ($this->configRepository->get('captcha.enabled') == false) {
            $v = validator($request->all(), [
                'type' => 'required',
                'email' => 'required|email|unique:invites|unique:users|unique:applications',
                'referrer' => 'required',
                'images.*' => 'filled',
                'images'   => 'min:2',
                'links.*' => 'filled',
                'links'   => 'min:2',
            ]);
        } else {
            $v = validator($request->all(), [
                'type' => 'required',
                'email' => 'required|email|unique:invites|unique:users|unique:applications',
                'referrer' => 'required',
                'images.*' => 'filled',
                'images'   => 'min:2',
                'links.*' => 'filled',
                'links'   => 'min:2',
                'captcha' => 'hiddencaptcha',
            ]);
        }

        if ($v->fails()) {
            return $this->redirector->route('application.create')
                ->withErrors($v->errors());
        } else {
            $application->save();

            // Map And Save IMG Proofs
            $imgs = (new Collection($request->input('images')))->map(fn ($value) => new ApplicationImageProof(['image' => $value]));
            $application->imageProofs()->saveMany($imgs);

            // Map And Save URL Proofs
            $urls = (new Collection($request->input('links')))->map(fn ($value) => new ApplicationUrlProof(['url' => $value]));
            $application->urlProofs()->saveMany($urls);

            return $this->redirector->route('login')
                ->withSuccess($this->translator->trans('application-submitted'));
        }
    }
}
