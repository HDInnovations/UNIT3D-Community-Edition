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
    | Model Fields To Disregard
    |--------------------------------------------------------------------------
    |
    | Array []
    |
    */

    'global_discards' => [
        'password', 'passkey', 'rsskey', 'ip', 'remember_token',
        'views', 'num_post', 'read',
        'last_reply_at', 'last_action', 'created_at', 'updated_at', 'deleted_at',
    ],

    /*
    |--------------------------------------------------------------------------
    | Recyle Old Audit Records
    |--------------------------------------------------------------------------
    |
    | In Days!
    |
    */

    'recycle' => 30,

];
