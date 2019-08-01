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

namespace App\Traits;

use App\Rules\DictionaryWord;
use App\Rules\BreachedPassword;
use App\Rules\ContextSpecificWord;
use App\Rules\RepetitiveCharacter;
use App\Rules\SequentialCharacter;
use App\Rules\DerivativesOfContextSpecificWord;

abstract class NISTPassword
{
    public static function register($username)
    {
        return [
            'required',
            'string',
            'min:8',
            new SequentialCharacter(),
            new RepetitiveCharacter(),
            new DictionaryWord(),
            //new ContextSpecificWord($username),
            //new DerivativesOfContextSpecificWord($username),
            new BreachedPassword(),
        ];
    }

    public static function changePassword($username, $oldPassword = null)
    {
        $rules = self::register($username);

        if ($oldPassword) {
            $rules = array_merge($rules, [
                'different:'.$oldPassword,
            ]);
        }

        return $rules;
    }

    public static function optionallyChangePassword($username, $oldPassword = null)
    {
        $rules = self::changePassword($username, $oldPassword);

        $rules = array_merge($rules, [
            'nullable',
        ]);

        foreach ($rules as $key => $rule) {
            if (is_string($rule) && $rule === 'required') {
                unset($rules[$key]);
            }
        }

        return $rules;
    }

    public static function login()
    {
        return [
            'required',
            'string',
        ];
    }
}
