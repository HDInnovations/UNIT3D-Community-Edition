<?php

namespace App\Traits;

use App\Models\Privilege;
use App\Models\Role;

trait HasPrivileges
{
    /**
     * @param mixed ...$privileges
     *
     * @return $this
     */
    public function givePrivilegesTo(...$privileges)
    {
        $privileges = $this->getAllPrivileges($privileges);
        if ($privileges === null) {
            return $this;
        }
        $this->privileges->saveMany($privileges);

        return $this;
    }

    /**
     * @param mixed ...$privileges
     *
     * @return $this
     */
    public function withdrawPrivilegesTo(...$privileges)
    {
        $privileges = $this->getAllPrivileges($privileges);
        $this->privileges->detach($privileges);

        return $this;
    }

    /**
     * @param mixed ...$privileges
     *
     * @return \App\Traits\HasPrivileges
     */
    public function refreshPrivileges(...$privileges)
    {
        $this->privileges->detach();

        return $this->givePrivilegesTo($privileges);
    }

    /**
     * @param $privilege
     *
     * @return bool
     */
    public function hasPrivilegeTo($privilege)
    {
        $perm = Privilege::where('slug', $privilege)->first();

        return $this->hasPrivilegeThroughRole($perm) || $this->hasPermission($perm);
    }

    /**
     * @param $privilege
     *
     * @return bool
     */
    public function hasPrivilegeThroughRole($privilege)
    {
        foreach ($privilege->roles as $role) {
            if ($this->roles->contains($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed ...$roles
     *
     * @return bool
     */
    public function hasRole(...$roles)
    {
        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'users_roles', 'user_id', 'role_id');
    }

    /**
     * @return mixed
     */
    public function privileges()
    {
        return $this->belongsToMany(Privilege::class, 'users_privileges', 'user_id', 'permission_id');
    }

    /**
     * @param $privilege
     *
     * @return bool
     */
    protected function hasPrivilege($privilege)
    {
        return (bool) $this->privileges->where('slug', $privilege->slug)->count();
    }

    /**
     * @param array $privileges
     *
     * @return mixed
     */
    protected function getAllPrivileges(array $privileges)
    {
        return Privilege::whereIn('slug', $privileges)->get();
    }
}
