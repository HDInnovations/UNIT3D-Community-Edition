<?php
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

use App\Enums\Permission;

return [
    'role-based-access-control' => 'Role-based access control',
    'roles'                     => 'Roles',
    'role'                      => 'Role',
    'add-role'                  => 'Add Role',
    'site'                      => 'Site',
    'tracker'                   => 'Tracker',
    'grant'                     => 'Grant',
    'revoke'                    => 'Revoke',
    'override-inherited-role'   => 'Override Inherited Role',
    'permission-enum'           => [
        Permission::MESSAGE_CREATE->value => [
            'name' => 'Can send chatbox messages',
        ],
        Permission::COMMENT_CREATE->value => [
            'name' => 'Can create comments',
        ],
        Permission::ANNOUNCE_PEER_VIEW->value => [
            'name' => 'Can receive peer list',
        ],
        Permission::REQUEST_CREATE->value => [
            'name' => 'Can create torrent requests',
        ],
        Permission::INVITE_CREATE->value => [
            'name' => 'Can send invites',
        ],
        Permission::TORRENT_CREATE->value => [
            'name' => 'Can upload torrents',
        ],
    ],
];
