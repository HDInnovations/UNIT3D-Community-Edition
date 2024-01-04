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
 */

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use Auditable;
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Indicates If The Model Should Be Timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The Attributes That Should Be Mutated To Dates.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'can_announce' => 'boolean',
        'can_upload'   => 'boolean',
        'can_chat'     => 'boolean',
        'can_comment'  => 'boolean',
        'can_download' => 'boolean',
        'can_request'  => 'boolean',
        'can_invite'   => 'boolean',
    ];

    /**
     * Has Many Users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<User>
     */
    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Has Many Permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Permission>
     */
    public function permissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Permission::class);
    }
}
