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
     * This is the API Token you got from https://api.igdb.com
     */
    'api_token' => env('IGDB_TOKEN', ''),

    /*
     * This package caches queries automatically.
     * Here you can set how long (in seconds) each query should be cached (by
     * default).
     *
     * To turn cache off set this value to 0
     */
    'cache_lifetime' => env('IGDB_CACHE_LIFETIME', 3600),

    /*
     * This is the per-page limit for your tier ("Free" by default)
     * Adjust this to 500 if you are on the "Pro" tier or in the Partner Program
     * or to 5000 if you are in the "Enterprise" tier.
     */
    'per_page_limit' => 50,

    /*
     * This is the offset limit for your tier ("Free" by default)
     * Adjust this to 5000 if you are on the "Pro" tier or in the Partner Program
     * or to 0 (to turn it off) if you are in the "Enterprise" tier.
     */
    'offset_limit' => 150,
];
