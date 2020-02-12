<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Validators;

class EmailValidator
{
    public function validateEmailList($attribute, $value, $parameters, $validator)
    {
        $domain = substr(strrchr($value, '@'), 1);
        switch ($parameters[0]) {
            case 'block':
                $domain_list = config('email-white-blacklist.block');

                return !in_array($domain, $domain_list);

                break;
            case 'allow':
                $domain_list = config('email-white-blacklist.allow');

                return in_array($domain, $domain_list);

                break;
            default:
                // code...
                break;
        }
    }
}
