<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RBACRoles extends Model
{
    use HasFactory;

    protected $table = 'RBACroles';

    public function permissions()
    {
        return $this->belongsToMany(RBACPermissions::class, 'RBACroles_permissions', 'role_id', 'permission_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'RBACusers_roles', 'role_id', 'user_id');
    }
}
