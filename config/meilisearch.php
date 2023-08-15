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
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Meilisearch Host
    |--------------------------------------------------------------------------
    |
    | Meilisearch host.
    | E.g. http://127.0.0.1:7700
    |
    */

    'host' => env('MEILISEARCH_HOST', ''),

    /*
    |--------------------------------------------------------------------------
    | Meilisearch Master Key
    |--------------------------------------------------------------------------
    |
    | Meilisearch master API key
    |
    */

    'key' => env('MEILISEARCH_KEY'),
];
