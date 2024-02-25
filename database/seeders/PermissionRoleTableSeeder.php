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

namespace Database\Seeders;

use App\Enums\Permission;
use App\Models\PermissionRole;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    public function run(): void
    {
        PermissionRole::upsert([
            [
                'role_id'       => 1,
                'permission_id' => Permission::MESSAGE_CREATE->value,
                'authorized'    => false,
            ],
            [
                'role_id'       => 1,
                'permission_id' => Permission::COMMENT_CREATE->value,
                'authorized'    => false,
            ],
            [
                'role_id'       => 1,
                'permission_id' => Permission::ANNOUNCE_PEER_VIEW->value,
                'authorized'    => false,
            ],
            [
                'role_id'       => 1,
                'permission_id' => Permission::REQUEST_CREATE->value,
                'authorized'    => false,
            ],
            [
                'role_id'       => 1,
                'permission_id' => Permission::INVITE_CREATE->value,
                'authorized'    => false,
            ],
            [
                'role_id'       => 1,
                'permission_id' => Permission::TORRENT_CREATE->value,
                'authorized'    => false,
            ],
            [
                'role_id'       => 2,
                'permission_id' => Permission::ANNOUNCE_PEER_VIEW->value,
                'authorized'    => false,
            ],
            [
                'role_id'       => 2,
                'permission_id' => Permission::REQUEST_CREATE->value,
                'authorized'    => false,
            ],
            [
                'role_id'       => 2,
                'permission_id' => Permission::INVITE_CREATE->value,
                'authorized'    => false,
            ],
            [
                'role_id'       => 3,
                'permission_id' => Permission::ANNOUNCE_PEER_VIEW->value,
                'authorized'    => false,
            ],
        ], ['role_id', 'group_id']);
    }
}
