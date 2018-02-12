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

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        $users = [
            [
                'username' => 'System',
                'email' => 'system@none.com',
                'group_id' => 9,
                'password' => \Hash::make(env('DEFAULT_OWNER_PASSWORD')),
                'passkey' => md5(uniqid() . time() . microtime()),
                'active' => 1
            ],
            [
                'username' => 'Bot',
                'email' => 'bot@none.com',
                'group_id' => 9,
                'password' => \Hash::make(env('DEFAULT_OWNER_PASSWORD')),
                'passkey' => md5(uniqid() . time() . microtime()),
                'active' => 1
            ],
            [
                'username' => env('DEFAULT_OWNER_NAME'),
                'email' => env('DEFAULT_OWNER_EMAIL'),
                'group_id' => 10,
                'password' => \Hash::make(env('DEFAULT_OWNER_PASSWORD')),
                'passkey' => md5(uniqid() . time() . microtime()),
                'active' => 1
            ]
        ];

        \DB::table('users')->delete();

        foreach ($users as $user) {
            App\User::create($user);
        }
    }
}
