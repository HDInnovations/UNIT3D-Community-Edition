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
    | Blacklist On/Off
    |
    */

    'enabled' => false,

    /*
    |--------------------------------------------------------------------------
    | Blacklist Clients
    |--------------------------------------------------------------------------
    | An array of clients to be blacklisted which will reject them from announcing
    | to the sites tracker.
    |
    |
    */
    'clients' => [
        'Transmission/2.93', 'Transmission/2.04',
    ],

    /*
    |--------------------------------------------------------------------------
    | Blacklist Browsers
    |--------------------------------------------------------------------------
    | An array of browsers to be blacklisted which will reject them from announcing
    | to the sites tracker.
    |
    |
    */
    'browsers' => [
        'Mozilla', 'AppleWebKit', 'Safari', 'Chrome', 'Lynx', 'Opera',
    ],

];
