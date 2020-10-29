<?php

namespace Database\Seeders;

use App\Models\RBACPermissions as Permission;
use App\Models\RBACRoles as Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RBACSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create Default Roles or User Groups
        $role = new Role();
        $role->slug = 'owner';
        $role->name = 'owner';
        $role->save();

        //Create Default Permissions
        $perm = new Permission();
        $perm->slug = 'dashboard';
        $perm->name = 'Dashboard';
        $perm->save();

        //Select Default Roles
        $admin = Role::where('slug', 'owner')->first();

        //Add Permissions to Default Roles
        $admin->permissions()->attach($perm);
        $admin->save();

        $owner = User::where('id', 3)->first();
        $owner->roles()->attach($role);
        $owner->save();
    }
}
