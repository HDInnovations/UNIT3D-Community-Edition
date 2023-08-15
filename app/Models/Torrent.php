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
 * @method \Illuminate\Database\Eloquent\Builder<static> scopeWithAnyStatus(\Illuminate\Database\Eloquent\Builder $builder)
 */
class Torrent extends Model
{
    use Auditable;
    use GroupedLastScope;
    use HasFactory;
    use TorrentFilter;

    protected $guarded = [];

    /**
     * The Attributes That Should Be Mutated To Dates.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fl_until'     => 'datetime',
        'du_until'     => 'datetime',
        'moderated_at' => 'datetime',
    ];

    /**
     * The attributes that should not be included in audit log.
     *
     * @var array
     */
    protected $discarded = [
        'info_hash',
    ];

    public const PENDING = 0;
    public const APPROVED = 1;
    public const REJECTED = 2;
    public const POSTPONED = 3;

    /**
     * This query is to be added to a raw select from the torrents table.
     *
     * The fields it returns are used by Meilisearch to power the advanced
     * torrent search, RSS, and the API.
     */
    public const SEARCHABLE = "
            JSON_ARRAYAGG(
                JSON_OBJECT(
                    'id', torrents.id,
                    'name', torrents.name,
                    'description', torrents.description,
                    'mediainfo', torrents.mediainfo,
                    'bdinfo', torrents.bdinfo,
                    'folder', torrents.folder,
                    'size', torrents.size,
                    'leechers', torrents.leechers,
                    'seeders', torrents.seeders,
                    'times_completed', torrents.times_completed,
                    'created_at', torrents.created_at,
                    'bumped_at', torrents.bumped_at,
                    'fl_until', torrents.fl_until,
                    'du_until', torrents.du_until,
                    'category_id', torrents.category_id,
                    'user_id', torrents.user_id,
                    'imdb', torrents.imdb,
                    'tvdb', torrents.tvdb,
                    'tmdb', torrents.tmdb,
                    'mal', torrents.mal,
                    'igdb', torrents.igdb,
                    'season_number', torrents.season_number,
                    'episode_number', torrents.episode_number,
                    'stream', torrents.stream,
                    'free', torrents.free,
                    'doubleup', torrents.doubleup,
                    'refundable', torrents.refundable,
                    'highspeed', torrents.highspeed,
                    'featured', torrents.featured,
                    'status', torrents.status,
                    'anon', torrents.anon,
                    'sticky', torrents.sticky,
                    'sd', torrents.sd,
                    'internal', torrents.internal,
                    'release_year', torrents.release_year,
                    'deleted_at', torrents.deleted_at,
                    'type_id', torrents.type_id,
                    'resolution_id', torrents.resolution_id,
                    'distributor_id', torrents.distributor_id,
                    'region_id', torrents.region_id,
                    'personal_release', torrents.personal_release,
                    'info_hash', HEX(torrents.info_hash),
                    'history',
                    (
                        SELECT
                            JSON_ARRAYAGG(
                                JSON_OBJECT(
                                    'user_id', history.user_id,
                                    'seeder', history.seeder,
                                    'active', history.active,
                                    'completed_at', history.completed_at
                                )
                            )
                        FROM
                            history
                        WHERE
                            torrents.id = history.torrent_id
                    ),
                    'user',
                    (
                        SELECT
                            JSON_OBJECT(
                                'id', users.id,
                                'username', users.username,
                                'group',
                                (
                                    SELECT
                                        JSON_OBJECT(
                                            'name', `groups`.name,
                                            'color', `groups`.color,
                                            'icon', `groups`.icon,
                                            'effect', `groups`.effect
                                        )
                                    FROM
                                        `groups`
                                    WHERE
                                        `groups`.id = users.id
                                    LIMIT
                                        1
                                )
                            )
                        FROM
                            users
                        WHERE
                            torrents.user_id = users.id
                        LIMIT
                            1
                    ),
                    'category',
                    (
                        SELECT
                            JSON_OBJECT(
                                'id', categories.id,
                                'name', categories.name,
                                'image', categories.image,
                                'icon', categories.icon,
                                'no_meta', categories.no_meta,
                                'music_meta', categories.music_meta,
                                'game_meta', categories.game_meta,
                                'tv_meta', categories.tv_meta,
                                'movie_meta', categories.movie_meta
                            )
                        FROM
                            categories
                        WHERE
                            torrents.category_id = categories.id
                        LIMIT
                            1
                    ),
                    'type',
                    (
                        SELECT
                            JSON_OBJECT(
                                'id', types.id,
                                'name', types.name
                            )
                        FROM
                            types
                        WHERE
                            torrents.type_id = types.id
                        LIMIT
                            1
                    ),
                    'resolution',
                    (
                        SELECT
                            JSON_OBJECT(
                                'id', resolutions.id,
                                'name', resolutions.name
                            )
                        FROM
                            resolutions
                        WHERE
                            torrents.resolution_id = resolutions.id
                        LIMIT
                            1
                    ),
                    'movie',
                    (
                        SELECT
                            JSON_OBJECT(
                                'id', movie.id,
                                'name', movie.title,
                                'year', YEAR(movie.release_date),
                                'genres',
                                (
                                    SELECT
                                        JSON_ARRAYAGG(
                                            JSON_OBJECT(
                                                'id', genres.id,
                                                'name', genres.name
                                            )
                                        )
                                    FROM
                                        genres
                                    WHERE
                                        genres.id IN (SELECT genre_id FROM genre_movie WHERE genre_movie.movie_id = torrents.tmdb)
                                )
                            )
                        FROM
                            movie
                        WHERE
                            torrents.tmdb = movie.id
                            AND torrents.category_id in (SELECT id FROM categories WHERE movie_meta = 1)
                        LIMIT
                            1
                    ),
                    'tv',
                    (
                        SELECT
                            JSON_OBJECT(
                                'id', tv.id,
                                'name', tv.name,
                                'year', YEAR(tv.first_air_date),
                                'genres',
                                (
                                    SELECT
                                        JSON_ARRAYAGG(
                                            JSON_OBJECT(
                                                'id', genres.id,
                                                'name', genres.name
                                            )
                                        )
                                    FROM
                                        genres
                                    WHERE
                                        genres.id IN (SELECT genre_id FROM genre_tv WHERE genre_tv.tv_id = torrents.tmdb)
                                )
                            )
                        FROM
                            tv
                        WHERE
                            torrents.tmdb = tv.id
                            AND torrents.category_id in (SELECT id FROM categories WHERE tv_meta = 1)
                        LIMIT
                            1
                    ),
                    'playlist_ids',
                    (
                        SELECT
                            JSON_ARRAYAGG(playlist_torrents.playlist_id)
                        FROM
                            playlist_torrents
                        WHERE
                            torrents.id = playlist_torrents.playlist_id
                    ),
                    'collection_ids',
                    (
                        SELECT
                            JSON_ARRAYAGG(collection_movie.collection_id)
                        FROM
                            collection_movie
                        WHERE
                            torrents.tmdb = collection_movie.movie_id
                            AND torrents.category_id IN (SELECT id FROM categories WHERE movie_meta = 1)
                    ),
                    'network_ids',
                    (
                        SELECT
                            JSON_ARRAYAGG(network_tv.network_id)
                        FROM
                            network_tv
                        WHERE
                            torrents.tmdb = network_tv.tv_id
                            AND torrents.category_id IN (SELECT id FROM categories WHERE tv_meta = 1)
                    ),
                    'bookmark_user_ids',
                    (
                        SELECT
                            JSON_ARRAYAGG(bookmarks.user_id)
                        FROM
                            bookmarks
                        WHERE
                            torrents.id = bookmarks.torrent_id
                    ),
                    'filenames',
                    (
                        SELECT
                            JSON_ARRAYAGG(
                                JSON_OBJECT(
                                    'name', IF(torrents.folder IS NULL, files.name, CONCAT(torrents.folder, '/', files.name)),
                                    'size', files.size
                                )
                            )
                        FROM
                            files
                        WHERE
                            torrents.id = files.torrent_id
                    ),
                    'keywords',
                    (
                        SELECT
                            JSON_ARRAYAGG(keywords.name)
                        FROM
                            keywords
                        WHERE
                            torrents.id = keywords.torrent_id
                    )
                )
            ) as searchable
    ";

    protected static function booted(): void
    {
        static::addGlobalScope(new ApprovedScope());
    }

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
    public function playlists(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Playlist::class, 'playlist_torrents')->using(PlaylistTorrent::class)->withPivot('id');
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
        return $this->hasMany(History::class);
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

    public function comments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
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
     * Relationship To Many Requests.
     */
    public function requests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TorrentRequest::class);
    }

    /**
     * Has many free leech tokens.
     */
    public function freeleechTokens(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FreeleechToken::class);
    }

    /**
     * Bookmarks.
     */
    public function bookmarks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Bookmarks.
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
     * Notify Uploader When An Action Is Taken.
     */
    public function notifyUploader($type, $payload): bool
    {
        $user = User::with('notification')->findOrFail($this->user_id);

        if ($type == 'thank') {
            if ($user->acceptsNotification(auth()->user(), $user, 'torrent', 'show_torrent_thank')) {
                $user->notify(new NewThank('torrent', $payload));

                return true;
            }

            return true;
        }

        if ($user->acceptsNotification(auth()->user(), $user, 'torrent', 'show_torrent_comment')) {
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
        $pfree = $user && ($user->group->is_freeleech || cache()->get('personal_freeleech:'.$user->id));

        return $this->free || config('other.freeleech') || $pfree;
    }
}
