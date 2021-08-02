<?php

namespace App\Traits;

use App\Models\Privilege;
use Illuminate\Support\Facades\DB;

trait HasPrivilege
{
    public function hasPrivilegeTo($privilege): bool
    {
        $ttl = new \DateInterval('PT60S');

        return (bool) \cache()->remember('priv-'.$this->id.'-'.$privilege, $ttl,
            function () use ($privilege) {
                return DB::select('SELECT UserHasPrivilegeTo('.$this->id.', \''.$privilege.'\') AS result')[0]->result;
            });
    }

    public function privileges()
    {
        return $this->belongsToMany(Privilege::class, 'user_privilege', 'user_id', 'privilege_id');
    }

    public function UserRestrictedPrivileges()
    {
        return $this->belongsToMany(Privilege::class, 'user_restricted_privilege', 'user_id', 'privilege_id');
    }

    protected function getAllPrivileges(array $privileges)
    {
        return Privilege::whereIn('slug', $privileges)->get();
    }

    public function hasPrivilegeThroughRole(Privilege $privilege): bool
    {
        foreach ($privilege->roles as $role) {
            if ($this->roles->contains($role)) {
                return true;
            }
        }

        return false;
    }
}
