<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{
    use HasFactory;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_privilege', 'privilege_id', 'role_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_privilege', 'privilege_id', 'user_id');
    }

    public static  function usersWith($slug) {
        $ByPrivilege = Privilege::where('slug','=',$slug)->first()->users()->get();
        $ByRole = false;
        foreach (Privilege::where('slug','=',$slug)->first()->roles()->get() as $role) {
            if (!$ByRole) {
                $ByRole = $role->users()->get();
            } else {
                $ByRole = $ByRole->merge($role->users()->get());
            }
        }
        return $ByPrivilege->merge($ByRole)->unique();
    }
}
