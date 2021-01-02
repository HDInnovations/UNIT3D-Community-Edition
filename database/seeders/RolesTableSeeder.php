<?php

namespace Database\Seeders;

use App\Models\Privilege;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Position ID's: 00# = Non-Active User , 1## = Regular Site User with Auto Group, 8## = Special Site Users, 9## = Site Staff Roles
        Role::upsert([
            ['slug' => 'sudo', 'name' => 'System Operator', 'position' => 999, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'root', 'name' => 'Owner', 'position' => 998, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'bot', 'name' => 'Bot', 'position' => 997, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'pruned', 'name' => 'Pruned', 'position' => 001, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'disabled', 'name' => 'Disabled', 'position' => 002, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'validating', 'name' => 'Validating', 'position' => 003, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'banned', 'name' => 'Banned', 'position' => 004, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'user', 'name' => 'User', 'position' => 100, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'group_1', 'name' => 'New User', 'position' =>101, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'group_2', 'name' => 'Power User', 'position' =>102, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'group_3', 'name' => 'Super User', 'position' =>103, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'group_4', 'name' => 'Extreme User', 'position' =>104, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'group_5', 'name' => 'Insane User', 'position' =>105, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'group_6', 'name' => 'Veteran User', 'position' =>106, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'group_7', 'name' => 'Archive User', 'position' =>107, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'uploader', 'name' => 'Uploader', 'position' =>801, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'recruiter', 'name' => 'Recruiter', 'position' =>802, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'trustee', 'name' => 'Trustee', 'position' =>803, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'supporter', 'name' => 'Supporter', 'position' =>201, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'fls', 'name' => 'FLS', 'position' =>901, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'editor', 'name' => 'Editor', 'position' =>900, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'internal', 'name' => 'Internal', 'position' => 902, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'moderator', 'name' => 'Moderator', 'position' => 903, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'admin', 'name' => 'Administrator', 'position' => 904, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],
            ['slug' => 'coder', 'name' => 'Coder', 'position' => 905, 'icon' => config('other.font-awesome').' fa-user-secret', 'color' => '#00abff', 'effect' => 'url(/img/sparkels.gif)', 'system_required' => true],

        ], ['slug'], ['name']);
    }
}
