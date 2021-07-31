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
 * @property int    $id
 * @property string $name
 * @property string $icon
 * @property string $effect
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Internal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Internal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Inernal query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Internal whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Internal whereEffect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Internal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Internal whereName($value)
 * @mixin \Eloquent
 */
class Internal extends Model
{
    use HasFactory;
    use Auditable;

    /**
     * The Attributes That Aren't Mass Assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Indicates If The Model Should Be Timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Has Many Users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
