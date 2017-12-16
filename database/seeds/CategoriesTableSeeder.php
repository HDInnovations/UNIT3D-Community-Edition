<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
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

        \DB::table('categories')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'name' => 'Movies',
                    'slug' => 'movies',
                    'num_torrent' => 0,
                ),
            1 =>
                array(
                    'id' => 2,
                    'name' => 'TV',
                    'slug' => 'tv',
                    'num_torrent' => 0,
                ),
            2 =>
                array(
                    'id' => 3,
                    'name' => 'FANRES',
                    'slug' => 'fanres',
                    'num_torrent' => 0,
                ),
        ));


    }
}
