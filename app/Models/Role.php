<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'roles';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function privileges()
    {
        return $this->belongsToMany(Privilege::class, 'role_privilege', 'role_id', 'privilege_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role', 'role_id', 'user_id');
    }

    /**
     * Get the Role allowed answer as bool.
     *
     * @param $object
     * @param $groupId
     *
     * @return int
     */
    public function isAllowed($object, $roleId)
    {
        if (\is_array($object) && \is_array($object['default_groups']) && \array_key_exists($roleId, $object['default_groups'])) {
            return $object['default_groups'][$roleId] == 1;
        }

        return true;
    }
}
