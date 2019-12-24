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

namespace App\Validators;

use Illuminate\Contracts\Config\Repository;

final class EmailValidator
{
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    private Repository $configRepository;

    public function __construct(Repository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    public function validateEmailList($attribute, $value, $parameters, $validator): bool
    {
        $domain = substr(strrchr($value, '@'), 1);
        switch ($parameters[0]) {
            case 'block':
                $domain_list = $this->configRepository->get('email-white-blacklist.block');

                return ! in_array($domain, $domain_list);

                break;
            case 'allow':
                $domain_list = $this->configRepository->get('email-white-blacklist.allow');

                return in_array($domain, $domain_list);

                break;
            default:
                // code...
                break;
        }
    }
}
