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

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        //1
        Group::create([
            'name'       => 'Validating',
            'slug'       => 'validating',
            'position'   => 4,
            'color'      => '#95A5A6',
            'icon'       => config('other.font-awesome').' fa-question-circle',
            'can_upload' => 0,
            'level'      => 0,
        ]);

        //2
        Group::create([
            'name'       => 'Guest',
            'slug'       => 'guest',
            'position'   => 3,
            'color'      => '#575757',
            'icon'       => config('other.font-awesome').' fa-question-circle',
            'can_upload' => 0,
            'level'      => 10,
        ]);

        //3
        Group::create([
            'name'      => 'User',
            'slug'      => 'user',
            'position'  => 6,
            'color'     => '#7289DA',
            'icon'      => config('other.font-awesome').' fa-user',
            'autogroup' => 1,
            'level'     => 30,
        ]);

        //4
        Group::create([
            'name'       => 'Administrator',
            'slug'       => 'administrator',
            'position'   => 18,
            'color'      => '#f92672',
            'icon'       => config('other.font-awesome').' fa-user-secret',
            'is_admin'   => 1,
            'is_modo'    => 1,
            'is_trusted' => 1,
            'is_immune'  => 1,
            'level'      => 5000,
        ]);

        //5
        Group::create([
            'name'       => 'Banned',
            'slug'       => 'banned',
            'position'   => 1,
            'color'      => 'red',
            'icon'       => config('other.font-awesome').' fa-ban',
            'can_upload' => 0,
            'level'      => 0,
        ]);

        //6
        Group::create([
            'name'       => 'Moderator',
            'slug'       => 'moderator',
            'position'   => 17,
            'color'      => '#4ECDC4',
            'icon'       => config('other.font-awesome').' fa-user-secret',
            'is_modo'    => 1,
            'is_trusted' => 1,
            'is_immune'  => 1,
            'level'      => 2500,
        ]);

        //7
        Group::create([
            'name'         => 'Uploader',
            'slug'         => 'uploader',
            'position'     => 15,
            'color'        => '#2ECC71',
            'icon'         => config('other.font-awesome').' fa-upload',
            'is_trusted'   => 1,
            'is_immune'    => 1,
            'is_freeleech' => 1,
            'level'        => 250,
        ]);

        //8
        Group::create([
            'name'         => 'Trustee',
            'slug'         => 'trustee',
            'position'     => 16,
            'color'        => '#BF55EC',
            'icon'         => config('other.font-awesome').' fa-shield',
            'is_trusted'   => 1,
            'is_immune'    => 1,
            'is_freeleech' => 1,
            'level'        => 1000,
        ]);

        //9
        Group::create([
            'name'       => 'Bot',
            'slug'       => 'bot',
            'position'   => 20,
            'color'      => '#f1c40f',
            'icon'       => 'fab fa-android',
            'is_modo'    => 1,
            'is_trusted' => 1,
            'is_immune'  => 1,
            'level'      => 0,
        ]);

        //10
        Group::create([
            'name'       => 'Owner',
            'slug'       => 'owner',
            'position'   => 19,
            'color'      => '#00abff',
            'icon'       => config('other.font-awesome').' fa-user-secret',
            'is_owner'   => 1,
            'is_admin'   => 1,
            'is_modo'    => 1,
            'is_trusted' => 1,
            'is_immune'  => 1,
            'level'      => 9999,
        ]);

        //11
        Group::create([
            'name'      => 'PowerUser',
            'slug'      => 'poweruser',
            'position'  => 7,
            'color'     => '#3c78d8',
            'icon'      => config('other.font-awesome').' fa-user-circle',
            'autogroup' => 1,
            'level'     => 40,
        ]);

        //12
        Group::create([
            'name'      => 'SuperUser',
            'slug'      => 'superuser',
            'position'  => 8,
            'color'     => '#1155cc',
            'icon'      => config('other.font-awesome').' fa-power-off',
            'autogroup' => 1,
            'level'     => 50,
        ]);

        //13
        Group::create([
            'name'       => 'ExtremeUser',
            'slug'       => 'extremeuser',
            'position'   => 9,
            'color'      => '#1c4587',
            'icon'       => config('other.font-awesome').' fa-bolt',
            'is_trusted' => 1,
            'autogroup'  => 1,
            'level'      => 60,
        ]);

        //14
        Group::create([
            'name'       => 'InsaneUser',
            'slug'       => 'insaneuser',
            'position'   => 10,
            'color'      => '#1c4587',
            'icon'       => config('other.font-awesome').' fa-rocket',
            'is_trusted' => 1,
            'autogroup'  => 1,
            'level'      => 70,
        ]);

        //15
        Group::create([
            'name'      => 'Leech',
            'slug'      => 'leech',
            'position'  => 5,
            'color'     => '#96281B',
            'icon'      => config('other.font-awesome').' fa-times',
            'autogroup' => 1,
            'level'     => 20,
        ]);

        //16
        Group::create([
            'name'         => 'Veteran',
            'slug'         => 'veteran',
            'position'     => 11,
            'color'        => '#1c4587',
            'icon'         => config('other.font-awesome').' fa-key',
            'effect'       => 'url(/img/sparkels.gif)',
            'is_trusted'   => 1,
            'is_immune'    => 1,
            'is_freeleech' => 1,
            'autogroup'    => 1,
            'level'        => 100,
        ]);

        //17
        Group::create([
            'name'       => 'Seeder',
            'slug'       => 'seeder',
            'position'   => 12,
            'color'      => '#1c4587',
            'icon'       => config('other.font-awesome').' fa-hdd',
            'is_trusted' => 1,
            'is_immune'  => 1,
            'autogroup'  => 1,
            'level'      => 80,
        ]);

        //18
        Group::create([
            'name'         => 'Archivist',
            'slug'         => 'archivist',
            'position'     => 13,
            'color'        => '#1c4587',
            'icon'         => config('other.font-awesome').' fa-server',
            'effect'       => 'url(/img/sparkels.gif)',
            'is_trusted'   => 1,
            'is_immune'    => 1,
            'is_freeleech' => 1,
            'autogroup'    => 1,
            'level'        => 90,
        ]);

        //19
        Group::create([
            'name'         => 'Internal',
            'slug'         => 'internal',
            'position'     => 14,
            'color'        => '#BAAF92',
            'icon'         => config('other.font-awesome').' fa-magic',
            'is_trusted'   => 1,
            'is_immune'    => 1,
            'is_freeleech' => 1,
            'is_internal'  => 1,
            'level'        => 500,
        ]);

        //20
        Group::create([
            'name'       => 'Disabled',
            'slug'       => 'disabled',
            'position'   => 2,
            'color'      => '#8D6262',
            'icon'       => config('other.font-awesome').' fa-pause-circle',
            'can_upload' => 0,
            'level'      => 0,
        ]);

        //21
        Group::create([
            'name'       => 'Pruned',
            'slug'       => 'pruned',
            'position'   => 0,
            'color'      => '#8D6262',
            'icon'       => config('other.font-awesome').' fa-times-circle',
            'can_upload' => 0,
            'level'      => 0,
        ]);
    }
}
