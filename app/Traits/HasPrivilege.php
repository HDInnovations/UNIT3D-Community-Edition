<?php

namespace App\Traits;

use App\Models\Privilege;

trait HasPrivilege
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
        $this->privileges()->attach($privileges);

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
        $this->privileges()->detach($privileges);

        return $this;
    }

    /**
     * @param mixed ...$privileges
     *
     * @return \App\Traits\HasPrivileges
     */
    public function refreshPrivileges(...$privileges)
    {
        $this->privileges()->detach();

        return $this->givePrivilegesTo($privileges);
    }

    /**
     * @param $privilege
     *
     * @return bool
     */
    public function hasPrivilegeTo($privilege)
    {
        $perm = Privilege::where('slug', '=', $privilege)->firstOrFail();

        return $this->hasPrivilegeThroughRole($perm) || $this->hasPrivilege($perm);
    }

    /**
     * @param $privilege
     *
     * @return bool
     */
    public function hasPrivilegeThroughRole($privilege): bool
    {
        foreach ($privilege->roles as $role) {
            if ($this->roles->contains($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function privileges()
    {
        return $this->belongsToMany(Privilege::class, 'user_privilege', 'user_id', 'privilege_id');
    }

    /**
     * @param $privilege
     *
     * @return bool
     */
    protected function hasPrivilege($privilege)
    {
        return (bool) $this->privileges->where('slug', '=', $privilege->slug)->count();
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
