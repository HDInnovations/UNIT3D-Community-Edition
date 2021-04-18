<?php

namespace App\Traits;

use App\Models\Privilege;
use Illuminate\Support\Facades\DB;

trait HasPrivilege
{

    /**
     * @param $privilege
     *
     * @return bool
     */
    public function hasPrivilegeTo($privilege)
    {

        return (bool)  \cache()->remember('priv-'.$this->id.'-'.$privilege, 60,
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
