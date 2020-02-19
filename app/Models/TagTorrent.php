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

/**
 * App\Models\TagTorrent.
 *
 * @property int $id
 * @property int $torrent_id
 * @property string $tag_name
 * @property-read \App\Models\Tag $genre
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TagTorrent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TagTorrent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TagTorrent query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TagTorrent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TagTorrent whereTagName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TagTorrent whereTorrentId($value)
 * @mixin \Eloquent
 */
class TagTorrent extends Model
{
    /**
     * The Database Table Used By The Model.
     *
     * @var string
     */
    protected $table = 'tag_torrent';

    /**
     * Indicates If The Model Should Be Timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Has Many Tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function genre()
    {
        return $this->belongsTo(Tag::class, 'tag_name', 'name');
    }
}
