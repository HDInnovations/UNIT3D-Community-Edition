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

use App\Models\Role;
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
     */
    public function run(): void
    {
        foreach ($this->users as $user) {
            $new = User::updateOrCreate($user);
            $new->roles()->attach(Role::where('id', '=', $user['role_id'])->get());
        }
    }

    private function getUsers(): array
    {
        return [
            [
                'username'  => 'System',
                'email'     => config('unit3d.default-owner-email'),
                'role_id'   => 3,
                'password'  => \Hash::make(config('unit3d.default-owner-password')),
                'passkey'   => md5(random_bytes(60)),
                'rsskey'    => md5(random_bytes(60)),
                'api_token' => Str::random(100),
                'active'    => 1,
            ],
            [
                'username'  => 'Bot',
                'email'     => config('unit3d.default-owner-email'),
                'role_id'   => 3,
                'password'  => \Hash::make(config('unit3d.default-owner-password')),
                'passkey'   => md5(random_bytes(60)),
                'rsskey'    => md5(random_bytes(60)),
                'api_token' => Str::random(100),
                'active'    => 1,
            ],
            [
                'username'  => config('unit3d.owner-username'),
                'email'     => config('unit3d.default-owner-email'),
                'role_id'   => 2,
                'password'  => \Hash::make(config('unit3d.default-owner-password')),
                'passkey'   => md5(random_bytes(60)),
                'rsskey'    => md5(random_bytes(60)),
                'api_token' => Str::random(100),
                'active'    => 1,
            ],
        ];
    }
}
