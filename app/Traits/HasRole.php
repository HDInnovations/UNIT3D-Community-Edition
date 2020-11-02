<?php

namespace App\Traits;

use App\Models\Role;

trait HasRole
{
    /**
     * @param mixed ...$roles
     *
     * @return bool
     */
    public function hasRole(...$roles)
    {
        foreach ($roles as $role) {
            if ($this->primaryRole()->contains('slug', $role)) {
                return true;
            }
            if ($this->additionalRoles()->contains('slug', $role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function primaryRole()
    {
        return $this->hasOne(Role::class, 'id', 'primary_role_id');
    }

    /**
     * @return mixed
     */
    public function additionalRoles()
    {
        return $this->belongsToMany(Role::class, 'users_roles', 'user_id', 'role_id');
    }
}
