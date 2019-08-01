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

/**
 * Class DerivativesOfContextSpecificWord.
 *
 * Implements the 'Context-specific words' recommendation with 'derivatives thereof'.
 * from NIST SP 800-63B section 5.1.1.2.
 */
class DerivativesOfContextSpecificWord extends ContextSpecificWord implements Rule
{
    private $detectedWord = null;

    /**
     * DerivativesOfContextSpecificWord constructor.
     *
     * @param $username
     */
    public function __construct($username)
    {
        parent::__construct($username);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = strtolower($value);

        foreach ($this->words as $word) {
            similar_text($value, $word, $percentage);

            if ($percentage >= 75) {
                $this->detectedWord = $word;

                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.can-not-be-similar-to-word', ['word' => $this->detectedWord]);
    }
}
