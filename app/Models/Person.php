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

use App\Enums\Occupations;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public $table = 'person';

    public function tv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    public function createdTv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupations::CREATOR)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    public function directedTv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupations::DIRECTOR)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    public function writtenTv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupations::WRITER)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    public function producedTv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupations::PRODUCER)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    public function composedTv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupations::COMPOSER)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    public function cinematographedTv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupations::CINEMATOGRAPHER)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    public function editedTv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupations::EDITOR)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    public function productionDesignedTv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupations::PRODUCTION_DESIGNER)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    public function artDirectedTv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupations::ART_DIRECTOR)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    public function actedTv(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tv::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupations::ACTOR)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    public function movie(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'credits')
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    public function directedMovies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupations::DIRECTOR)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    public function writtenMovies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupations::WRITER)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    public function producedMovies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupations::PRODUCER)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    public function composedMovies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupations::COMPOSER)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    public function cinematographedMovies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupations::CINEMATOGRAPHER)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    public function editedMovies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupations::EDITOR)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    public function productionDesignedMovies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupations::PRODUCTION_DESIGNER)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    public function artDirectedMovies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupations::ART_DIRECTOR)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }

    public function actedMovies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'credits')
            ->wherePivot('occupation_id', '=', Occupations::ACTOR)
            ->withPivot('character', 'occupation_id')
            ->as('credit');
    }
}
