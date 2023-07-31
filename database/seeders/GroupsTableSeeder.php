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

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    public function run(): void
    {
        Group::upsert([
            [
                'name'         => 'Validating',
                'slug'         => 'validating',
                'position'     => 4,
                'color'        => '#95A5A6',
                'icon'         => config('other.font-awesome').' fa-question-circle',
                'effect'       => 'none',
                'autogroup'    => 0,
                'is_owner'     => 0,
                'is_admin'     => 0,
                'is_modo'      => 0,
                'is_internal'  => 0,
                'is_trusted'   => 0,
                'is_freeleech' => 0,
                'is_immune'    => 0,
                'can_upload'   => 0,
                'can_chat'     => 0,
                'can_comment'  => 0,
                'level'        => 0,
            ],
            [
                'name'         => 'Guest',
                'slug'         => 'guest',
                'position'     => 3,
                'color'        => '#575757',
                'icon'         => config('other.font-awesome').' fa-question-circle',
                'effect'       => 'none',
                'autogroup'    => 0,
                'is_owner'     => 0,
                'is_admin'     => 0,
                'is_modo'      => 0,
                'is_internal'  => 0,
                'is_trusted'   => 0,
                'is_freeleech' => 0,
                'is_immune'    => 0,
                'can_upload'   => 0,
                'can_chat'     => 0,
                'can_comment'  => 0,
                'level'        => 10,
            ],
            [
                'name'         => 'User',
                'slug'         => 'user',
                'position'     => 6,
                'color'        => '#7289DA',
                'icon'         => config('other.font-awesome').' fa-user',
                'effect'       => 'none',
                'autogroup'    => 1,
                'is_owner'     => 0,
                'is_admin'     => 0,
                'is_modo'      => 0,
                'is_internal'  => 0,
                'is_trusted'   => 0,
                'is_freeleech' => 0,
                'is_immune'    => 0,
                'can_upload'   => 1,
                'can_chat'     => 1,
                'can_comment'  => 1,
                'level'        => 30,
            ],
            [
                'name'         => 'Administrator',
                'slug'         => 'administrator',
                'position'     => 18,
                'color'        => '#f92672',
                'icon'         => config('other.font-awesome').' fa-user-secret',
                'effect'       => 'none',
                'autogroup'    => 0,
                'is_owner'     => 0,
                'is_admin'     => 1,
                'is_modo'      => 1,
                'is_internal'  => 0,
                'is_trusted'   => 1,
                'is_freeleech' => 0,
                'is_immune'    => 1,
                'can_upload'   => 1,
                'can_chat'     => 1,
                'can_comment'  => 1,
                'level'        => 5000,
            ],
            [
                'name'         => 'Banned',
                'slug'         => 'banned',
                'position'     => 1,
                'color'        => 'red',
                'icon'         => config('other.font-awesome').' fa-ban',
                'effect'       => 'none',
                'autogroup'    => 0,
                'is_owner'     => 0,
                'is_admin'     => 0,
                'is_modo'      => 0,
                'is_internal'  => 0,
                'is_trusted'   => 0,
                'is_freeleech' => 0,
                'is_immune'    => 0,
                'can_upload'   => 0,
                'can_chat'     => 0,
                'can_comment'  => 0,
                'level'        => 0,
            ],
            [
                'name'         => 'Moderator',
                'slug'         => 'moderator',
                'position'     => 17,
                'color'        => '#4ECDC4',
                'icon'         => config('other.font-awesome').' fa-user-secret',
                'effect'       => 'none',
                'autogroup'    => 0,
                'is_owner'     => 0,
                'is_admin'     => 0,
                'is_modo'      => 1,
                'is_internal'  => 0,
                'is_trusted'   => 1,
                'is_freeleech' => 0,
                'is_immune'    => 1,
                'can_upload'   => 1,
                'can_chat'     => 1,
                'can_comment'  => 1,
                'level'        => 2500,
            ],
            [
                'name'         => 'Uploader',
                'slug'         => 'uploader',
                'position'     => 15,
                'color'        => '#2ECC71',
                'icon'         => config('other.font-awesome').' fa-upload',
                'effect'       => 'none',
                'autogroup'    => 0,
                'is_owner'     => 0,
                'is_admin'     => 0,
                'is_modo'      => 0,
                'is_internal'  => 0,
                'is_trusted'   => 1,
                'is_freeleech' => 1,
                'is_immune'    => 1,
                'can_upload'   => 1,
                'can_chat'     => 1,
                'can_comment'  => 1,
                'level'        => 250,
            ],
            [
                'name'         => 'Trustee',
                'slug'         => 'trustee',
                'position'     => 16,
                'color'        => '#BF55EC',
                'icon'         => config('other.font-awesome').' fa-shield',
                'effect'       => 'none',
                'autogroup'    => 0,
                'is_owner'     => 0,
                'is_admin'     => 0,
                'is_modo'      => 0,
                'is_internal'  => 0,
                'is_trusted'   => 1,
                'is_freeleech' => 1,
                'is_immune'    => 1,
                'can_upload'   => 1,
                'can_chat'     => 1,
                'can_comment'  => 1,
                'level'        => 1000,
            ],
            [
                'name'         => 'Bot',
                'slug'         => 'bot',
                'position'     => 20,
                'color'        => '#f1c40f',
                'icon'         => 'fab fa-android',
                'effect'       => 'none',
                'autogroup'    => 0,
                'is_owner'     => 0,
                'is_admin'     => 0,
                'is_modo'      => 1,
                'is_internal'  => 0,
                'is_trusted'   => 1,
                'is_freeleech' => 0,
                'is_immune'    => 1,
                'can_upload'   => 1,
                'can_chat'     => 1,
                'can_comment'  => 1,
                'level'        => 0,
            ],
            [
                'name'         => 'Owner',
                'slug'         => 'owner',
                'position'     => 19,
                'color'        => '#00abff',
                'icon'         => config('other.font-awesome').' fa-user-secret',
                'effect'       => 'none',
                'autogroup'    => 0,
                'is_owner'     => 1,
                'is_admin'     => 1,
                'is_modo'      => 1,
                'is_internal'  => 0,
                'is_trusted'   => 1,
                'is_freeleech' => 0,
                'is_immune'    => 1,
                'can_upload'   => 1,
                'can_chat'     => 1,
                'can_comment'  => 1,
                'level'        => 9999,
            ],
            [
                'name'         => 'PowerUser',
                'slug'         => 'poweruser',
                'position'     => 7,
                'color'        => '#3c78d8',
                'icon'         => config('other.font-awesome').' fa-user-circle',
                'effect'       => 'none',
                'autogroup'    => 1,
                'is_owner'     => 0,
                'is_admin'     => 0,
                'is_modo'      => 0,
                'is_internal'  => 0,
                'is_trusted'   => 0,
                'is_freeleech' => 0,
                'is_immune'    => 0,
                'can_upload'   => 1,
                'can_chat'     => 1,
                'can_comment'  => 1,
                'level'        => 40,
            ],
            [
                'name'         => 'SuperUser',
                'slug'         => 'superuser',
                'position'     => 8,
                'color'        => '#1155cc',
                'icon'         => config('other.font-awesome').' fa-power-off',
                'effect'       => 'none',
                'autogroup'    => 1,
                'is_owner'     => 0,
                'is_admin'     => 0,
                'is_modo'      => 0,
                'is_internal'  => 0,
                'is_trusted'   => 0,
                'is_freeleech' => 0,
                'is_immune'    => 0,
                'can_upload'   => 1,
                'can_chat'     => 1,
                'can_comment'  => 1,
                'level'        => 50,
            ],
            [
                'name'         => 'ExtremeUser',
                'slug'         => 'extremeuser',
                'position'     => 9,
                'color'        => '#1c4587',
                'icon'         => config('other.font-awesome').' fa-bolt',
                'effect'       => 'none',
                'autogroup'    => 1,
                'is_owner'     => 0,
                'is_admin'     => 0,
                'is_modo'      => 0,
                'is_internal'  => 0,
                'is_trusted'   => 1,
                'is_freeleech' => 0,
                'is_immune'    => 0,
                'can_upload'   => 1,
                'can_chat'     => 1,
                'can_comment'  => 1,
                'level'        => 60,
            ],
            [
                'name'         => 'InsaneUser',
                'slug'         => 'insaneuser',
                'position'     => 10,
                'color'        => '#1c4587',
                'icon'         => config('other.font-awesome').' fa-rocket',
                'effect'       => 'none',
                'autogroup'    => 1,
                'is_owner'     => 0,
                'is_admin'     => 0,
                'is_modo'      => 0,
                'is_internal'  => 0,
                'is_trusted'   => 1,
                'is_freeleech' => 0,
                'is_immune'    => 0,
                'can_upload'   => 1,
                'can_chat'     => 1,
                'can_comment'  => 1,
                'level'        => 70,
            ],
            [
                'name'         => 'Leech',
                'slug'         => 'leech',
                'position'     => 5,
                'color'        => '#96281B',
                'icon'         => config('other.font-awesome').' fa-times',
                'effect'       => 'none',
                'autogroup'    => 1,
                'is_owner'     => 0,
                'is_admin'     => 0,
                'is_modo'      => 0,
                'is_internal'  => 0,
                'is_trusted'   => 0,
                'is_freeleech' => 0,
                'is_immune'    => 0,
                'can_upload'   => 1,
                'can_chat'     => 1,
                'can_comment'  => 1,
                'level'        => 20,
            ],
            [
                'name'         => 'Veteran',
                'slug'         => 'veteran',
                'position'     => 11,
                'color'        => '#1c4587',
                'icon'         => config('other.font-awesome').' fa-key',
                'effect'       => 'url(/img/sparkels.gif)',
                'autogroup'    => 1,
                'is_owner'     => 0,
                'is_admin'     => 0,
                'is_modo'      => 0,
                'is_internal'  => 0,
                'is_trusted'   => 0,
                'is_freeleech' => 1,
                'is_immune'    => 0,
                'can_upload'   => 1,
                'can_chat'     => 1,
                'can_comment'  => 1,
                'level'        => 100,
            ],
            [
                'name'         => 'Seeder',
                'slug'         => 'seeder',
                'position'     => 12,
                'color'        => '#1c4587',
                'icon'         => config('other.font-awesome').' fa-hdd',
                'effect'       => 'none',
                'autogroup'    => 1,
                'is_owner'     => 0,
                'is_admin'     => 0,
                'is_modo'      => 0,
                'is_internal'  => 0,
                'is_trusted'   => 1,
                'is_freeleech' => 0,
                'is_immune'    => 1,
                'can_upload'   => 1,
                'can_chat'     => 1,
                'can_comment'  => 1,
                'level'        => 80,
            ],
            [
                'name'         => 'Archivist',
                'slug'         => 'archivist',
                'position'     => 13,
                'color'        => '#1c4587',
                'icon'         => config('other.font-awesome').' fa-server',
                'effect'       => 'url(/img/sparkels.gif)',
                'autogroup'    => 1,
                'is_owner'     => 0,
                'is_admin'     => 0,
                'is_modo'      => 0,
                'is_internal'  => 0,
                'is_trusted'   => 1,
                'is_freeleech' => 1,
                'is_immune'    => 1,
                'can_upload'   => 1,
                'can_chat'     => 1,
                'can_comment'  => 1,
                'level'        => 90,
            ],
            [
                'name'         => 'Internal',
                'slug'         => 'internal',
                'position'     => 14,
                'color'        => '#BAAF92',
                'icon'         => config('other.font-awesome').' fa-magic',
                'effect'       => 'none',
                'autogroup'    => 0,
                'is_owner'     => 0,
                'is_admin'     => 0,
                'is_modo'      => 0,
                'is_internal'  => 1,
                'is_trusted'   => 1,
                'is_freeleech' => 1,
                'is_immune'    => 1,
                'can_upload'   => 1,
                'can_chat'     => 1,
                'can_comment'  => 1,
                'level'        => 500,
            ],
            [
                'name'         => 'Disabled',
                'slug'         => 'disabled',
                'position'     => 2,
                'color'        => '#8D6262',
                'icon'         => config('other.font-awesome').' fa-pause-circle',
                'effect'       => 'none',
                'autogroup'    => 0,
                'is_owner'     => 0,
                'is_admin'     => 0,
                'is_modo'      => 0,
                'is_internal'  => 0,
                'is_trusted'   => 0,
                'is_freeleech' => 0,
                'is_immune'    => 0,
                'can_upload'   => 0,
                'can_chat'     => 0,
                'can_comment'  => 0,
                'level'        => 0,
            ],
            [
                'name'         => 'Pruned',
                'slug'         => 'pruned',
                'position'     => 0,
                'color'        => '#8D6262',
                'icon'         => config('other.font-awesome').' fa-times-circle',
                'effect'       => 'none',
                'autogroup'    => 0,
                'is_owner'     => 0,
                'is_admin'     => 0,
                'is_modo'      => 0,
                'is_internal'  => 0,
                'is_trusted'   => 0,
                'is_freeleech' => 0,
                'is_immune'    => 0,
                'can_upload'   => 0,
                'can_chat'     => 0,
                'can_comment'  => 0,
                'level'        => 0,
            ],
        ], ['slug']);
    }
}
