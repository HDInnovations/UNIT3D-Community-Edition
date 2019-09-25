<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaylistTorrent extends Model
{
    /**
     * Indicates If The Model Should Be Timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Belongs To A Torrent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function torrent()
    {
        return $this->belongsTo(Torrent::class);
    }

    /**
     * Belongs To A Playlist.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }
}
