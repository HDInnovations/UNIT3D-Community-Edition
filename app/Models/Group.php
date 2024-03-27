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

/**
 * App\Models\Group.
 *
 * @property int      $id
 * @property string   $name
 * @property string   $slug
 * @property int      $position
 * @property int      $level
 * @property int|null $download_slots
 * @property string   $description
 * @property string   $color
 * @property string   $icon
 * @property string   $effect
 * @property int      $is_internal
 * @property int      $is_editor
 * @property int      $is_owner
 * @property int      $is_admin
 * @property int      $is_modo
 * @property int      $is_trusted
 * @property int      $is_immune
 * @property int      $is_freeleech
 * @property int      $is_double_upload
 * @property int      $is_refundable
 * @property int      $can_upload
 * @property int      $is_incognito
 * @property int      $autogroup
 * @property bool     $system_required
 * @property int      $min_uploaded
 * @property int      $min_seedsize
 * @property int      $min_avg_seedtime
 * @property float    $min_ratio
 * @property int      $min_age
 */
class Group extends Model
{
    use Auditable;
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'system_required' => 'boolean',
    ];

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<ForumPermission>
     */
    public function permissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ForumPermission::class);
    }
}
