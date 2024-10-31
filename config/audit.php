<?php

declare(strict_types=1);
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
        'created_at',
        'deleted_at',
        'ip',
        'last_action',
        'nfo',
        'passkey',
        'password',
        'read',
        'remember_token',
        'rsskey',
        'updated_at',
        'views',
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
