<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     BluCrew
 */

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('users')->delete();

        \DB::table('users')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Movies',
                'slug' => 'movies',
                'num_torrent' => 0,
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'TV',
                'slug' => 'tv',
                'num_torrent' => 0,
            ),
            2 =>
            array (
                'id' => 3,
                'name' => 'FANRES',
                'slug' => 'fanres',
                'num_torrent' => 0,
            ),
        ));


    }
}


class UserTableSeeder extends Seeder {
    public function run() {
        // System
        $user = new \App\User;
        $user->id = 0;
        $user->username = 'System';
        $user->email = 'system@none.com';
        $user->password = \Hash::make(env('DEFAULT_OWNER_PASSWORD'));
        $user->group_id = '9';
        $user->save();
        // Bot
        $user = new \App\User;
        $user->id = 0;
        $user->username = 'UNIT3D';
        $user->email = 'bot@none.com';
        $user->password = \Hash::make(env('DEFAULT_OWNER_PASSWORD'));
        $user->group_id = '9';
        $user->save();
        // Owner
        $user = new \App\User;
        $user->username = env('DEFAULT_ADMIN_NAME');
        $user->email = env('DEFAULT_ADMIN_EMAIL');
        $user->password = \Hash::make(env('DEFAULT_OWNER_PASSWORD'));
        $user->group_id = '10';
        $user->save();
    }
