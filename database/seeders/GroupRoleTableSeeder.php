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

use App\Models\GroupRole;
use Illuminate\Database\Seeder;

class GroupRoleTableSeeder extends Seeder
{
    public function run(): void
    {
        GroupRole::upsert([
            [
                'role_id'  => 1,
                'group_id' => 5,
            ],
            //            [
            //                'role_id'  => 1,
            //                'group_id' => 20,
            //            ],
            //            [
            //                'role_id'  => 1,
            //                'group_id' => 21,
            //            ],
            [
                'role_id'  => 2,
                'group_id' => 15,
            ],
        ], ['role_id', 'group_id']);
    }
}
