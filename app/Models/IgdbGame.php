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
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\IgdbGame.
 *
 * @property int                         $id
 * @property string                      $name
 * @property ?string                     $summary
 * @property ?string                     $first_artwork_image_id
 * @property ?\Illuminate\Support\Carbon $first_release_date
 * @property ?string                     $cover_image_id
 * @property ?string                     $url
 * @property ?float                      $rating
 * @property ?int                        $rating_count
 * @property ?string                     $first_video_video_id
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class IgdbGame extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array{first_release_date: 'datetime'}
     */
    protected function casts(): array
    {
        return [
            'first_release_date' => 'datetime',
        ];
    }

    /**
     * Belongs to many platforms.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<IgdbPlatform, $this>
     */
    public function platforms(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(IgdbPlatform::class);
    }

    /**
     * Belongs to many companies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<IgdbCompany, $this>
     */
    public function companies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(IgdbCompany::class);
    }

    /**
     * Belongs to many genres.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<IgdbGenre, $this>
     */
    public function genres(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(IgdbGenre::class);
    }
}
