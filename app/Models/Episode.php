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

class Episode extends Model
{
    protected $guarded = [];
    protected $orderBy = 'order';
    protected $orderDirection = 'ASC';
    protected $primaryKey = 'id';
    public $table = 'episodes';

    public function season()
    {
        return $this->belongsTo(Season::class)
            ->orderBy('season_id')
            ->orderBy('episode_id');;
    }

    public function person()
    {
        return $this->belongsToMany(Person::class);
    }

    public function cast()
    {
        return $this->belongsToMany(Cast::class)
            ->orderBy('order');
    }

    public function crew()
    {
        return $this->belongsToMany(Crew::class, 'crew_episode', 'person_id', 'episode_id');
    }

    public function guest_star()
    {
        return $this->belongsToMany(GuestStar::class, 'episode_guest_star', 'person_id', 'episode_id');
    }
}
