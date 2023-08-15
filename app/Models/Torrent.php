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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
 * @property string|null                     $release_year
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

    /**
     * This query is to be added to a raw select from the torrents table.
     *
     * The fields it returns are used by Meilisearch to power the advanced
     * torrent search, RSS, and the API.
     */
    public const string SEARCHABLE = "
            JSON_ARRAYAGG(
                JSON_OBJECT(
                    'id', torrents.id,
                    'name', torrents.name,
                    'description', torrents.description,
                    'mediainfo', torrents.mediainfo,
                    'bdinfo', torrents.bdinfo,
                    'num_file', torrents.num_file,
                    'folder', torrents.folder,
                    'size', torrents.size,
                    'leechers', torrents.leechers,
                    'seeders', torrents.seeders,
                    'times_completed', torrents.times_completed,
                    'created_at', UNIX_TIMESTAMP(torrents.created_at),
                    'bumped_at', UNIX_TIMESTAMP(torrents.bumped_at),
                    'fl_until', UNIX_TIMESTAMP(torrents.fl_until),
                    'du_until', UNIX_TIMESTAMP(torrents.du_until),
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
                    'deleted_at', UNIX_TIMESTAMP(torrents.deleted_at),
                    'distributor_id', torrents.distributor_id,
                    'region_id', torrents.region_id,
                    'personal_release', torrents.personal_release,
                    'info_hash', LOWER(HEX(torrents.info_hash)),
                    'history_seeders', (
                        SELECT COALESCE(JSON_ARRAYAGG(JSON_OBJECT(
                            'user_id', history.user_id
                        )), JSON_ARRAY())
                        FROM history
                        WHERE torrents.id = history.torrent_id
                            AND seeder = 1
                    ),
                    'history_leechers', (
                        SELECT COALESCE(JSON_ARRAYAGG(JSON_OBJECT(
                            'user_id', history.user_id
                        )), JSON_ARRAY())
                        FROM history
                        WHERE torrents.id = history.torrent_id
                            AND seeder = 0
                    ),
                    'history_active', (
                        SELECT COALESCE(JSON_ARRAYAGG(JSON_OBJECT(
                            'user_id', history.user_id
                        )), JSON_ARRAY())
                        FROM history
                        WHERE torrents.id = history.torrent_id
                            AND active = 1
                    ),
                    'history_inactive', (
                        SELECT COALESCE(JSON_ARRAYAGG(JSON_OBJECT(
                            'user_id', history.user_id
                        )), JSON_ARRAY())
                        FROM history
                        WHERE torrents.id = history.torrent_id
                            AND active = 0
                    ),
                    'history_complete', (
                        SELECT COALESCE(JSON_ARRAYAGG(JSON_OBJECT(
                            'user_id', history.user_id
                        )), JSON_ARRAY())
                        FROM history
                        WHERE torrents.id = history.torrent_id
                            AND completed_at IS NOT NULL
                    ),
                    'history_incomplete', (
                        SELECT COALESCE(JSON_ARRAYAGG(JSON_OBJECT(
                            'user_id', history.user_id
                        )), JSON_ARRAY())
                        FROM history
                        WHERE torrents.id = history.torrent_id
                            AND completed_at IS NULL
                    ),
                    'user', (
                        SELECT JSON_OBJECT(
                            'id', users.id,
                            'username', users.username,
                            'group', (
                                SELECT JSON_OBJECT(
                                    'name', `groups`.name,
                                    'color', `groups`.color,
                                    'icon', `groups`.icon,
                                    'effect', `groups`.effect
                                )
                                FROM `groups`
                                WHERE `groups`.id = users.group_id
                                LIMIT 1
                            )
                        )
                        FROM users
                        WHERE torrents.user_id = users.id
                        LIMIT 1
                    ),
                    'category', (
                        SELECT JSON_OBJECT(
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
                        FROM categories
                        WHERE torrents.category_id = categories.id
                        LIMIT 1
                    ),
                    'type', (
                        SELECT JSON_OBJECT(
                            'id', types.id,
                            'name', types.name
                        )
                        FROM types
                        WHERE torrents.type_id = types.id
                        LIMIT 1
                    ),
                    'resolution', (
                        SELECT JSON_OBJECT(
                            'id', resolutions.id,
                            'name', resolutions.name
                        )
                        FROM resolutions
                        WHERE torrents.resolution_id = resolutions.id
                        LIMIT 1
                    ),
                    'movie', (
                        SELECT JSON_OBJECT(
                            'id', movies.id,
                            'name', movies.title,
                            'year', YEAR(movies.release_date),
                            'poster', movies.poster,
                            'original_language', movies.original_language,
                            'adult', movies.adult,
                            'companies', (
                                SELECT COALESCE(JSON_ARRAYAGG(JSON_OBJECT(
                                    'id', companies.id,
                                    'name', companies.name
                                )), JSON_ARRAY())
                                FROM companies
                                WHERE companies.id IN (
                                    SELECT company_id
                                    FROM company_movie
                                    WHERE company_movie.movie_id = torrents.tmdb
                                )
                            ),
                            'genres', (
                                SELECT COALESCE(JSON_ARRAYAGG(JSON_OBJECT(
                                    'id', genres.id,
                                    'name', genres.name
                                )), JSON_ARRAY())
                                FROM genres
                                WHERE genres.id IN (
                                    SELECT genre_id
                                    FROM genre_movie
                                    WHERE genre_movie.movie_id = torrents.tmdb
                                )
                            ),
                            'collection_id', (
                                SELECT collection_movie.collection_id
                                FROM collection_movie
                                WHERE movies.id = collection_movie.movie_id
                                LIMIT 1
                            ),
                            'wishes', (
                                SELECT COALESCE(JSON_ARRAYAGG(JSON_OBJECT(
                                    'user_id', wishes.user_id
                                )), JSON_ARRAY())
                                FROM wishes
                                WHERE wishes.movie_id = movies.id
                            )
                        )
                        FROM movies
                        WHERE torrents.tmdb = movies.id
                            AND torrents.category_id in (
                                SELECT id
                                FROM categories
                                WHERE movie_meta = 1
                            )
                        LIMIT 1
                    ),
                    'tv', (
                        SELECT JSON_OBJECT(
                            'id', tv.id,
                            'name', tv.name,
                            'year', YEAR(tv.first_air_date),
                            'poster', tv.poster,
                            'original_language', tv.original_language,
                            'companies', (
                                SELECT COALESCE(JSON_ARRAYAGG(JSON_OBJECT(
                                    'id', companies.id,
                                    'name', companies.name
                                )), JSON_ARRAY())
                                FROM companies
                                WHERE companies.id IN (
                                    SELECT company_id
                                    FROM company_tv
                                    WHERE company_tv.tv_id = torrents.id
                                )
                            ),
                            'genres', (
                                SELECT COALESCE(JSON_ARRAYAGG(JSON_OBJECT(
                                    'id', genres.id,
                                    'name', genres.name
                                )), JSON_ARRAY())
                                FROM genres
                                WHERE genres.id IN (
                                    SELECT genre_id
                                    FROM genre_tv
                                    WHERE genre_tv.tv_id = torrents.tmdb
                                )
                            ),
                            'networks', (
                                SELECT COALESCE(JSON_ARRAYAGG(JSON_OBJECT(
                                    'id', networks.id,
                                    'name', networks.name
                                )), JSON_ARRAY())
                                FROM networks
                                WHERE networks.id IN (
                                    SELECT network_id
                                    FROM network_tv
                                    WHERE network_tv.tv_id = torrents.id
                                )
                            ),
                            'wishes', (
                                SELECT COALESCE(JSON_ARRAYAGG(JSON_OBJECT(
                                    'user_id', wishes.user_id
                                )), JSON_ARRAY())
                                FROM wishes
                                WHERE wishes.tv_id = tv.id
                            )
                        )
                        FROM tv
                        WHERE torrents.tmdb = tv.id
                            AND torrents.category_id in (
                                SELECT id
                                FROM categories
                                WHERE tv_meta = 1
                            )
                        LIMIT 1
                    ),
                    'playlists', (
                        SELECT COALESCE(JSON_ARRAYAGG(JSON_OBJECT(
                            'id', playlist_torrents.id
                        )), JSON_ARRAY())
                        FROM playlist_torrents
                        WHERE torrents.id = playlist_torrents.playlist_id
                    ),
                    'freeleech_tokens', (
                        SELECT COALESCE(JSON_ARRAYAGG(JSON_OBJECT(
                            'user_id', freeleech_tokens.id
                        )), JSON_ARRAY())
                        FROM freeleech_tokens
                        WHERE torrents.id = freeleech_tokens.torrent_id
                    ),
                    'bookmarks', (
                        SELECT COALESCE(JSON_ARRAYAGG(JSON_OBJECT(
                            'user_id', bookmarks.user_id
                        )), JSON_ARRAY())
                        FROM bookmarks
                        WHERE torrents.id = bookmarks.torrent_id
                    ),
                    'files', (
                        SELECT COALESCE(JSON_ARRAYAGG(JSON_OBJECT(
                            'id', files.id,
                            'name', files.name,
                            'size', files.size
                        )), JSON_ARRAY())
                        FROM files
                        WHERE torrents.id = files.torrent_id
                    ),
                    'keywords', (
                        SELECT COALESCE(JSON_ARRAYAGG(keywords.name), JSON_ARRAY())
                        FROM keywords
                        WHERE torrents.id = keywords.torrent_id
                    )
                )
            ) AS searchable
    ";

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

    public function syncToMeilisearch(): void
    {
        $torrents = DB::table('torrents')
            ->selectRaw(Torrent::SEARCHABLE)
            ->where('id', '=', $this->id)
            ->value('searchable');

        $response = Http::withToken(config('meilisearch.key'))
            ->withBody($torrents)
            ->post(config('meilisearch.host').'/indexes/torrents/documents');

        if ($response->failed()) {
            Log::notice('Meilisearch sync error', [
                'status' => $response->status(),
                'body'   => $response->body(),
                'id'     => $this->id,
            ]);
        }
    }
}
