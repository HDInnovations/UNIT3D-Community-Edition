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

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WikiCategory.
 *
 * @property int    $id
 * @property string $name
 * @property string $icon
 * @property int    $position
 */
class WikiCategory extends Model
{
    /**
     * Indicates If The Model Should Be Timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $guarded = [];

    /**
     * Has Many Wikis.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Wiki>
     */
    public function wikis(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Wiki::class, 'category_id');
    }
}
