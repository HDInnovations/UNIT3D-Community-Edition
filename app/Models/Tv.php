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
use App\Enums\Occupation;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tv.
 *
 * @property int                             $id
 * @property string|null                     $tmdb_id
 * @property string|null                     $imdb_id
 * @property string|null                     $tvdb_id
 * @property string|null                     $type
 * @property string                          $name
 * @property string                          $name_sort
 * @property string|null                     $overview
 * @property int|null                        $number_of_episodes
 * @property int|null                        $count_existing_episodes
 * @property int|null                        $count_total_episodes
 * @property int|null                        $number_of_seasons
 * @property string|null                     $episode_run_time
 * @property string|null                     $first_air_date
 * @property string|null                     $status
 * @property string|null                     $homepage
 * @property int|null                        $in_production
 * @property string|null                     $last_air_date
 * @property string|null                     $next_episode_to_air
 * @property string|null                     $origin_country
 * @property string|null                     $original_language
 * @property string|null                     $original_name
 * @property string|null                     $popularity
 * @property string|null                     $backdrop
 * @property string|null                     $poster
 * @property string|null                     $vote_average
 * @property int|null                        $vote_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null                     $trailer
 */
class Tv extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $table = 'tv';

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Has Many Torrents.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Torrent>
     */
    public function torrents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Torrent::class, 'tmdb', 'id')->whereHas('category', function ($q): void {
            $q->where('tv_meta', '=', true);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Season>
     */
    public function seasons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Season::class)
            ->oldest('season_number');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Person>
     */
    public function people(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'credits');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Credit>
     */
    public function credits(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Credit::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Person>
     */
    public function creators(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupation::CREATOR->value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Genre>
     */
    public function genres(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Network>
     */
    public function networks(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Network::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Company>
     */
    public function companies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Company::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Recommendation>
     */
    public function recommendations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Recommendation::class, 'tv_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Tv>
     */
    public function recommendedTv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, Recommendation::class, 'tv_id', 'recommendation_tv_id', 'id', 'id');
    }
}
