<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('categories')->delete();

        \DB::table('categories')->insert([
            0 =>
                [
                    'id' => 1,
                    'name' => 'Movies',
                    'slug' => 'movies',
                    'position' => 0,
                    'icon' => 'fa fa-film',
                    'num_torrent' => 0,
                    'meta' => 1,
                ],
                1 =>
                [
                    'id' => 2,
                    'name' => 'TV',
                    'slug' => 'tv',
                    'position' => 1,
                    'icon' => 'fa fa-television',
                    'num_torrent' => 0,
                    'meta' => 1,
                ],
                2 =>
                [
                    'id' => 3,
                    'name' => 'Music',
                    'slug' => 'music',
                    'position' => 2,
                    'icon' => 'fa fa-music',
                    'num_torrent' => 0,
                    'meta' => 0,
                ],
        ]);
    }
}
