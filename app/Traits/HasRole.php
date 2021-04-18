<?php

namespace App\Traits;

use App\Models\Role;

trait HasRole
{
    /**
     * Check If A User Has One Or Many Roles.
     *
     * @param $roles
     *
     * @return bool
     */
    public function hasRole(array|string $roles): bool
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->primaryRole->slug === $role) {
                    return true;
                }
                if ($this->additionalRoles->contains('slug', '=', $role)) {
                    return true;
                }
            }
        } else {
            if ($this->primaryRole->slug === $roles) {
                return true;
            }
            if ($this->additionalRoles->contains('slug', '=', $roles)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check A Users Primary Role.
     *
     * @return mixed
     */
    public function primaryRole()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    /**
     * Check A Users Additional Roles.
     *
     * @return mixed
     */
    public function additionalRoles()
    {
        if ($this->primaryRole) {
            return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id')->where('user_id', $this->id)->where('id', '!=', $this->primaryRole->id);
        }
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id')->where('user_id', $this->id);
    }
}
