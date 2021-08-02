<?php

namespace App\Traits;

use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasRole
{
    /**
     * Check If A User Has One Or Many Roles.
     *
     * @param $roles
     *
     * @return bool
     */
    public function hasRole(array | string $roles): bool
    {
        if (is_array($roles)) {
            $count = $this->roles()->whereIn('slug', $roles)->count();
            if ($count > 0) {
                return true;
            }
        }
        if ($this->roles()->where('slug', $roles)->count() > 0) {
            return true;
        }

        return false;
    }

    /**
     * Check A Users Primary Role.
     */
    public function primaryRole(): HasOne
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
