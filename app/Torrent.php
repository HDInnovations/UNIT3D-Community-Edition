<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hootlex\Moderation\Moderatable;
use Kyslik\ColumnSortable\Sortable;

use App\Helpers\MediaInfo;
use App\Helpers\StringHelper;
use App\Helpers\Bbcode;

/**
 * Torrent model
 *
 */
class Torrent extends Model
{
    use Moderatable;
    use Sortable;

    public $sortable = ['id', 'name', 'size', 'seeders', 'leechers', 'times_completed', 'created_at'];

    /**
     * Belongs To A User
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id' => '1'
        ]);
    }

    /**
     * Belongs To A Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Belongs To A Type
     */
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Has Many Files
     */
    public function files()
    {
        return $this->hasMany(TorrentFile::class);
    }

    /**
     * Has Many Comments
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Has Many Peers
     */
    public function peers()
    {
        return $this->hasMany(Peer::class);
    }

    /**
     * Has Many Tags
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Relationship To A Single Request
     */
    public function request()
    {
        return $this->hasOne(TorrentRequest::class, 'filled_hash', 'info_hash');
    }

    /**
     * Torrent Has Been Moderated By
     */
    public function moderated()
    {
        return $this->belongsTo(User::class, 'moderated_by')->withDefault([
            'username' => 'System',
            'id' => '1'
        ]);
    }

    /**
     * Formats The Output Of The Description
     */
    public function getDescriptionHtml()
    {
        return Bbcode::parse($this->description);
    }

    /**
     * Formats The Output Of The Media Info Dump
     */
    public function getMediaInfo()
    {
        $parser = new MediaInfo;
        $parsed = $parser->parse($this->mediaInfo);
        return $parsed;
    }

    /**
     * Returns The Size In Human Format
     */
    public function getSize($bytes = null, $precision = 2)
    {
        $bytes = $this->size;
        return StringHelper::formatBytes($bytes, 2);
    }

    /**
     * Bookmarks
     */
    public function bookmarked()
    {
        return Bookmark::where('user_id', auth()->user()->id)
            ->where('torrent_id', $this->id)
            ->first() ? true : false;
    }

    /**
     * One Title Belongs To Many Catalogs
     */
    public function catalogs()
    {
        return $this->belongsToMany(Catalog::class)->withTimestamps();
    }

    /**
     * Has Many History
     */
    public function history()
    {
        return $this->hasMany(History::class, "info_hash", "info_hash");
    }

    /**
     * Has Many Thank
     */
    public function thanks()
    {
        return $this->hasMany(Thank::class);
    }

    /**
     * Has Many HitRuns
     */
    public function hitrun()
    {
        return $this->hasMany(Warning::class, 'torrent');
    }

    /**
     * Has Many Featured
     */
    public function featured()
    {
        return $this->hasMany(FeaturedTorrent::class);
    }

    /**
     * Torrent Is Freeleech
     */
    public function isFreeleech($user = null)
    {
        $pfree = $user ? $user->group->is_freeleech || PersonalFreeleech::where('user_id', '=', $user->id)->first() : false;
        return $this->free || config('other.freeleech') || $pfree;
    }
}
