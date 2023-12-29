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

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected string $orderBy = 'order';

    protected string $orderDirection = 'ASC';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Season, self>
     */
    public function season(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Season::class)
            ->oldest('season_id')
            ->oldest('episode_id');
    }
}
