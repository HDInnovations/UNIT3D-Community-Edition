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

class ArticlesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('articles')->delete();

        \DB::table('articles')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'title' => 'Welcome To UNIT3D',
                    'slug' => 'welcome_to_unit3d',
                    'content' => 'For the last year, I have been developing a Nex-Gen Torrent Tracker Script called “UNIT3D.” This is a PHP script based off the lovely Laravel Framework – currently Laravel Framework 5.4.36, MySQL Strict Mode Compliant and PHP 7.1 Ready. The code is well-designed and follows the PSR-2 coding style. It uses a MVC Architecture to ensure clarity between logic and presentation. As a hashing algorithm Bcrypt is used, to ensure a safe and proper way to store the passwords for the users. A lightweight Blade Templating Engine. Caching System Supporting: “apc,” “array,” “database,” “file,” “memcached,” and “redis” methods. Eloquent and much more!',
                    'user_id' => 3,
                ),
        ));
    }
}
