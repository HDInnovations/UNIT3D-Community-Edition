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

return [
    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    |
    | Hidden Captcha On/Off
    |
    */

    'enabled' => true,

    /*
    |
    | Captcha Image Settings
    |
    */
    'characters' => ['2', '3', '4', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'M', 'N', 'P', 'Q', 'R', 'T', 'U', 'X', 'Y', 'Z'],
    
    'default'   => [
        'length'    => 4,
        'width'     => 200,
        'height'    => 50,
        'quality'   => 90,
        'math'      => false, //Enable Math Captcha
        'expire'    => 60,   //Stateless/API captcha expiration
    ],

    'inverse' => [
        'length' => 4,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'sensitive' => true,
        'angle' => 12,
        'sharpen' => 10,
        'blur' => 1,
        'invert' => true,
        'contrast' => 0,
    ]
];
