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

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(GroupsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(BonExchangeTableSeeder::class);
        $this->call(AchievementDetailsTableSeeder::class);
        $this->call(PagesTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(TypesTableSeeder::class);
        $this->call(ArticlesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(ForumsTableSeeder::class);
        $this->call(ChatroomTableSeeder::class);
        $this->call(ChatStatusSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(BotsTableSeeder::class);
    }
}
