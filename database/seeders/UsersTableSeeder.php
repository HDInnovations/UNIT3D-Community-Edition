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

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    private $users;

    public function __construct()
    {
        $this->users = $this->getUsers();
    }

    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->users as $user) {
            User::updateOrCreate($user);
        }
    }

    private function getUsers()
    {
        return [
            [
                'username'  => 'System',
                'email'     => config('unit3d.default-owner-email'),
                'group_id'  => 9,
                'password'  => \Hash::make(config('unit3d.default-owner-password')),
                'passkey'   => md5(uniqid().time().microtime()),
                'rsskey'    => md5(uniqid().time()),
                'api_token' => Str::random(100),
                'active'    => 1,
            ],
            [
                'username'  => 'Bot',
                'email'     => config('unit3d.default-owner-email'),
                'group_id'  => 9,
                'password'  => \Hash::make(config('unit3d.default-owner-password')),
                'passkey'   => md5(uniqid().time().microtime()),
                'rsskey'    => md5(uniqid().time()),
                'api_token' => Str::random(100),
                'active'    => 1,
            ],
            [
                'username'  => config('unit3d.owner-username'),
                'email'     => config('unit3d.default-owner-email'),
                'group_id'  => 10,
                'password'  => \Hash::make(config('unit3d.default-owner-password')),
                'passkey'   => md5(uniqid().time().microtime()),
                'rsskey'    => md5(uniqid().time()),
                'api_token' => Str::random(100),
                'active'    => 1,
            ],
        ];
    }
}
