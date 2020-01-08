<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\PlaylistTorrent.
 *
 * @property int $id
 * @property int|null $position
 * @property int $playlist_id
 * @property int $torrent_id
 * @property int $tmdb_id
 * @property-read \App\Models\Playlist $playlist
 * @property-read \App\Models\Torrent $torrent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlaylistTorrent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlaylistTorrent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlaylistTorrent query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlaylistTorrent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlaylistTorrent wherePlaylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlaylistTorrent wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlaylistTorrent whereTmdbId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlaylistTorrent whereTorrentId($value)
 * @mixin \Eloquent
 */
final class PlaylistTorrent extends Model
{
    use Auditable;

    /**
     * Indicates If The Model Should Be Timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Belongs To A Torrent.
     *
     * @return BelongsToMany
     */
    public function torrent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Torrent::class);
    }

    /**
     * Belongs To A Playlist.
     *
     * @return BelongsToMany
     */
    public function playlist(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Playlist::class);
    }
}
