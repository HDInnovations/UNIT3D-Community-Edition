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

use App\Helpers\Bbcode;
use App\Helpers\Linkify;
use App\Helpers\MediaInfo;
use App\Helpers\StringHelper;
use App\Notifications\NewComment;
use App\Notifications\NewThank;
use App\Traits\Auditable;
use Hootlex\Moderation\Moderatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use voku\helper\AntiXSS;

class Torrent extends Model
{
    use HasFactory;
    use Moderatable;
    use Sortable;
    use Auditable;

    /**
     * The Columns That Are Sortable.
     */
    public array $sortable = [
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
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Belongs To A Uploader.
     */
    public function uploader(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        // Not needed yet but may use this soon.

        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Belongs To A Category.
     */
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Belongs To A Type.
     */
    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Belongs To A Resolution.
     */
    public function resolution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Resolution::class);
    }

    /**
     * Belongs To A Distributor.
     */
    public function distributor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Distributor::class);
    }

    /**
     * Belongs To A Region.
     */
    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Belongs To A Playlist.
     */
    public function playlists(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PlaylistTorrent::class);
    }

    /**
     * Has Many Genres.
     */
    public function genres(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'genre_torrent', 'torrent_id', 'genre_id', 'id', 'id');
    }

    /**
     * Torrent Has Been Moderated By.
     */
    public function moderated(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by')->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Has Many Keywords.
     */
    public function keywords(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Keyword::class);
    }

    /**
     * Has Many History.
     */
    public function history(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(History::class, 'info_hash', 'info_hash');
    }

    /**
     * Has Many Tips.
     */
    public function tips(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BonTransactions::class, 'torrent_id', 'id')->where('name', '=', 'tip');
    }

    /**
     * Has Many Thank.
     */
    public function thanks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Thank::class);
    }

    /**
     * Has Many HitRuns.
     */
    public function hitrun(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Warning::class, 'torrent');
    }

    /**
     * Has Many Featured.
     */
    public function featured(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FeaturedTorrent::class);
    }

    /**
     * Has Many Files.
     */
    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TorrentFile::class);
    }

    /**
     * Has Many Comments.
     */
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Has Many Peers.
     */
    public function peers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Peer::class);
    }

    /**
     * Has Many Subtitles.
     */
    public function subtitles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subtitle::class);
    }

    /**
     * Relationship To A Single Request.
     */
    public function request(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(TorrentRequest::class, 'filled_hash', 'info_hash');
    }

    /**
     * Set The Torrents Description After Its Been Purified.
     */
    public function setDescriptionAttribute(string $value): void
    {
        $this->attributes['description'] = \htmlspecialchars((new AntiXSS())->xss_clean($value), ENT_NOQUOTES);
    }

    /**
     * Parse Description And Return Valid HTML.
     */
    public function getDescriptionHtml(): string
    {
        $bbcode = new Bbcode();

        return (new Linkify())->linky($bbcode->parse($this->description, true));
    }

    /**
     * Set The Torrents MediaInfo After Its Been Purified.
     */
    public function setMediaInfoAttribute(?string $value): void
    {
        $this->attributes['mediainfo'] = $value;
    }

    /**
     * Formats The Output Of The Media Info Dump.
     */
    public function getMediaInfo(): array
    {
        return (new MediaInfo())->parse($this->mediaInfo);
    }

    /**
     * Returns The Size In Human Format.
     */
    public function getSize(): string
    {
        $bytes = $this->size;

        return StringHelper::formatBytes($bytes, 2);
    }

    /**
     * Bookmarks.
     */
    public function bookmarked(): bool
    {
        return (bool) Bookmark::where('user_id', '=', \auth()->user()->id)
            ->where('torrent_id', '=', $this->id)
            ->first();
    }

    /**
     * Notify Uploader When An Action Is Taken.
     */
    public function notifyUploader($type, $payload): bool
    {
        $user = User::with('notification')->findOrFail($this->user_id);
        if ($type == 'thank') {
            if ($user->acceptsNotification(\auth()->user(), $user, 'torrent', 'show_torrent_thank')) {
                $user->notify(new NewThank('torrent', $payload));

                return true;
            }

            return true;
        }

        if ($user->acceptsNotification(\auth()->user(), $user, 'torrent', 'show_torrent_comment')) {
            $user->notify(new NewComment('torrent', $payload));

            return true;
        }

        return true;
    }

    /**
     * Torrent Is Freeleech.
     */
    public function isFreeleech($user = null): bool
    {
        $pfree = $user && ($user->group->is_freeleech || PersonalFreeleech::where('user_id', '=', $user->id)->first());

        return $this->free || \config('other.freeleech') || $pfree;
    }
}
