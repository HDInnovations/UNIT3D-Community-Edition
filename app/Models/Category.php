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
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Auditable;
    use HasFactory;

    /**
     * Indicates If The Model Should Be Timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The Attributes That Should Be Mutated To Dates.
     *
     * @var array<string, string>
     */
    public $casts = [
        'music_meta' => 'boolean',
        'game_meta'  => 'boolean',
        'tv_meta'    => 'boolean',
        'movie_meta' => 'boolean',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * Has Many Torrents.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Torrent>
     */
    public function torrents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Torrent::class);
    }

    /**
     * Has Many Requests.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<TorrentRequest>
     */
    public function requests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TorrentRequest::class);
    }
}
