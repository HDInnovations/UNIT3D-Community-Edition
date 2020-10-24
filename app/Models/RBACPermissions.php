<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RBACPermissions extends Model
{
    use HasFactory;

    protected $table = 'RBACpermissions';

    public function roles() {

        return $this->belongsToMany(RBACRoles::class,'RBACroles_permissions', 'permission_id', 'role_id');

    }

    public function users() {

        return $this->belongsToMany(User::class,'RBACusers_permissions', 'permission_id', 'user_id');

    }
}
