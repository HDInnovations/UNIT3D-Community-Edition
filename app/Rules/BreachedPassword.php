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

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DivineOmega\PasswordExposed\PasswordExposedChecker;
use DivineOmega\LaravelPasswordExposedValidationRule\PasswordExposed;

/**
 * Class BreachedPassword.
 *
 * Implements the 'Passwords obtained from previous breach corpuses' recommendation
 * from NIST SP 800-63B section 5.1.1.2.
 */
class BreachedPassword extends PasswordExposed implements Rule
{
    /**
     * BreachedPassword constructor.
     *
     * @param PasswordExposedChecker|null $passwordExposedChecker
     */
    public function __construct(PasswordExposedChecker $passwordExposedChecker = null)
    {
        parent::__construct($passwordExposedChecker);

        $this->setMessage(trans('validation.found-in-data-breach'));
    }
}
