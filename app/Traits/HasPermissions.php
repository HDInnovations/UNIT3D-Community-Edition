<?php
namespace App\Traits;

use App\Models\RBACPermissions;
use App\Models\RBACRoles;

trait HasPermissions {

    public function givePermissionsTo(... $permissions) {

        $permissions = $this->getAllPermissions($permissions);
        if($permissions === null) {
            return $this;
        }
        $this->permissions->saveMany($permissions);
        return $this;
    }

    public function withdrawPermissionsTo( ... $permissions ) {

        $permissions = $this->getAllPermissions($permissions);
        $this->permissions->detach($permissions);
        return $this;

    }

    public function refreshPermissions( ... $permissions ) {

        $this->permissions->detach();
        return $this->givePermissionsTo($permissions);
    }

    public function hasPermissionTo($permission) {

        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }
    public function hasPermissionThroughRole($permission) {

        foreach ($permission->roles as $role){
            if($this->roles->contains($role)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole( ... $roles ) {

        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    public function roles() {

        return $this->belongsToMany(RBACRoles::class,'RBACusers_roles', 'user_id', 'role_id');

    }
    public function permissions() {

        return $this->belongsToMany(RBACPermissions::class,'RBACusers_permissions','user_id', 'permission_id');

    }
    protected function hasPermission($permission) {

        return (bool) $this->permissions->where('slug', $permission->slug)->count();
    }

    protected function getAllPermissions(array $permissions) {

        return RBACPermissions::whereIn('slug',$permissions)->get();

    }

}
