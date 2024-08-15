<?php

declare(strict_types=1);

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
 * App\Models\Person.
 *
 * @property int         $id
 * @property string      $name
 * @property string|null $imdb_id
 * @property string|null $known_for_department
 * @property string|null $place_of_birth
 * @property string|null $popularity
 * @property string|null $profile
 * @property string|null $still
 * @property string|null $adult
 * @property string|null $also_known_as
 * @property string|null $biography
 * @property string|null $birthday
 * @property string|null $deathday
 * @property string|null $gender
 * @property string|null $homepage
 */
class Person extends Model
{
    /** @use HasFactory<\Database\Factories\PersonFactory> */
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Credit, $this>
     */
    public function credits(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Credit::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Tv, $this>
     */
    public function tv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Tv, $this>
     */
    public function createdTv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupation::CREATOR)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Tv, $this>
     */
    public function directedTv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupation::DIRECTOR)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Tv, $this>
     */
    public function writtenTv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupation::WRITER)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Tv, $this>
     */
    public function producedTv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupation::PRODUCER)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Tv, $this>
     */
    public function composedTv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupation::COMPOSER)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Tv, $this>
     */
    public function cinematographedTv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupation::CINEMATOGRAPHER)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Tv, $this>
     */
    public function editedTv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupation::EDITOR)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Tv, $this>
     */
    public function productionDesignedTv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupation::PRODUCTION_DESIGNER)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Tv, $this>
     */
    public function artDirectedTv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupation::ART_DIRECTOR)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Tv, $this>
     */
    public function actedTv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupation::ACTOR)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Movie, $this>
     */
    public function movie(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'credits')
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Movie, $this>
     */
    public function directedMovies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupation::DIRECTOR)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Movie, $this>
     */
    public function writtenMovies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupation::WRITER)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Movie, $this>
     */
    public function producedMovies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupation::PRODUCER)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Movie, $this>
     */
    public function composedMovies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupation::COMPOSER)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Movie, $this>
     */
    public function cinematographedMovies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupation::CINEMATOGRAPHER)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Movie, $this>
     */
    public function editedMovies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupation::EDITOR)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Movie, $this>
     */
    public function productionDesignedMovies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupation::PRODUCTION_DESIGNER)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Movie, $this>
     */
    public function artDirectedMovies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupation::ART_DIRECTOR)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Movie, $this>
     */
    public function actedMovies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupation::ACTOR)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }
}
