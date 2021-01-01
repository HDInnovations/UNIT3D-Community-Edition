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
        Role::upsert([
            ['slug' => 'sudo', 'name' => 'System Operator'],
            ['slug' => 'root', 'name' => 'Owner'],
            ['slug' => 'pruned', 'name' => 'Pruned'],
            ['slug' => 'banned', 'name' => 'Banned'],
            ['slug' => 'disabled', 'name' => 'Disabled'],
            ['slug' => 'validating', 'name' => 'Validating'],
            ['slug' => 'user', 'name' => 'User'],
            ['slug' => 'group_1', 'name' => 'New User'],
            ['slug' => 'group_2', 'name' => 'Power User'],
            ['slug' => 'group_3', 'name' => 'Super User'],
            ['slug' => 'group_4', 'name' => 'Extreme User'],
            ['slug' => 'group_5', 'name' => 'Insane User'],
            ['slug' => 'group_6', 'name' => 'Veteran User'],
            ['slug' => 'group_7', 'name' => 'Archive User'],
            ['slug' => 'uploader', 'name' => 'Uploader'],
            ['slug' => 'recruiter', 'name' => 'Recruiter'],
            ['slug' => 'trustee', 'name' => 'Trustee'],
            ['slug' => 'supporter', 'name' => 'Supporter'],
            ['slug' => 'fls', 'name' => 'FLS'],
            ['slug' => 'editor', 'name' => 'Editor'],
            ['slug' => 'internal', 'name' => 'Internal'],
            ['slug' => 'moderator', 'name' => 'Moderator'],
            ['slug' => 'admin', 'name' => 'Administrator'],
            ['slug' => 'coder', 'name' => 'Coder'],
            ['slug' => 'bot', 'name' => 'Bot'],
        ], ['slug'], ['name']);
    }
}
