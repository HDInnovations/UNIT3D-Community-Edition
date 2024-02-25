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

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        Role::upsert([
            [
                'id'                  => 1,
                'position'            => 1,
                'name'                => 'Disabled',
                'description'         => 'No permissions.',
                'system_required'     => true,
                'auto_manage'         => false,
                'warnings_active_max' => null,
                'warnings_active_min' => null,
            ],
            [
                'id'                  => 2,
                'position'            => 2,
                'name'                => 'Leech',
                'description'         => 'Minimum ratio not achieved.',
                'system_required'     => true,
                'auto_manage'         => false,
                'warnings_active_max' => null,
                'warnings_active_min' => null,
            ],
            [
                'id'                  => 3,
                'position'            => 3,
                'name'                => 'Warned',
                'description'         => 'Minimum seedtime not achieved.',
                'system_required'     => true,
                'auto_manage'         => true,
                'warnings_active_max' => null,
                'warnings_active_min' => 4,
            ],
        ], ['id']);
    }
}
