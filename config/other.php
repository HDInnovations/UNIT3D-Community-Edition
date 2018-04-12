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
    | Codebase Name
    |--------------------------------------------------------------------------
    |
    | Name of Codebase
    |
    */

    'codebase' => '"UNIT3D" Nex-Gen Torrent Tracker v1.6.5',

    /*
    |--------------------------------------------------------------------------
    | Site title
    |--------------------------------------------------------------------------
    |
    | Title of Site
    |
    */

    'title' => 'UNIT3D',

    /*
    |--------------------------------------------------------------------------
    | Site SubTitle
    |--------------------------------------------------------------------------
    |
    | SubTitle
    |
    */

    'subTitle' => 'Built On Laravel',

    /*
    |--------------------------------------------------------------------------
    | Site email
    |--------------------------------------------------------------------------
    |
    | Email address to send emails
    |
    */

    'email' => 'none@none.com',

    /*
    |--------------------------------------------------------------------------
    | Meta description
    |--------------------------------------------------------------------------
    |
    | Default meta description content
    |
    */

    'meta_description' => 'Built On Laravel',

    /*
    |--------------------------------------------------------------------------
    | Site Birthdate
    |--------------------------------------------------------------------------
    |
    | Date Site Was Born
    |
    */
    'birthdate' => 'December 30th 2017',

    /*
    |--------------------------------------------------------------------------
    | Freelech State
    |--------------------------------------------------------------------------
    |
    | Global Freeleech
    |
    */
    'freeleech' => false,

    'freeleech_until' => '12/23/2017 3:00 PM EST',

    /*
    |--------------------------------------------------------------------------
    | Double Upload State
    |--------------------------------------------------------------------------
    |
    | Global Double Upload
    |
    */
    'doubleup' => false,

    /*
    |--------------------------------------------------------------------------
    | Min Ratio
    |--------------------------------------------------------------------------
    |
    | Minimum Ratio To Download
    |
    */

    'ratio' => 0.4,

    /*
    |--------------------------------------------------------------------------
    | Private tracker
    |--------------------------------------------------------------------------
    |
    | Registered member only can access to the site
    |
    */
    'private' => true,

    /*
    |--------------------------------------------------------------------------
    | Invite only
    |--------------------------------------------------------------------------
    |
    | Invite System On/Off (Open Reg / Closed)
    | Expire time in days
    |
    */
    'invite-only' => true,
    'invite_expire' => '14',

    /*
    |--------------------------------------------------------------------------
    | Max Seedbox Records (USER)
    |--------------------------------------------------------------------------
    |
    | Users max seedboxs allowed
    |
    */
    'max_cli' => 6,

    /*
    |--------------------------------------------------------------------------
    | Default Users Stats
    |--------------------------------------------------------------------------
    |
    | This will be the upload and download given to new members. (In Bytes!)
    | Default: 50GB Upload and 1GB Download
    */
    'default_upload' => '53687091200',
    'default_download' => '1073741824',
    'default_style' => 0,

    'invites_restriced' => false,
    'invite_groups' => [],
];
