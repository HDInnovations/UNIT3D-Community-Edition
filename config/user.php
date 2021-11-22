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
    | User Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default settings for users if they belong to no group.
    |
    */

    'group' => [
        'defaults' => [
            'name'          => 'Orphan',
            'slug'          => 'orphan',
            'color'         => '#FF9966',
            'effect'        => '',
            'icon'          => 'fal fa-robot',
            'is_admin'      => false,
            'is_freeleech'  => false,
            'is_immune'     => false,
            'is_incognito'  => false,
            'is_internal'   => false,
            'is_modo'       => false,
            'is_trusted'    => false,
            'can_upload'    => false,
            'level'         => 0,
            'position'      => 0,
        ],
    ],

    'privacy' => [
        'defaults' => [
            'is_hidden'  => false,
            'is_private' => false,
        ],
    ],
];
