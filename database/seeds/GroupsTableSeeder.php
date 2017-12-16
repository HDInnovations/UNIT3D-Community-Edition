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

class GroupsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('groups')->delete();

        \DB::table('groups')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'name' => 'Validating',
                    'slug' => 'validating',
                    'position' => 2,
                    'color' => '#95A5A6',
                    'icon' => 'fa fa-question-circle',
                    'effect' => 'none',
                    'is_admin' => 0,
                    'is_modo' => 0,
                    'is_trusted' => 0,
                    'is_immune' => 0,
                    'is_freeleech' => 0,
                    'autogroup' => 0,
                ),
            1 =>
                array(
                    'id' => 2,
                    'name' => 'Guest',
                    'slug' => 'guests',
                    'position' => 1,
                    'color' => '#575757',
                    'icon' => 'fa fa-question-circle',
                    'effect' => 'none',
                    'is_admin' => 0,
                    'is_modo' => 0,
                    'is_trusted' => 0,
                    'is_immune' => 0,
                    'is_freeleech' => 0,
                    'autogroup' => 0,
                ),
            2 =>
                array(
                    'id' => 3,
                    'name' => 'Member',
                    'slug' => 'members',
                    'position' => 4,
                    'color' => '#7289DA',
                    'icon' => 'fa fa-user',
                    'effect' => 'none',
                    'is_admin' => 0,
                    'is_modo' => 0,
                    'is_trusted' => 0,
                    'is_immune' => 0,
                    'is_freeleech' => 0,
                    'autogroup' => 1,
                ),
            3 =>
                array(
                    'id' => 4,
                    'name' => 'Administrator',
                    'slug' => 'administrators',
                    'position' => 15,
                    'color' => '#f92672',
                    'icon' => 'fa fa-user-secret',
                    'effect' => 'none',
                    'is_admin' => 1,
                    'is_modo' => 1,
                    'is_trusted' => 1,
                    'is_immune' => 1,
                    'is_freeleech' => 0,
                    'autogroup' => 0,
                ),
            4 =>
                array(
                    'id' => 5,
                    'name' => 'Banned',
                    'slug' => 'banned',
                    'position' => 0,
                    'color' => 'red',
                    'icon' => 'fa fa-ban',
                    'effect' => 'none',
                    'is_admin' => 0,
                    'is_modo' => 0,
                    'is_trusted' => 0,
                    'is_immune' => 0,
                    'is_freeleech' => 0,
                    'autogroup' => 0,
                ),
            5 =>
                array(
                    'id' => 6,
                    'name' => 'Moderator',
                    'slug' => 'moderators',
                    'position' => 14,
                    'color' => '#4ECDC4',
                    'icon' => 'fa fa-user-secret',
                    'effect' => 'none',
                    'is_admin' => 0,
                    'is_modo' => 1,
                    'is_trusted' => 1,
                    'is_immune' => 1,
                    'is_freeleech' => 0,
                    'autogroup' => 0,
                ),
            6 =>
                array(
                    'id' => 7,
                    'name' => 'Uploader',
                    'slug' => 'uploader',
                    'position' => 12,
                    'color' => '#2ECC71',
                    'icon' => 'fa fa-upload',
                    'effect' => 'none',
                    'is_admin' => 0,
                    'is_modo' => 0,
                    'is_trusted' => 1,
                    'is_immune' => 1,
                    'is_freeleech' => 1,
                    'autogroup' => 0,
                ),
            7 =>
                array(
                    'id' => 8,
                    'name' => 'Trustee',
                    'slug' => 'trustee',
                    'position' => 13,
                    'color' => '#BF55EC',
                    'icon' => 'fa fa-shield',
                    'effect' => 'none',
                    'is_admin' => 0,
                    'is_modo' => 0,
                    'is_trusted' => 1,
                    'is_immune' => 1,
                    'is_freeleech' => 1,
                    'autogroup' => 0,
                ),
            8 =>
                array(
                    'id' => 9,
                    'name' => 'Bot',
                    'slug' => 'bot',
                    'position' => 17,
                    'color' => '#f1c40f',
                    'icon' => 'fa fa-android',
                    'effect' => 'none',
                    'is_admin' => 0,
                    'is_modo' => 0,
                    'is_trusted' => 1,
                    'is_immune' => 1,
                    'is_freeleech' => 0,
                    'autogroup' => 0,
                ),
            9 =>
                array(
                    'id' => 10,
                    'name' => 'Coder',
                    'slug' => 'coder',
                    'position' => 16,
                    'color' => '#00abff',
                    'icon' => 'fa fa-code',
                    'effect' => 'none',
                    'is_admin' => 1,
                    'is_modo' => 1,
                    'is_trusted' => 1,
                    'is_immune' => 1,
                    'is_freeleech' => 0,
                    'autogroup' => 0,
                ),
            10 =>
                array(
                    'id' => 11,
                    'name' => 'BluMember',
                    'slug' => 'blumember',
                    'position' => 5,
                    'color' => '#3c78d8',
                    'icon' => 'fa fa-user-circle-o',
                    'effect' => 'none',
                    'is_admin' => 0,
                    'is_modo' => 0,
                    'is_trusted' => 0,
                    'is_immune' => 0,
                    'is_freeleech' => 0,
                    'autogroup' => 1,
                ),
            11 =>
                array(
                    'id' => 12,
                    'name' => 'BluMaster',
                    'slug' => 'blumaster',
                    'position' => 6,
                    'color' => '#1155cc',
                    'icon' => 'fa fa-power-off',
                    'effect' => 'none',
                    'is_admin' => 0,
                    'is_modo' => 0,
                    'is_trusted' => 0,
                    'is_immune' => 0,
                    'is_freeleech' => 0,
                    'autogroup' => 1,
                ),
            12 =>
                array(
                    'id' => 13,
                    'name' => 'BluExtremist',
                    'slug' => 'bluextremist',
                    'position' => 7,
                    'color' => '#1c4587',
                    'icon' => 'fa fa-bolt',
                    'effect' => 'none',
                    'is_admin' => 0,
                    'is_modo' => 0,
                    'is_trusted' => 1,
                    'is_immune' => 0,
                    'is_freeleech' => 0,
                    'autogroup' => 1,
                ),
            13 =>
                array(
                    'id' => 14,
                    'name' => 'BluLegend',
                    'slug' => 'blulegend',
                    'position' => 8,
                    'color' => '#1c4587',
                    'icon' => 'fa fa-rocket',
                    'effect' => 'none',
                    'is_admin' => 0,
                    'is_modo' => 0,
                    'is_trusted' => 1,
                    'is_immune' => 0,
                    'is_freeleech' => 0,
                    'autogroup' => 1,
                ),
            14 =>
                array(
                    'id' => 15,
                    'name' => 'Leech',
                    'slug' => 'leech',
                    'position' => 3,
                    'color' => '#96281B',
                    'icon' => 'fa fa-times',
                    'effect' => 'none',
                    'is_admin' => 0,
                    'is_modo' => 0,
                    'is_trusted' => 0,
                    'is_immune' => 0,
                    'is_freeleech' => 0,
                    'autogroup' => 1,
                ),
            15 =>
                array(
                    'id' => 16,
                    'name' => 'Blutopian',
                    'slug' => 'blutopian',
                    'position' => 9,
                    'color' => '#1c4587',
                    'icon' => 'fa fa-key',
                    'effect' => 'url(https://i.imgur.com/F0UCb7A.gif)',
                    'is_admin' => 0,
                    'is_modo' => 0,
                    'is_trusted' => 1,
                    'is_immune' => 1,
                    'is_freeleech' => 1,
                    'autogroup' => 1,
                ),
            16 =>
                array(
                    'id' => 17,
                    'name' => 'BluSeeder',
                    'slug' => 'bluseeder',
                    'position' => 10,
                    'color' => '#1c4587',
                    'icon' => 'fa fa-hdd-o',
                    'effect' => 'none',
                    'is_admin' => 0,
                    'is_modo' => 0,
                    'is_trusted' => 1,
                    'is_immune' => 1,
                    'is_freeleech' => 0,
                    'autogroup' => 1,
                ),
            17 =>
                array(
                    'id' => 18,
                    'name' => 'BluArchivist',
                    'slug' => 'bluarchivist',
                    'position' => 11,
                    'color' => '#1c4587',
                    'icon' => 'fa fa-tasks',
                    'effect' => 'url(https://i.imgur.com/F0UCb7A.gif)',
                    'is_admin' => 0,
                    'is_modo' => 0,
                    'is_trusted' => 1,
                    'is_immune' => 1,
                    'is_freeleech' => 1,
                    'autogroup' => 1,
                ),
        ));


    }
}
