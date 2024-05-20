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
use App\Models\Scopes\ApprovedScope;
use App\Notifications\NewComment;
use App\Notifications\NewThank;
use App\Traits\Auditable;
use App\Traits\GroupedLastScope;
use App\Traits\TorrentFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use voku\helper\AntiXSS;

/**
 * App\Models\Torrent.
 *
 * @property int                                                                        $id
 * @property string                                                                     $name
 * @property string                                                                     $description
 * @property string|null                                                                $mediainfo
 * @property string|null                                                                $bdinfo
 * @property string                                                                     $file_name
 * @property int                                                                        $num_file
 * @property string|null                                                                $folder
 * @property float                                                                      $size
 * @property string|null                                                                $nfo
 * @property int                                                                        $leechers
 * @property int                                                                        $seeders
 * @property int                                                                        $times_completed
 * @property int|null                                                                   $category_id
 * @property int                                                                        $user_id
 * @property int                                                                        $imdb
 * @property int                                                                        $tvdb
 * @property int                                                                        $tmdb
 * @property int                                                                        $mal
 * @property int                                                                        $igdb
 * @property int|null                                                                   $season_number
 * @property int|null                                                                   $episode_number
 * @property int                                                                        $stream
 * @property int                                                                        $free
 * @property bool                                                                       $doubleup
 * @property bool                                                                       $refundable
 * @property int                                                                        $highspeed
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\FeaturedTorrent> $featured
 * @property int                                                                        $status
 * @property \Illuminate\Support\Carbon|null                                            $moderated_at
 * @property int|null                                                                   $moderated_by
 * @property int                                                                        $anon
 * @property bool                                                                       $sticky
 * @property int                                                                        $sd
 * @property int                                                                        $internal
 * @property \Illuminate\Support\Carbon|null                                            $created_at
 * @property \Illuminate\Support\Carbon|null                                            $updated_at
 * @property string|null                                                                $bumped_at
 * @property \Illuminate\Support\Carbon|null                                            $fl_until
 * @property \Illuminate\Support\Carbon|null                                            $du_until
 * @property int|null                                                                   $release_year
 * @property int                                                                        $type_id
 * @property int|null                                                                   $resolution_id
 * @property int|null                                                                   $distributor_id
 * @property int|null                                                                   $region_id
 * @property int                                                                        $personal_release
 * @property int|null                                                                   $balance
 * @property int|null                                                                   $balance_offset
 * @property string                                                                     $info_hash
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bookmark> $bookmarks
 * @property-read Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read Distributor|null $distributor
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TorrentFile> $files
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FreeleechToken> $freeleechTokens
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\History> $history
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Warning> $hitrun
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Keyword> $keywords
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Peer> $leeches
 * @property-read User|null $moderated
 * @property-read Movie|null $movie
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Peer> $peers
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Playlist> $playlists
 * @property-read Region|null $region
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TorrentRequest> $requests
 * @property-read Resolution|null $resolution
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resurrection> $resurrections
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Peer> $seeds
 * @property-write mixed $media_info
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subtitle> $subtitles
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Thank> $thanks
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TorrentTip> $tips
 * @property-read Tv|null $tv
 * @property-read Type|null $type
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent alive()
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent bookmarkedBy(\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent dead()
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent doubleup()
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent downloadedBy(\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent dying()
 * @method static \Database\Factories\TorrentFactory            factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent featured()
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent graveyard()
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent highSpeed()
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent internal()
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent lastPerGroup(?array $fields = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent leechedby(\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent notDownloadedBy(\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofAdult(?bool $isAdult = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofCategory(array $categories)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofCollection(int $collectionId)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofCompany(int $companyId)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofDescription(string $description, bool $isRegex = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofDistributor(array $distributors)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofEpisode(int $episodeNumber)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofFilename(string $filename)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofFreeleech(array|int $free)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofGenre(array $genres)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofImdb(int $tvdbId)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofKeyword(array $keywords)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofMal(int $malId)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofMediainfo(string $mediainfo, bool $isRegex = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofName(string $name, bool $isRegex = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofNetwork(int $networkId)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofPlaylist(int $playlistId)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofPrimaryLanguage(array $languages)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofRefundable(int $refundable)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofRegion(array $regions)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofResolution(array $resolutions)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofSeason(int $seasonNumber)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofSizeGreaterOrEqualTo(int $size)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofSizeLesserOrEqualTo(int $size)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofTmdb(int $tvdbId)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofTvdb(int $tvdbId)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofType(array $types)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent ofUploader(string $username, ?\App\Models\User $authenticatedUser = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent personalRelease()
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent query()
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent refundable()
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent releasedAfterOrIn(int $year)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent releasedBeforeOrIn(int $year)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent sd()
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent seededBy(\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent streamOptimized()
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent uncompletedBy(\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder|Torrent wishedBy(\App\Models\User $user)
 * @mixin \Eloquent
 */
class Torrent extends Model
{
    use Auditable;
    use GroupedLastScope;
    use HasFactory;
    use TorrentFilter;

    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'igdb'         => 'integer',
            'fl_until'     => 'datetime',
            'du_until'     => 'datetime',
            'doubleup'     => 'boolean',
            'refundable'   => 'boolean',
            'featured'     => 'boolean',
            'moderated_at' => 'datetime',
            'sticky'       => 'boolean',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, self>
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Category, self>
     */
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Belongs To A Type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Type, self>
     */
    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Belongs To A Resolution.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Resolution, self>
     */
    public function resolution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Resolution::class);
    }

    /**
     * Belongs To A Distributor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Distributor, self>
     */
    public function distributor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Distributor::class);
    }

    /**
     * Belongs To A Region.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Region, self>
     */
    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Belongs To A Movie.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Movie, self>
     */
    public function movie(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Movie::class, 'tmdb');
    }

    /**
     * Belongs To A Tv.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Tv, self>
     */
    public function tv(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tv::class, 'tmdb');
    }

    /**
     * Belongs To A Playlist.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Playlist>
     */
    public function playlists(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Playlist::class, 'playlist_torrents')->using(PlaylistTorrent::class)->withPivot('id');
    }

    /**
     * Torrent Has Been Moderated By.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, self>
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Keyword>
     */
    public function keywords(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Keyword::class);
    }

    /**
     * Has Many History.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<History>
     */
    public function history(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(History::class);
    }

    /**
     * Has Many Tips.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<TorrentTip>
     */
    public function tips(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TorrentTip::class);
    }

    /**
     * Has Many Thank.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Thank>
     */
    public function thanks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Thank::class);
    }

    /**
     * Has Many HitRuns.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Warning>
     */
    public function hitrun(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Warning::class, 'torrent');
    }

    /**
     * Has Many Featured.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<FeaturedTorrent>
     */
    public function featured(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FeaturedTorrent::class);
    }

    /**
     * Has Many Files.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<TorrentFile>
     */
    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TorrentFile::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<Comment>
     */
    public function comments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Has Many Peers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Peer>
     */
    public function peers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Peer::class);
    }

    /**
     * Has Many Seeds.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Peer>
     */
    public function seeds(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Peer::class)->where('seeder', '=', true);
    }

    /**
     * Has Many Leeches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Peer>
     */
    public function leeches(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Peer::class)->where('seeder', '=', false);
    }

    /**
     * Has Many Subtitles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Subtitle>
     */
    public function subtitles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subtitle::class);
    }

    /**
     * Relationship To Many Requests.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<TorrentRequest>
     */
    public function requests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TorrentRequest::class);
    }

    /**
     * Has many free leech tokens.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<FreeleechToken>
     */
    public function freeleechTokens(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FreeleechToken::class);
    }

    /**
     * Bookmarks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Bookmark>
     */
    public function bookmarks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Bookmarks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Resurrection>
     */
    public function resurrections(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Resurrection::class);
    }

    /**
     * Set The Torrents Description After Its Been Purified.
     */
    public function setDescriptionAttribute(?string $value): void
    {
        $this->attributes['description'] = htmlspecialchars((new AntiXSS())->xss_clean($value), ENT_NOQUOTES);
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
                    $user->notify(new NewComment('torrent', $payload));
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
