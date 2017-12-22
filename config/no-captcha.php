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

return [
    /* ------------------------------------------------------------------------------------------------
     |  Credentials
     | ------------------------------------------------------------------------------------------------
     */
    'secret' => getenv('NOCAPTCHA_SECRET') ?: 'no-captcha-secret',
    'sitekey' => getenv('NOCAPTCHA_SITEKEY') ?: 'no-captcha-sitekey',

    /* ------------------------------------------------------------------------------------------------
     |  Localization
     | ------------------------------------------------------------------------------------------------
     */
    'lang' => app()->getLocale(),

    /* ------------------------------------------------------------------------------------------------
     |  Attributes
     | ------------------------------------------------------------------------------------------------
     */
    'attributes' => [
        'data-theme' => null, // 'light', 'dark'
        'data-type' => null, // 'image', 'audio'
        'data-size' => null, // 'normal', 'compact'
    ],
];
