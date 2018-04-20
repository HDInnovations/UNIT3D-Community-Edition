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

use App\Group;
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
        //1
        Group::create([
            'name' => 'Validating',
            'slug' => 'validating',
            'position' => 2,
            'color' => '#95A5A6',
            'icon' => 'fa fa-question-circle'
        ]);

        //2
        Group::create([
            'name' => 'Guest',
            'slug' => 'guest',
            'position' => 1,
            'color' => '#575757',
            'icon' => 'fa fa-question-circle'
        ]);

        //3
        Group::create([
            'name' => 'User',
            'slug' => 'user',
            'position' => 4,
            'color' => '#7289DA',
            'icon' => 'fa fa-user',
            'autogroup' => 1
        ]);

        //4
        Group::create([
            'name' => 'Administrator',
            'slug' => 'administrator',
            'position' => 15,
            'color' => '#f92672',
            'icon' => 'fa fa-user-secret',
            'is_admin' => 1,
            'is_modo' => 1,
            'is_trusted' => 1,
            'is_immune' => 1
        ]);

        //5
        Group::create([
            'name' => 'Banned',
            'slug' => 'banned',
            'position' => 0,
            'color' => 'red',
            'icon' => 'fa fa-ban'
        ]);

        //6
        Group::create([
            'name' => 'Moderator',
            'slug' => 'moderator',
            'position' => 14,
            'color' => '#4ECDC4',
            'icon' => 'fa fa-user-secret',
            'is_modo' => 1,
            'is_trusted' => 1,
            'is_immune' => 1
        ]);

        //7
        Group::create([
            'name' => 'Uploader',
            'slug' => 'uploader',
            'position' => 12,
            'color' => '#2ECC71',
            'icon' => 'fa fa-upload',
            'is_trusted' => 1,
            'is_immune' => 1,
            'is_freeleech' => 1
        ]);

        //8
        Group::create([
            'name' => 'Trustee',
            'slug' => 'trustee',
            'position' => 13,
            'color' => '#BF55EC',
            'icon' => 'fa fa-shield',
            'is_trusted' => 1,
            'is_immune' => 1,
            'is_freeleech' => 1
        ]);

        //9
        Group::create([
            'name' => 'Bot',
            'slug' => 'bot',
            'position' => 17,
            'color' => '#f1c40f',
            'icon' => 'fa fa-android',
            'is_modo' => 1,
            'is_trusted' => 1,
            'is_immune' => 1
        ]);

        //10
        Group::create([
            'name' => 'Owner',
            'slug' => 'owner',
            'position' => 16,
            'color' => '#00abff',
            'icon' => 'fa fa-user-secret',
            'is_admin' => 1,
            'is_modo' => 1,
            'is_trusted' => 1,
            'is_immune' => 1
        ]);

        //11
        Group::create([
            'name' => 'PowerUser',
            'slug' => 'poweruser',
            'position' => 5,
            'color' => '#3c78d8',
            'icon' => 'fa fa-user-circle-o',
            'autogroup' => 1
        ]);

        //12
        Group::create([
            'name' => 'SuperUser',
            'slug' => 'superuser',
            'position' => 6,
            'color' => '#1155cc',
            'icon' => 'fa fa-power-off',
            'autogroup' => 1
        ]);

        //13
        Group::create([
            'name' => 'ExtremeUser',
            'slug' => 'extremeuser',
            'position' => 7,
            'color' => '#1c4587',
            'icon' => 'fa fa-bolt',
            'is_trusted' => 1,
            'autogroup' => 1
        ]);

        //14
        Group::create([
            'name' => 'InsaneUser',
            'slug' => 'insaneuser',
            'position' => 8,
            'color' => '#1c4587',
            'icon' => 'fa fa-rocket',
            'is_trusted' => 1,
            'autogroup' => 1
        ]);

        //15
        Group::create([
            'name' => 'Leech',
            'slug' => 'leech',
            'position' => 3,
            'color' => '#96281B',
            'icon' => 'fa fa-times',
            'autogroup' => 1
        ]);

        //16
        Group::create([
            'name' => 'Veteran',
            'slug' => 'veteran',
            'position' => 9,
            'color' => '#1c4587',
            'icon' => 'fa fa-key',
            'effect' => 'url(https://i.imgur.com/F0UCb7A.gif)',
            'is_trusted' => 1,
            'is_immune' => 1,
            'is_freeleech' => 1,
            'autogroup' => 1
        ]);

        //17
        Group::create([
            'name' => 'Seeder',
            'slug' => 'seeder',
            'position' => 10,
            'color' => '#1c4587',
            'icon' => 'fa fa-hdd-o',
            'is_trusted' => 1,
            'is_immune' => 1,
            'autogroup' => 1
        ]);

        //18
        Group::create([
            'name' => 'Archivist',
            'slug' => 'archivist',
            'position' => 11,
            'color' => '#1c4587',
            'icon' => 'fa fa-tasks',
            'effect' => 'url(https://i.imgur.com/F0UCb7A.gif)',
            'is_trusted' => 1,
            'is_immune' => 1,
            'is_freeleech' => 1,
            'autogroup' => 1
        ]);

        //19
        Group::create([
            'name' => 'Internal',
            'slug' => 'internal',
            'position' => 11,
            'color' => '#BAAF92',
            'icon' => 'fa fa-magic',
            'is_trusted' => 1,
            'is_immune' => 1,
            'is_freeleech' => 1,
            'is_internal' => 1,
        ]);
    }
}
