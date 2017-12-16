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

class TypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('types')->delete();

        \DB::table('types')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'name' => 'UHD-100',
                    'slug' => 'uhd-100',
                    'position' => 0,
                ),
            1 =>
                array(
                    'id' => 2,
                    'name' => 'UHD-66',
                    'slug' => 'uhd-66',
                    'position' => 1,
                ),
            2 =>
                array(
                    'id' => 3,
                    'name' => 'UHD-50',
                    'slug' => 'uhd-50',
                    'position' => 2,
                ),
            3 =>
                array(
                    'id' => 4,
                    'name' => 'BD50',
                    'slug' => 'bd50',
                    'position' => 4,
                ),
            4 =>
                array(
                    'id' => 5,
                    'name' => 'BD25',
                    'slug' => 'bd25',
                    'position' => 5,
                ),
            5 =>
                array(
                    'id' => 6,
                    'name' => 'BD25Encode',
                    'slug' => 'bd25encode',
                    'position' => 6,
                ),
            6 =>
                array(
                    'id' => 7,
                    'name' => 'Remux',
                    'slug' => 'remux',
                    'position' => 7,
                ),
            7 =>
                array(
                    'id' => 8,
                    'name' => '2160p',
                    'slug' => '2160p',
                    'position' => 8,
                ),
            8 =>
                array(
                    'id' => 9,
                    'name' => '1080p',
                    'slug' => '1080p',
                    'position' => 9,
                ),
            9 =>
                array(
                    'id' => 10,
                    'name' => '900p',
                    'slug' => '900p',
                    'position' => 10,
                ),
            10 =>
                array(
                    'id' => 11,
                    'name' => '720p',
                    'slug' => '720p',
                    'position' => 11,
                ),
            11 =>
                array(
                    'id' => 12,
                    'name' => 'UHD-Remux',
                    'slug' => 'uhd-remux',
                    'position' => 3,
                ),
            12 =>
                array(
                    'id' => 13,
                    'name' => 'SD',
                    'slug' => 'sd',
                    'position' => 12,
                ),
        ));


    }
}
