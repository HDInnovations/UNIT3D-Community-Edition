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
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Models;

use App\Helpers\Bbcode;
use App\Helpers\Linkify;
use App\Helpers\MediaInfo;
use App\Helpers\StringHelper;
use App\Models\Scopes\ApprovedScope;
use App\Notifications\NewComment;
use App\Notifications\NewThank;
use App\Traits\Auditable;
use App\Traits\GroupedLastScope;
use App\Traits\TorrentFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use voku\helper\AntiXSS;

/**
 * App\Models\Torrent.
 *
 * @property string                          $info_hash
 * @property int                             $id
 * @property string                          $name
 * @property string                          $description
 * @property string|null                     $mediainfo
 * @property string|null                     $bdinfo
 * @property string                          $file_name
 * @property int                             $num_file
 * @property string|null                     $folder
 * @property float                           $size
 * @property mixed|null                      $nfo
 * @property int                             $leechers
 * @property int                             $seeders
 * @property int                             $times_completed
 * @property int|null                        $category_id
 * @property int                             $user_id
 * @property int                             $imdb
 * @property int                             $tvdb
 * @property int                             $tmdb
 * @property int                             $mal
 * @property int                             $igdb
 * @property int|null                        $season_number
 * @property int|null                        $episode_number
 * @property int                             $stream
 * @property int                             $free
 * @property bool                            $doubleup
 * @property bool                            $refundable
 * @property int                             $highspeed
 * @property bool                            $featured
 * @property int                             $status
 * @property \Illuminate\Support\Carbon|null $moderated_at
 * @property int|null                        $moderated_by
 * @property int                             $anon
 * @property bool                            $sticky
 * @property int                             $sd
 * @property int                             $internal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $bumped_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $fl_until
 * @property \Illuminate\Support\Carbon|null $du_until
 * @property int                             $type_id
 * @property int|null                        $resolution_id
 * @property int|null                        $distributor_id
 * @property int|null                        $region_id
 * @property int                             $personal_release
 * @property int|null                        $balance
 * @property int|null                        $balance_offset
 */
class Torrent extends Model
{
    use Auditable;
    use GroupedLastScope;

    /** @use HasFactory<\Database\Factories\TorrentFactory> */
    use HasFactory;
    use SoftDeletes;
    use TorrentFilter;

    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array{tmdb: 'int', igdb: 'int', bumped_at: 'datetime', fl_until: 'datetime', du_until: 'datetime', doubleup: 'bool', refundable: 'bool', featured: 'bool', moderated_at: 'datetime', sticky: 'bool'}
     */
    protected function casts(): array
    {
        return [
            'tmdb'         => 'int',
            'igdb'         => 'int',
            'bumped_at'    => 'datetime',
            'fl_until'     => 'datetime',
            'du_until'     => 'datetime',
            'doubleup'     => 'bool',
            'refundable'   => 'bool',
            'featured'     => 'bool',
            'moderated_at' => 'datetime',
            'sticky'       => 'bool',
        ];
    }

    /**
     * The attributes that should not be included in audit log.
     *
     * @var string[]
     */
    protected $discarded = [
        'info_hash',
    ];

    final public const PENDING = 0;
    final public const APPROVED = 1;
    final public const REJECTED = 2;
    final public const POSTPONED = 3;

    protected static function booted(): void
    {
        static::addGlobalScope(new ApprovedScope());
    }

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, $this>
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Belongs To A Category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Category, $this>
     */
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Belongs To A Type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Type, $this>
     */
    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Belongs To A Resolution.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Resolution, $this>
     */
    public function resolution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Resolution::class);
    }

    /**
     * Belongs To A Distributor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Distributor, $this>
     */
    public function distributor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Distributor::class);
    }

    /**
     * Belongs To A Region.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Region, $this>
     */
    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Belongs To A Movie.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Movie, $this>
     */
    public function movie(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Movie::class, 'tmdb');
    }

    /**
     * Belongs To A Tv.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Tv, $this>
     */
    public function tv(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tv::class, 'tmdb');
    }

    /**
     * Belongs To A Playlist.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Playlist, $this>
     */
    public function playlists(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Playlist::class, 'playlist_torrents')->using(PlaylistTorrent::class)->withPivot('id');
    }

    /**
     * Torrent Has Been Moderated By.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, $this>
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
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Keyword, $this>
     */
    public function keywords(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Keyword::class);
    }

    /**
     * Has Many History.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<History, $this>
     */
    public function history(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(History::class);
    }

    /**
     * Has Many Tips.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<TorrentTip, $this>
     */
    public function tips(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TorrentTip::class);
    }

    /**
     * Has Many Thank.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Thank, $this>
     */
    public function thanks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Thank::class);
    }

    /**
     * Has Many HitRuns.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Warning, $this>
     */
    public function hitrun(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Warning::class, 'torrent');
    }

    /**
     * Has Many Featured.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<FeaturedTorrent, $this>
     */
    public function featured(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FeaturedTorrent::class);
    }

    /**
     * Has Many Files.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<TorrentFile, $this>
     */
    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TorrentFile::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<Comment, $this>
     */
    public function comments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Has Many Peers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Peer, $this>
     */
    public function peers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Peer::class);
    }

    /**
     * Has Many Seeds.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Peer, $this>
     */
    public function seeds(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Peer::class)->where('seeder', '=', true);
    }

    /**
     * Has Many Leeches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Peer, $this>
     */
    public function leeches(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Peer::class)->where('seeder', '=', false);
    }

    /**
     * Has Many Subtitles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Subtitle, $this>
     */
    public function subtitles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subtitle::class);
    }

    /**
     * Relationship To Many Requests.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<TorrentRequest, $this>
     */
    public function requests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TorrentRequest::class);
    }

    /**
     * Has many free leech tokens.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<FreeleechToken, $this>
     */
    public function freeleechTokens(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FreeleechToken::class);
    }

    /**
     * Bookmarks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Bookmark, $this>
     */
    public function bookmarks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Bookmarks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Resurrection, $this>
     */
    public function resurrections(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Resurrection::class);
    }

    /**
     * Trump.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<TorrentTrump, $this>
     */
    public function trump(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(TorrentTrump::class);
    }

    /**
     * Set The Torrents Description After Its Been Purified.
     */
    public function setDescriptionAttribute(?string $value): void
    {
        $this->attributes['description'] = $value === null ? null : htmlspecialchars((new AntiXSS())->xss_clean($value), ENT_NOQUOTES);
    }

    /**
     * Parse Description And Return Valid HTML.
     */
    public function getDescriptionHtml(): string
    {
        $bbcode = new Bbcode();

        return (new Linkify())->linky($bbcode->parse($this->description));
    }

    /**
     * Set The Torrents MediaInfo After Its Been Purified.
     */
    public function setMediaInfoAttribute(?string $value): void
    {
        $this->attributes['mediainfo'] = $value;
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
     * Notify Uploader When An Action Is Taken.
     */
    public function notifyUploader(string $type, Thank|Comment $payload): bool
    {
        $user = User::with('notification')->findOrFail($this->user_id);

        switch (true) {
            case $payload instanceof Thank:
                if ($user->acceptsNotification(auth()->user(), $user, 'torrent', 'show_torrent_thank')) {
                    $user->notify(new NewThank('torrent', $payload));
                }

                break;
            case $payload instanceof Comment:
                if ($user->acceptsNotification(auth()->user(), $user, 'torrent', 'show_torrent_comment')) {
                    $user->notify(new NewComment($this, $payload));
                }

                break;
        }

        return true;
    }

    /**
     * Torrent Is Freeleech.
     */
    public function isFreeleech(User $user = null): bool
    {
        $pfree = $user && ($user->group->is_freeleech || cache()->get('personal_freeleech:'.$user->id));

        return $this->free || config('other.freeleech') || $pfree;
    }
}
