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

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;

trait Encryptable
{
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (\in_array($key, $this->encryptable, true)) {
            try {
                $decryptedValue = Crypt::decrypt($value);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $decryptException) {
                $decryptedValue = 'The value could not be decrypted.';
            }
            return $decryptedValue;
        }

        return $value;
    }

    public function setAttribute($key, $value)
    {
        if (\in_array($key, $this->encryptable, true)) {
            $value = Crypt::encrypt($value);
        }

        return parent::setAttribute($key, $value);
    }
}
