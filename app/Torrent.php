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

    /**
     * Mass assignment fields
     *
     */
    protected $fillable = ['name', 'slug', 'description', 'mediainfo', 'info_hash', 'file_name', 'num_file', 'announce', 'size', 'nfo', 'category_id', 'user_id',
        'imdb', 'tvdb', 'tmdb', 'mal', 'type', 'anon', 'stream', 'sd'];

    /**
     * Rules
     *
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required',
        'description' => 'required',
        'info_hash' => 'required|unique:torrents',
        'file_name' => 'required',
        'num_file' => 'required|numeric',
        'announce' => 'required',
        'size' => 'required',
        'category_id' => 'required',
        'user_id' => 'required',
        'imdb' => 'required|numeric',
        'tvdb' => 'required|numeric',
        'tmdb' => 'required|numeric',
        'type' => 'required',
        'anon' => 'required',
        'stream' => 'required',
        'sd' => 'required'
    ];

    public $sortable = ['id', 'name', 'size', 'seeders', 'leechers', 'times_completed', 'created_at'];

    /**
     * Belongs to User
     *
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    /**
     * Belongs to  Category
     *
     */
    public function category()
    {
        return $this->belongsTo(\App\Category::class);
    }

    /**
     * Belongs to  Type
     *
     */
    public function type()
    {
        return $this->belongsTo(\App\Type::class);
    }

    /**
     * Has many files
     *
     */
    public function files()
    {
        return $this->hasMany(\App\TorrentFile::class);
    }

    /**
     * Has many Comment
     *
     */
    public function comments()
    {
        return $this->hasMany(\App\Comment::class);
    }

    /**
     * Has many peers
     *
     *
     */
    public function peers()
    {
        return $this->hasMany(\App\Peer::class);
    }

    /**
     * HABTM Tag
     *
     *
     */
    public function tags()
    {
        return $this->belongsToMany(\App\Tag::class);
    }

    /**
     * Relationship to a single request
     *
     */
    public function request()
    {
        return $this->hasOne(\App\TorrentRequest::class, 'filled_hash', 'info_hash');
    }

    /**
     * Torrent has been moderated by
     *
     */
    public function moderated()
    {
        return $this->belongsTo(\App\User::class, 'moderated_by');
    }

    /**
     * Formats the output of the description
     *
     */
    public function getDescriptionHtml()
    {
        return Bbcode::parse($this->description);
    }

    /**
     * Formats the output of the mediainfo dump
     *
     */
    public function getMediaInfo()
    {
        $parser = new MediaInfo;
        $parsed = $parser->parse($this->mediaInfo);
        return $parsed;
    }

    /**
     * Returns the size in human format
     *
     */
    public function getSize($bytes = null, $precision = 2)
    {
        $bytes = $this->size;
        return StringHelper::formatBytes($bytes, 2);
    }

    /**
     * Bookmarks
     *
     */
    public function bookmarks()
    {
        return (bool)Bookmark::where('user_id', Auth::id())->where('torrent_id', $this->id)->first();
    }

    /**
     * One movie belongs to many catalogs
     *
     */
    public function catalogs()
    {
        return $this->belongsToMany(Catalog::class)->withTimestamps();
    }

    public function history()
    {
        return $this->hasMany(\App\History::class, "info_hash", "info_hash");
    }

    /**
     * Has many Thank
     *
     */
    public function thanks()
    {
        return $this->hasMany(\App\Thank::class);
    }

    /**
     * Has many HitRuns
     *
     */
    public function hitrun()
    {
        return $this->hasMany(\App\Warning::class, 'torrent');
    }

    /**
     * Has many Featured
     *
     */
    public function featured()
    {
        return $this->hasMany(\App\FeaturedTorrent::class);
    }

    public function isFreeleech($user = null)
    {
        $pfree = $user ? $user->group->is_freeleech || PersonalFreeleech::where('user_id', '=', $user->id)->first() : false;
        return $this->free || config('other.freeleech') || $pfree;
    }
}
