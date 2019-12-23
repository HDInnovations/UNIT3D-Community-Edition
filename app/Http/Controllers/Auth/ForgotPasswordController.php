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

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

final class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private $configRepository;

    public function __construct(Repository $configRepository)
    {
        $this->middleware('guest');
        $this->configRepository = $configRepository;
    }

    protected function validateEmail(Request $request): void
    {
        if ($this->configRepository->get('captcha.enabled') == false) {
            $request->validate(['email' => 'required|email']);
        } else {
            $request->validate([
                'email' => 'required|email',
                'captcha' => 'hiddencaptcha',
            ]);
        }
    }
}
