<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */
 
namespace App\Traits;

use Illuminate\Support\Facades\Crypt;

trait Encryptable
{
    /**
     * Decrypt the column value if it is in the encrypted array.
     *
     * @param $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        if (in_array($key, $this->encrypted ?? [])) {
            $value = $this->decryptValue($value);
        }

        return $value;
    }

    /**
     * Decrypts a value only if it is not null and not empty.
     *
     * @param $value
     *
     * @return mixed
     */
    protected function decryptValue($value)
    {
        if ($value !== null && !empty($value)) {
            return Crypt::decrypt($value);
        }

        return $value;
    }

    /**
     * Set the value, encrypting it if it is in the encrypted array.
     *
     * @param $key
     * @param $value
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encrypted ?? [])) {
            $value = Crypt::encrypt($value);
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Retrieves all values and decrypts them if needed.
     *
     * @return mixed
     */
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        foreach ($this->encrypted ?? [] as $key) {
            if (isset($attributes[$key])) {
                $attributes[$key] = $this->decryptValue($attributes[$key]);
            }
        }

        return $attributes;
    }
}
