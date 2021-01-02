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
            ['slug' => 'sudo', 'name' => 'System Operator', 'position' => 999],
            ['slug' => 'root', 'name' => 'Owner', 'position' => 998],
            ['slug' => 'bot', 'name' => 'Bot', 'position' => 997],
            ['slug' => 'pruned', 'name' => 'Pruned', 'position' => 001],
            ['slug' => 'disabled', 'name' => 'Disabled', 'position' => 002],
            ['slug' => 'validating', 'name' => 'Validating', 'position' => 003],
            ['slug' => 'banned', 'name' => 'Banned', 'position' => 004],
            ['slug' => 'user', 'name' => 'User', 'position' => 100],
            ['slug' => 'group_1', 'name' => 'New User', 'position' =>101],
            ['slug' => 'group_2', 'name' => 'Power User', 'position' =>102],
            ['slug' => 'group_3', 'name' => 'Super User', 'position' =>103],
            ['slug' => 'group_4', 'name' => 'Extreme User', 'position' =>104],
            ['slug' => 'group_5', 'name' => 'Insane User', 'position' =>105],
            ['slug' => 'group_6', 'name' => 'Veteran User', 'position' =>106],
            ['slug' => 'group_7', 'name' => 'Archive User', 'position' =>107],
            ['slug' => 'uploader', 'name' => 'Uploader', 'position' =>801],
            ['slug' => 'recruiter', 'name' => 'Recruiter', 'position' =>802],
            ['slug' => 'trustee', 'name' => 'Trustee', 'position' =>803],
            ['slug' => 'supporter', 'name' => 'Supporter', 'position' =>201],
            ['slug' => 'fls', 'name' => 'FLS', 'position' =>901],
            ['slug' => 'editor', 'name' => 'Editor', 'position' =>900],
            ['slug' => 'internal', 'name' => 'Internal', 'position' => 902],
            ['slug' => 'moderator', 'name' => 'Moderator', 'position' => 903],
            ['slug' => 'admin', 'name' => 'Administrator', 'position' => 904],
            ['slug' => 'coder', 'name' => 'Coder', 'position' => 905],

        ], ['slug'], ['name']);
    }
}
