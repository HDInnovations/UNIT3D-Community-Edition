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

namespace App\Http\Controllers\Auth;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Routing\Redirector;
use Illuminate\Translation\Translator;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\UsernameReminder;
use Illuminate\Http\Request;

final class ForgotUsernameController extends Controller
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
     * Forgot Username Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showForgotUsernameForm(): Factory
    {
        return $this->viewFactory->make('auth.username');
    }

    /**
     * Send Username Reminder.
     *
     * @param Request  $request
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function sendUsernameReminder(Request $request)
    {
        $email = $request->get('email');

        if ($this->configRepository->get('captcha.enabled') == false) {
            $v = validator($request->all(), [
                'email' => 'required',
            ]);
        } else {
            $v = validator($request->all(), [
                'email' => 'required',
                'captcha' => 'hiddencaptcha',
            ]);
        }

        if ($v->fails()) {
            return $this->redirector->route('username.request')
                ->withErrors($v->errors());
        } else {
            $user = User::where('email', '=', $email)->first();

            if (empty($user)) {
                return $this->redirector->route('username.request')
                    ->withErrors($this->translator->trans('email.no-email-found'));
            }

            //send username reminder notification
            $user->notify(new UsernameReminder());

            return $this->redirector->route('login')
                ->withSuccess($this->translator->trans('email.username-sent'));
        }
    }
}
