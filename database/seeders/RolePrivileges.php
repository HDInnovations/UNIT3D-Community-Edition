<?php

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
                'forum_can_view', 'playlist_can_view', 'playlist_can_create'])
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
