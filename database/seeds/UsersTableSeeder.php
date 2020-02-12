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

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'username'  => 'System',
                'email'     => 'system@none.com',
                'group_id'  => 9,
                'password'  => \Hash::make(env('DEFAULT_OWNER_PASSWORD')),
                'passkey'   => md5(uniqid().time().microtime()),
                'rsskey'    => md5(uniqid().time()),
                'api_token' => Str::random(100),
                'active'    => 1,
            ],
            [
                'username'  => 'Bot',
                'email'     => 'bot@none.com',
                'group_id'  => 9,
                'password'  => \Hash::make(env('DEFAULT_OWNER_PASSWORD')),
                'passkey'   => md5(uniqid().time().microtime()),
                'rsskey'    => md5(uniqid().time()),
                'api_token' => Str::random(100),
                'active'    => 1,
            ],
            [
                'username'  => env('DEFAULT_OWNER_NAME', 'UNIT3D'),
                'email'     => env('DEFAULT_OWNER_EMAIL', 'none@none.com'),
                'group_id'  => 10,
                'password'  => \Hash::make(env('DEFAULT_OWNER_PASSWORD', 'UNIT3D')),
                'passkey'   => md5(uniqid().time().microtime()),
                'rsskey'    => md5(uniqid().time()),
                'api_token' => Str::random(100),
                'active'    => 1,
            ],
        ];

        foreach ($users as $user) {
            User::create([
                'username'  => $user['username'],
                'email'     => $user['email'],
                'group_id'  => $user['group_id'],
                'password'  => $user['password'],
                'passkey'   => $user['passkey'],
                'rsskey'    => $user['rsskey'],
                'api_token' => $user['api_token'],
                'active'    => $user['active'],
            ]);
        }
    }
}
