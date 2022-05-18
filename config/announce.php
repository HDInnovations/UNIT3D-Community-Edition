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
    | Interval
    |--------------------------------------------------------------------------
    |
    | Site Announce Interval
    |
    */

    'interval' => '(60 * 45)',

    /*
    |--------------------------------------------------------------------------
    | Min Interval
    |--------------------------------------------------------------------------
    |
    | Site Min Announce Interval
    |
    */

    'min_interval' => [

        'enabled' => (bool) env('CHECK_ANNOUNCE_INTERVAL', true),

        'interval' => (int) env('MIN_ANNOUNCE_INTERVAL', 60 * 10),

    ],
    /*
    |--------------------------------------------------------------------------
    | Rate Limit
    |--------------------------------------------------------------------------
    |
    | Amount Of Locations A User Can Seed A Single Torrent From
    |
    */

    'rate_limit' => 3,

    /*
    |--------------------------------------------------------------------------
    | Client Connectable Check
    |--------------------------------------------------------------------------
    |
    | This option toggles Client connectivity check
    | !!! Attention: Will result in leaking the server IP !!!
    | It will result in higher disc / DB IO
    |
    */

    'connectable_check' => false,

    /*
    |--------------------------------------------------------------------------
    | Connectable check interval
    |--------------------------------------------------------------------------
    |
    | Amount Of Time until the next connectable check
    |
    */

    'connectable_check_interval' => 60 * 30,

    /*
    |--------------------------------------------------------------------------
    | Download Slots System
    |--------------------------------------------------------------------------
    |
    | Enables download slots for user groups set in group settings via staff dashboard
    | Make sure you have a slot value set for EVERY group before enabling. This system is disabled
    | by default and groups download_slots are null. Null equals unlimited slots. Groups like banned should be
    | set to 0
    |
    */

    'slots_system' => [
        'enabled' => false,
    ],

];
