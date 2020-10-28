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

class Season extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $table = 'seasons';

    /**
     * Has Many Torrents.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function torrents()
    {
        return $this->hasMany(Torrent::class, 'tmdb', 'tv_id');
    }

    public function tv()
    {
        return $this->belongsTo(Tv::class);
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class)
            ->orderBy('episode_number');
    }

    public function person()
    {
        return $this->belongsToMany(Person::class);
    }

    public function cast()
    {
        return $this->belongsToMany(Cast::class);
    }

    public function crew()
    {
        return $this->belongsToMany(Crew::class);
    }
}
