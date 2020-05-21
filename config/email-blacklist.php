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
    | Email Domain Blackist Validation
    |--------------------------------------------------------------------------
    |
    | The email domain blacklist validation rule uses a remote or local source
    | to get updated and also allows to specify a custom append list.
    |
    | enabled: true|false
    |
    | source: string|null
    |         You may specify the preferred URL or file path to update the blacklist.
    |         Keep null if you don't want to use a remote source.
    |         Default: "https://cdn.jsdelivr.net/gh/andreis/disposable-email-domains@master/domains.json"
    |
    | cache-key: string|null
    |         You may change the cache key for the sourced blacklist.
    |         Keep null if you want to use the default value.
    |
    | auto-update: true|false
    |         Specify if should automatically get source when cache is empty.
    |         ADVICE: This may slow down the first request upon validation.
    |         Default: true
    |
    | append: string|null
    |         You may a string of pipe | separated domains list.
    |         Keep null if you don't want to append custom domains.
    |         Example: "example.com|example.net|foobar.com".
    |
    */
    'enabled'     => true,
    'source'      => 'https://cdn.jsdelivr.net/gh/andreis/disposable-email-domains@master/domains.json',
    'cache-key'   => 'email.domains.blacklist',
    'auto-update' => true,
    'append'      => null,
];
