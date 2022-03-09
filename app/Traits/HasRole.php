<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @credits    clandestine8 <https://github.com/clandestine8>
 */

namespace App\Traits;

use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
    public function primaryRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id');
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
