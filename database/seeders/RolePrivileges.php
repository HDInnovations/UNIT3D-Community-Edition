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
 * @credits    clandestine8 <https://github.com/clandestine8>
 */

namespace Database\Seeders;

use App\Models\Privilege;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePrivileges extends Seeder
{
    public function __construct()
    {
        $this->map = [
            'sudo' => Privilege::all(),
            'root' => Privilege::all(),
            'user' => Privilege::whereIn('slug', ['torrent_can_view',
                'torrent_can_create', 'torrent_can_download', 'request_can_view',
                'request_can_create', 'comment_can_view', 'comment_can_create',
                'forum_can_view', 'playlist_can_view', 'playlist_can_create', ]),
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_privilege')->truncate();
        foreach ($this->map as $role => $privileges) {
            $R = Role::where('slug', '=', $role)->first();
            foreach ($privileges as $privilege) {
                $R->privileges()->attach($privilege);
            }
        }
    }
}
