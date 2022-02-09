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

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            GroupsTableSeeder::class,
            UsersTableSeeder::class,
            BonExchangeTableSeeder::class,
            AchievementDetailsTableSeeder::class,
            PagesTableSeeder::class,
            CategoriesTableSeeder::class,
            TypesTableSeeder::class,
            ArticlesTableSeeder::class,
            PermissionsTableSeeder::class,
            ForumsTableSeeder::class,
            ChatroomTableSeeder::class,
            ChatStatusSeeder::class,
            BotsTableSeeder::class,
            MediaLanguagesSeeder::class,
            ResolutionsTableSeeder::class,
            TicketCategoriesTableSeeder::class,
            TicketPrioritiesTableSeeder::class,
            DistributorsTableSeeder::class,
            RegionsTableSeeder::class,
        ]);
    }
}
