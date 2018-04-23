<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    |
    | Graveyard On/Off
    |
    */

    'enabled' => false,

    /*
     |------------------------------------------------------------------------------------------------
     |  Credentials
     |
     |  Grab captcha keys from .env file. Default uses default test keys.
     | ------------------------------------------------------------------------------------------------
     */

    'secretkey' => env('CAPTCHA_SECRETKEY', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'),
    'sitekey' => env('CAPTCHA_SITEKEY', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI'),
];
