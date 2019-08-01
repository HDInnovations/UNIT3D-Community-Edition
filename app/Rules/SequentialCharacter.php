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
 * Class SequentialCharacter.
 *
 * Implements the 'Sequential characters' recommendation
 * from NIST SP 800-63B section 5.1.1.2.
 */
class SequentialCharacter implements Rule
{
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
        $haystack = strtolower($value);
        $group = [];

        for ($start = 48; $start <= 88; $start++) {
            $sequence = '';
            for ($charCode = $start; $charCode < $start + 3; $charCode++) {
                if ($charCode >= 58 && $charCode <= 64) {
                    continue 2;
                }
                $sequence .= chr($charCode);
            }
            $group[] = strtolower($sequence);
        }
        $group[] = '098';

        foreach ($group as $needle) {
            if (strpos($haystack, $needle) !== false || strpos($haystack, strrev($needle)) !== false) {
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
        return trans('validation.can-not-be-sequential-characters');
    }
}
