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
                    'icon' => 'fa fa-film',
                    'num_torrent' => 0,
                    'meta' => 1;
                ),
            1 =>
                array(
                    'id' => 2,
                    'name' => 'TV',
                    'slug' => 'tv',
                    'icon' => 'fa fa-television',
                    'num_torrent' => 0,
                    'meta' => 1;
                ),
            2 =>
                array(
                    'id' => 3,
                    'name' => 'Music',
                    'slug' => 'music',
                    'icon' => 'fa fa-music',
                    'num_torrent' => 0,
                    'meta' => 0;
                ),
        ));


    }
}
