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

namespace App;

use App\Helpers\Bbcode;
use App\Helpers\MediaInfo;
use App\Helpers\StringHelper;
use App\Notifications\NewThank;
use App\Notifications\NewComment;
use Hootlex\Moderation\Moderatable;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

/**
 * Torrent model.
 */
class Torrent extends Model
{
    use Moderatable;
    use Sortable;

    /**
     * The Columns That Are Sortable.
     *
     * @var array
     */
    public $sortable = [
        'id',
        'name',
        'size',
        'seeders',
        'leechers',
        'times_completed',
        'created_at',
    ];

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Belongs To A Uploader.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function uploader()
    {
        // Not needed yet but may use this soon.

        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Belongs To A Category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Belongs To A Type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Torrent Has Been Moderated By.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function moderated()
    {
        return $this->belongsTo(User::class, 'moderated_by')->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * One Title Belongs To Many Catalogs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function catalogs()
    {
        return $this->belongsToMany(Catalog::class)->withTimestamps();
    }

    /**
     * Has Many Tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Has Many History.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history()
    {
        return $this->hasMany(History::class, 'info_hash', 'info_hash');
    }

    /**
     * Has Many Thank.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function thanks()
    {
        return $this->hasMany(Thank::class);
    }

    /**
     * Has Many HitRuns.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hitrun()
    {
        return $this->hasMany(Warning::class, 'torrent');
    }

    /**
     * Has Many Featured.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function featured()
    {
        return $this->hasMany(FeaturedTorrent::class);
    }

    /**
     * Has Many Files.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(TorrentFile::class);
    }

    /**
     * Has Many Comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Has Many Peers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function peers()
    {
        return $this->hasMany(Peer::class);
    }

    /**
     * Relationship To A Single Request.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function request()
    {
        return $this->hasOne(TorrentRequest::class, 'filled_hash', 'info_hash');
    }

    /**
     * Parse Description And Return Valid HTML.
     *
     * @return string Parsed BBCODE To HTML
     */
    public function getDescriptionHtml()
    {
        return Bbcode::parse($this->description);
    }

    /**
     * Formats The Output Of The Media Info Dump.
     *
     * @return array
     */
    public function getMediaInfo()
    {
        $parser = new MediaInfo();
        $parsed = $parser->parse($this->mediaInfo);

        return $parsed;
    }

    /**
     * Returns The Size In Human Format.
     *
     * @return string
     */
    public function getSize($bytes = null, $precision = 2)
    {
        $bytes = $this->size;

        return StringHelper::formatBytes($bytes, 2);
    }

    /**
     * Bookmarks.
     */
    public function bookmarked()
    {
        return Bookmark::where('user_id', '=', auth()->user()->id)
            ->where('torrent_id', '=', $this->id)
            ->first() ? true : false;
    }

    /**
     * Notify Uploader When An Action Is Taken.
     *
     * @return boolean
     */
    public function notifyUploader($type,$payload)
    {
        if($type == 'thank') {
            User::find($this->user_id)->notify(new NewThank('torrent',$payload));
            return true;
        }
        User::find($this->user_id)->notify(new NewComment('torrent',$payload));
        return true;
    }

    /**
     * Torrent Is Freeleech.
     */
    public function isFreeleech($user = null)
    {
        $pfree = $user ? $user->group->is_freeleech || PersonalFreeleech::where('user_id', '=', $user->id)->first() : false;

        return $this->free || config('other.freeleech') || $pfree;
    }
}
