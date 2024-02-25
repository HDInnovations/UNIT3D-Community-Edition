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
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $permissionIds = array_column(Permission::cases(), 'value');

        DB::table('permissions')->upsert(
            array_map(static fn ($id) => ['id' => $id], $permissionIds),
            ['id'],
        );

        DB::table('permissions')->whereNotIn('id', $permissionIds)->delete();
    }
}
