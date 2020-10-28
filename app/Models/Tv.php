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

class Tv extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'id';
    public $table = 'tv';
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Has Many Torrents.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function torrents()
    {
        return $this->hasMany(Torrent::class, 'tmdb', 'id');
    }

    public function seasons()
    {
        return $this->hasMany(Season::class)
            ->orderBy('season_number');
    }

    public function persons()
    {
        return $this->belongsToMany(Person::class);
    }

    public function cast()
    {
        return $this->belongsToMany(Cast::class, 'cast_tv', 'cast_id', 'tv_id')
            ->orderBy('tv_id')
            ->take(6);
    }

    public function crew()
    {
        return $this->belongsToMany(Crew::class, 'crew_tv', 'person_id', 'tv_id');
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function creators()
    {
        return $this->belongsToMany(Person::class);
    }

    public function networks()
    {
        return $this->belongsToMany(Network::class);
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }
}
