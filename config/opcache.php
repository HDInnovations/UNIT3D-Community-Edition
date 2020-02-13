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
    'url'         => env('OPCACHE_URL', config('app.url')),
    'verify_ssl'  => true,
    'headers'     => [],
    'directories' => [
        base_path('app'),
        base_path('bootstrap'),
        base_path('public'),
        base_path('resources/lang'),
        base_path('routes'),
        base_path('storage/framework/views'),
        base_path('vendor/appstract'),
        base_path('vendor/composer'),
        base_path('vendor/laravel/framework'),
    ],
];
