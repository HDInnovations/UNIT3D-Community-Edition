<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Role.
 *
 * @property int                             $id
 * @property int                             $position
 * @property string                          $name
 * @property string                          $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Role extends Model
{
    use Auditable;

    public $guarded = [];

    /**
     * Has many permission_role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Permission>
     */
    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->using(PermissionRole::class)->withTimestamps()->withPivot('id');
    }

    /**
     * Has many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<User>
     */
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class)->using(RoleUser::class)->withTimestamps()->withPivot('id');
    }

    /**
     * Has many groups.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Group>
     */
    public function groups(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Group::class)->using(GroupRole::class)->withTimestamps()->withPivot('id');
    }
}
