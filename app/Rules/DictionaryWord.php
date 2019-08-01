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
 * Class DictionaryWord.
 *
 * Implements the 'Dictionary word' recommendation
 * from NIST SP 800-63B section 5.1.1.2.
 */
class DictionaryWord implements Rule
{
    const DICTIONARY_FILE = __DIR__.'/../../resources/words.txt';

    private $words = [];

    /**
     * DictionaryWord constructor.
     */
    public function __construct()
    {
        $this->words = explode(PHP_EOL, file_get_contents(self::DICTIONARY_FILE));
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
        return ! in_array(strtolower(trim($value)), $this->words);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.can-not-be-dictionary-word', ['word' => $this->words]);
    }
}
