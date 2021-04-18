<?php

namespace App\Traits;

use App\Models\Privilege;
use Illuminate\Support\Facades\DB;

trait HasPrivilege
{
    /**
     * @param $privilege
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function hasPrivilegeTo($privilege)
    {
        $ttl = new \DateInterval('PT60S');

        return (bool) \cache()->remember('priv-'.$this->id.'-'.$privilege, $ttl,
            function () use ($privilege) {
                return DB::select('SELECT UserHasPrivilegeTo('.$this->id.', \''.$privilege.'\') AS result')[0]->result;
            });
    }

    /**
     * @return mixed
     */
    public function privileges()
    {
        return $this->belongsToMany(Privilege::class, 'user_privilege', 'user_id', 'privilege_id');
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
