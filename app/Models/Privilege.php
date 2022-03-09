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

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'name',
    ];

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

    public static function usersWith($slug)
    {
        $ByPrivilege = self::where('slug', '=', $slug)->first()->users()->get();
        $ByRole = false;
        foreach (self::where('slug', '=', $slug)->first()->roles()->get() as $role) {
            if (! $ByRole) {
                $ByRole = $role->users()->get();
            } else {
                $ByRole = $ByRole->merge($role->users()->get());
            }
        }

        return $ByPrivilege->merge($ByRole)->unique();
    }

    public function RestrictedRoles()
    {
        return $this->belongsToMany(Role::class, 'role_restricted_privilege', 'privilege_id', 'role_id');
    }
}
