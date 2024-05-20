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
use Illuminate\Database\Eloquent\Model;

/**
 * @property int                             $id
 * @property int                             $position
 * @property string|null                     $name_regex
 * @property int|null                        $size
 * @property int|null                        $category_id
 * @property int|null                        $type_id
 * @property int|null                        $resolution_id
 * @property int                             $freeleech_percentage
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Category|null $category
 * @property-read Resolution|null $resolution
 * @property-read Type|null $type
 * @method static \Illuminate\Database\Eloquent\Builder|AutomaticTorrentFreeleech newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AutomaticTorrentFreeleech newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AutomaticTorrentFreeleech query()
 * @mixin \Eloquent
 */
class AutomaticTorrentFreeleech extends Model
{
    use Auditable;

    protected $guarded = [];

    /**
     * Belongs To A Category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Category, self>
     */
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Belongs To A Type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Type, self>
     */
    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Belongs To A Resolution.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Resolution, self>
     */
    public function resolution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Resolution::class);
    }
}
