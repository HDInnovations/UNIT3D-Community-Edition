<?php

declare(strict_types=1);

/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.tx
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Livewire;

use App\DTO\TorrentSearchFiltersDTO;
use App\Models\Category;
use App\Models\Distributor;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Region;
use App\Models\Resolution;
use App\Models\Torrent;
use App\Models\Tv;
use App\Models\Type;
use App\Traits\CastLivewireProperties;
use App\Traits\LivewireSort;
use App\Traits\TorrentMeta;
use Illuminate\Http\Request;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class TorrentSearch extends Component
{
    use CastLivewireProperties;
    use LivewireSort;
    use TorrentMeta;
    use WithPagination;

    #TODO: Update URL attributes once Livewire 3 fixes upstream bug. See: https://github.com/livewire/livewire/discussions/7746

    #[Url(history: true)]
    public string $name = '';

    #[Url(history: true)]
    public string $description = '';

    #[Url(history: true)]
    public string $mediainfo = '';

    #[Url(history: true)]
    public string $uploader = '';

    #[Url(history: true)]
    public string $keywords = '';

    #[Url(history: true)]
    public ?int $startYear = null;

    #[Url(history: true)]
    public ?int $endYear = null;

    #[Url(history: true)]
    public ?int $minSize = null;

    #[Url(history: true)]
    public int $minSizeMultiplier = 1;

    #[Url(history: true)]
    public ?int $maxSize = null;

    #[Url(history: true)]
    public int $maxSizeMultiplier = 1;

    #[Url(history: true)]
    public ?int $episodeNumber = null;

    #[Url(history: true)]
    public ?int $seasonNumber = null;

    /**
     * @var array<int>
     */
    #[Url(history: true)]
    public array $categoryIds = [];

    /**
     * @var array<int>
     */
    #[Url(history: true)]
    public array $typeIds = [];

    /**
     * @var array<int>
     */
    #[Url(history: true)]
    public array $resolutionIds = [];

    /**
     * @var array<int>
     */
    #[Url(history: true)]
    public array $genreIds = [];

    /**
     * @var array<int>
     */
    #[Url(history: true)]
    public array $regionIds = [];

    /**
     * @var array<int>
     */
    #[Url(history: true)]
    public array $distributorIds = [];

    #[Url(history: true)]
    public string $adult = 'any';

    #[Url(history: true)]
    public ?int $tmdbId = null;

    #[Url(history: true)]
    public string $imdbId = '';

    #[Url(history: true)]
    public ?int $tvdbId = null;

    #[Url(history: true)]
    public ?int $malId = null;

    #[Url(history: true)]
    public ?int $playlistId = null;

    #[Url(history: true)]
    public ?int $collectionId = null;

    #[Url(history: true)]
    public ?int $networkId = null;

    #[Url(history: true)]
    public ?int $companyId = null;

    /**
     * @var string[]
     */
    #[Url(history: true)]
    public array $primaryLanguageNames = [];

    /**
     * @var string[]
     */
    #[Url(history: true)]
    public array $free = [];

    #[Url(history: true)]
    public bool $doubleup = false;

    #[Url(history: true)]
    public bool $featured = false;

    #[Url(history: true)]
    public bool $refundable = false;

    #[Url(history: true)]
    public bool $stream = false;

    #[Url(history: true)]
    public bool $sd = false;

    #[Url(history: true)]
    public bool $highspeed = false;

    #[Url(history: true)]
    public bool $bookmarked = false;

    #[Url(history: true)]
    public bool $wished = false;

    #[Url(history: true)]
    public bool $internal = false;

    #[Url(history: true)]
    public bool $personalRelease = false;

    #[Url(history: true)]
    public bool $alive = false;

    #[Url(history: true)]
    public bool $dying = false;

    #[Url(history: true)]
    public bool $dead = false;

    #[Url(history: true)]
    public bool $graveyard = false;

    #[Url(history: true)]
    public bool $notDownloaded = false;

    #[Url(history: true)]
    public bool $downloaded = false;

    #[Url(history: true)]
    public bool $seeding = false;

    #[Url(history: true)]
    public bool $leeching = false;

    #[Url(history: true)]
    public bool $incomplete = false;

    #[Url(history: true, except: 'meilisearch')]
    public ?string $driver = 'meilisearch';

    #[Url(history: true)]
    public int $perPage = 25;

    #[Url(except: 'bumped_at')]
    public string $sortField = 'bumped_at';

    #[Url(history: true)]
    public string $sortDirection = 'desc';

    #[Url(except: 'list')]
    public string $view = 'list';

    final public function mount(Request $request): void
    {
        if ($request->missing('sortField')) {
            $this->sortField = auth()->user()->settings?->torrent_sort_field ?? 'bumped_at';
        }

        if ($request->missing('view')) {
            $this->view = match (auth()->user()->settings?->torrent_layout) {
                1       => 'card',
                2       => 'group',
                3       => 'poster',
                default => 'list',
            };
        }
    }

    final public function updating(string $field, mixed &$value): void
    {
        $this->castLivewireProperties($field, $value);
    }

    final public function updatingName(): void
    {
        $this->resetPage();
    }

    final public function updatedView(): void
    {
        $this->perPage = \in_array($this->view, ['card', 'poster']) ? 24 : 25;
    }

    #[Computed]
    final public function personalFreeleech(): bool
    {
        return cache()->get('personal_freeleech:'.auth()->id()) ?? false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Category>
     */
    #[Computed(cache: true, seconds: 3600)]
    final public function categories(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::query()->orderBy('position')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Type>
     */
    #[Computed(cache: true, seconds: 3600)]
    final public function types(): \Illuminate\Database\Eloquent\Collection
    {
        return Type::query()->orderBy('position')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Resolution>
     */
    #[Computed(cache: true, seconds: 3600)]
    final public function resolutions(): \Illuminate\Database\Eloquent\Collection
    {
        return Resolution::query()->orderBy('position')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Genre>
     */
    #[Computed(cache: true, seconds: 3600)]
    final public function genres(): \Illuminate\Database\Eloquent\Collection
    {
        return Genre::query()->orderBy('name')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Region>
     */
    #[Computed(cache: true, seconds: 3600)]
    final public function regions(): \Illuminate\Database\Eloquent\Collection
    {
        return Region::query()->orderBy('position')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Distributor>
     */
    #[Computed(cache: true, seconds: 3600)]
    final public function distributors(): \Illuminate\Database\Eloquent\Collection
    {
        return Distributor::query()->orderBy('name')->get();
    }

    /**
     * @return \Illuminate\Support\Collection<int, Movie>
     */
    #[Computed(cache: true, seconds: 3600)]
    final public function primaryLanguages(): \Illuminate\Support\Collection
    {
        return Movie::query()
            ->select('original_language')
            ->distinct()
            ->orderBy('original_language')
            ->pluck('original_language');
    }

    final public function filters(): TorrentSearchFiltersDTO
    {
        return (new TorrentSearchFiltersDTO(
            name: $this->name,
            description: $this->description,
            mediainfo: $this->mediainfo,
            uploader: $this->uploader,
            keywords: $this->keywords ? array_map('trim', explode(',', $this->keywords)) : [],
            startYear: $this->startYear,
            endYear: $this->endYear,
            minSize: $this->minSize === null ? null : $this->minSize * $this->minSizeMultiplier,
            maxSize: $this->maxSize === null ? null : $this->maxSize * $this->maxSizeMultiplier,
            episodeNumber: $this->episodeNumber,
            seasonNumber: $this->seasonNumber,
            categoryIds: $this->categoryIds,
            typeIds: $this->typeIds,
            resolutionIds: $this->resolutionIds,
            genreIds: $this->genreIds,
            regionIds: $this->regionIds,
            distributorIds: $this->distributorIds,
            adult: match ($this->adult) {
                'include' => true,
                'exclude' => false,
                default   => null,
            },
            tmdbId: $this->tmdbId,
            imdbId: $this->imdbId === '' ? null : ((int) (preg_match('/tt0*(?=(\d{7,}))/', $this->imdbId, $matches) ? $matches[1] : $this->imdbId)),
            tvdbId: $this->tvdbId,
            malId: $this->malId,
            playlistId: $this->playlistId,
            collectionId: $this->collectionId,
            networkId: $this->networkId,
            companyId: $this->companyId,
            primaryLanguageNames: $this->primaryLanguageNames,
            free: $this->free,
            doubleup: $this->doubleup,
            featured: $this->featured,
            refundable: $this->refundable,
            stream: $this->stream,
            sd: $this->sd,
            highspeed: $this->highspeed,
            internal: $this->internal,
            personalRelease: $this->personalRelease,
            alive: $this->alive,
            dying: $this->dying,
            dead: $this->dead,
            graveyard: $this->graveyard,
            userBookmarked: $this->bookmarked,
            userWished: $this->wished,
            userDownloaded: match (true) {
                $this->downloaded    => true,
                $this->notDownloaded => false,
                default              => null,
            },
            userSeeder: match (true) {
                $this->seeding  => true,
                $this->leeching => false,
                default         => null,
            },
            userActive: match (true) {
                $this->seeding  => true,
                $this->leeching => true,
                default         => null,
            },
        ));
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Torrent>
     */
    #[Computed]
    final public function torrents(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $user = auth()->user();

        // Whitelist which columns are allowed to be ordered by
        if (!\in_array($this->sortField, [
            'name',
            'size',
            'seeders',
            'leechers',
            'times_completed',
            'created_at',
            'bumped_at'
        ])) {
            $this->reset('sortField');
        }

        $isSqlAllowed = $user->group->is_modo && $this->driver === 'sql';
        $isRegexAllowed = $isSqlAllowed && $isSqlAllowed;

        if ($isSqlAllowed) {
            $torrents = Torrent::with(['user:id,username,group_id', 'user.group', 'category', 'type', 'resolution'])
                ->withCount([
                    'thanks',
                    'comments',
                    'seeds'   => fn ($query) => $query->where('active', '=', true)->where('visible', '=', true),
                    'leeches' => fn ($query) => $query->where('active', '=', true)->where('visible', '=', true),
                ])
                ->withExists([
                    'bookmarks'          => fn ($query) => $query->where('user_id', '=', $user->id),
                    'freeleechTokens'    => fn ($query) => $query->where('user_id', '=', $user->id),
                    'history as seeding' => fn ($query) => $query->where('user_id', '=', $user->id)
                        ->where('active', '=', 1)
                        ->where('seeder', '=', 1),
                    'history as leeching' => fn ($query) => $query->where('user_id', '=', $user->id)
                        ->where('active', '=', 1)
                        ->where('seeder', '=', 0),
                    'history as not_completed' => fn ($query) => $query->where('user_id', '=', $user->id)
                        ->where('active', '=', 0)
                        ->where('seeder', '=', 0)
                        ->whereNull('completed_at'),
                    'history as not_seeding' => fn ($query) => $query->where('user_id', '=', $user->id)
                        ->where('active', '=', 0)
                        ->where(
                            fn ($query) => $query
                                ->where('seeder', '=', 1)
                                ->orWhereNotNull('completed_at')
                        ),
                ])
                ->selectRaw("
                    CASE
                        WHEN category_id IN (SELECT `id` from `categories` where `movie_meta` = 1) THEN 'movie'
                        WHEN category_id IN (SELECT `id` from `categories` where `tv_meta` = 1) THEN 'tv'
                        WHEN category_id IN (SELECT `id` from `categories` where `game_meta` = 1) THEN 'game'
                        WHEN category_id IN (SELECT `id` from `categories` where `music_meta` = 1) THEN 'music'
                        WHEN category_id IN (SELECT `id` from `categories` where `no_meta` = 1) THEN 'no'
                    END as meta
                ")
                ->where($this->filters()->toSqlQueryBuilder())
                ->latest('sticky')
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(min($this->perPage, 100));

            // See app/Traits/TorrentMeta.php
            $this->scopeMeta($torrents);
        } else {
            $sort = match ($this->sortField) {
                'name'            => 'name',
                'size'            => 'size',
                'seeders'         => 'seeders',
                'leechers'        => 'leechers',
                'times_completed' => 'times_completed',
                'bumped_at'       => 'bumped_at',
                default           => 'created_at',
            };

            $sort .= match ($this->sortDirection) {
                'asc'   => ':asc',
                default => ':desc',
            };

            $results = Http::acceptJson()
                ->withToken(config('meilisearch.key'))
                ->post(config('meilisearch.host').'/indexes/torrents/search', [
                    'q'      => json_encode($this->name),
                    'offset' => $this->perPage ?: 25 * ($this->getPage() ?: 1 - 1),
                    // is limited by `maxTotalHits` config which maxes out at 1000 documents returned
                    'limit'       => $this->perPage ?: 25,
                    'hitsPerPage' => $this->perPage ?: 25,
                    'page'        => $this->getPage() ?: 1,
                    'sort'        => [$sort],
                    'filter'      => $this->filters()->toMeilisearchFilter(),
                ])
                ->json();

            $torrents = [];

            foreach ($results['hits'] ?? [] as $hit) {
                $torrents[] = [
                    'id'               => $hit['id'],
                    'name'             => $hit['name'],
                    'description'      => $hit['description'],
                    'mediainfo'        => $hit['mediainfo'],
                    'bdinfo'           => $hit['bdinfo'],
                    'num_file'         => $hit['num_file'],
                    'folder'           => $hit['folder'],
                    'size'             => $hit['size'],
                    'leechers'         => $hit['leechers'],
                    'seeders'          => $hit['seeders'],
                    'times_completed'  => $hit['times_completed'],
                    'created_at'       => Carbon::createFromTimestamp($hit['created_at']),
                    'bumped_at'        => Carbon::createFromTimestamp($hit['bumped_at']),
                    'fl_until'         => Carbon::createFromTimestamp($hit['fl_until']),
                    'du_until'         => Carbon::createFromTimestamp($hit['du_until']),
                    'user_id'          => $hit['user_id'],
                    'imdb'             => $hit['imdb'],
                    'tvdb'             => $hit['tvdb'],
                    'tmdb'             => $hit['tmdb'],
                    'mal'              => $hit['mal'],
                    'igdb'             => $hit['igdb'],
                    'season_number'    => $hit['season_number'],
                    'episode_number'   => $hit['episode_number'],
                    'stream'           => $hit['stream'],
                    'free'             => $hit['free'],
                    'doubleup'         => $hit['doubleup'],
                    'refundable'       => $hit['refundable'],
                    'highspeed'        => $hit['highspeed'],
                    'featured'         => $hit['featured'],
                    'status'           => $hit['status'],
                    'anon'             => $hit['anon'],
                    'sticky'           => $hit['sticky'],
                    'sd'               => $hit['sd'],
                    'internal'         => $hit['internal'],
                    'release_year'     => $hit['release_year'],
                    'deleted_at'       => Carbon::createFromTimestamp($hit['deleted_at']),
                    'distributor_id'   => $hit['distributor_id'],
                    'region_id'        => $hit['region_id'],
                    'personal_release' => $hit['personal_release'],
                    'info_hash'        => hex2bin($hit['info_hash']),
                    'user'             => [
                        'id'       => $hit['user']['id'],
                        'username' => $hit['user']['username'],
                        'group'    => [
                            'name'   => $hit['user']['group']['name'],
                            'color'  => $hit['user']['group']['color'],
                            'icon'   => $hit['user']['group']['icon'],
                            'effect' => $hit['user']['group']['effect'],
                        ],
                    ],
                    'category_id' => $hit['category']['id'],
                    'category'    => [
                        'id'         => $hit['category']['id'],
                        'name'       => $hit['category']['name'],
                        'image'      => $hit['category']['image'],
                        'icon'       => $hit['category']['icon'],
                        'no_meta'    => $hit['category']['no_meta'],
                        'music_meta' => $hit['category']['music_meta'],
                        'game_meta'  => $hit['category']['game_meta'],
                        'tv_meta'    => $hit['category']['tv_meta'],
                        'movie_meta' => $hit['category']['movie_meta']
                    ],
                    'type_id' => $hit['type']['id'],
                    'type'    => [
                        'id'   => $hit['type']['id'],
                        'name' => $hit['type']['name'],
                    ],
                    'resolution_id' => $hit['resolution']['id'],
                    'resolution'    => [
                        'id'   => $hit['resolution']['id'],
                        'name' => $hit['resolution']['name'],
                    ],
                    'thanks_count'            => 0,
                    'comments_count'          => 0,
                    'seeds_count'             => $hit['seeders'],
                    'leeches_count'           => $hit['leechers'],
                    'bookmarks_exists'        => \in_array($user->id, $hit['bookmark_user_ids'] ?? []),
                    'freeleech_tokens_exists' => \in_array($user->id, $hit['freeleech_token_user_ids'] ?? []),
                    'seeding'                 => \in_array($user->id, $hit['history_active_user_ids'] ?? []) && \in_array($user->id, $hit['history_seeder_user_ids'] ?? []),
                    'leeching'                => \in_array($user->id, $hit['history_active_user_ids'] ?? []) && \in_array($user->id, $hit['history_leecher_user_ids'] ?? []),
                    'not_completed'           => \in_array($user->id, $hit['history_incompleted_user_ids'] ?? []),
                    'not_seeding'             => \in_array($user->id, $hit['history_completed_user_ids'] ?? []),
                    'meta'                    => match (true) {
                        $hit['movie'] !== null => [
                            'id'                => $hit['movie']['id'],
                            'title'             => $hit['movie']['name'],
                            'release_date'      => $hit['movie']['year'],
                            'poster'            => $hit['movie']['poster'],
                            'original_language' => $hit['movie']['original_language'],
                            'adult'             => $hit['movie']['adult'],
                            'genres'            => $hit['movie']['genres'],
                        ],
                        $hit['tv'] !== null => [
                            'id'                => $hit['tv']['id'],
                            'name'              => $hit['tv']['name'],
                            'first_air_date'    => $hit['tv']['year'],
                            'poster'            => $hit['tv']['poster'],
                            'original_language' => $hit['tv']['original_language'],
                            'genres'            => $hit['tv']['genres'],
                        ]
                    }
                ];
            }

            return new LengthAwarePaginator($torrents, $results['estimatedTotalHits'] ?? 0, $this->perPage);
        }
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Torrent>
     */
    #[Computed]
    final public function groupedTorrents()
    {
        $user = auth()->user();

        // Whitelist which columns are allowed to be ordered by
        if (!\in_array($this->sortField, [
            'bumped_at',
            'times_completed',
        ])) {
            $this->reset('sortField');
        }

        $groups = Torrent::query()
            ->select('tmdb')
            ->selectRaw('MAX(sticky) as sticky')
            ->selectRaw('MAX(bumped_at) as bumped_at')
            ->selectRaw('SUM(times_completed) as times_completed')
            ->selectRaw("CASE WHEN category_id IN (SELECT `id` from `categories` where `movie_meta` = 1) THEN 'movie' WHEN category_id IN (SELECT `id` from `categories` where `tv_meta` = 1) THEN 'tv' END as meta")
            ->havingNotNull('meta')
            ->where('tmdb', '!=', 0)
            ->where($this->filters()->toSqlQueryBuilder())
            ->groupBy('tmdb', 'meta')
            ->latest('sticky')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(min($this->perPage, 100));

        $movieIds = $groups->getCollection()->where('meta', '=', 'movie')->pluck('tmdb');
        $tvIds = $groups->getCollection()->where('meta', '=', 'tv')->pluck('tmdb');

        $movies = Movie::with('genres', 'directors')->whereIntegerInRaw('id', $movieIds)->get()->keyBy('id');
        $tv = Tv::with('genres', 'creators')->whereIntegerInRaw('id', $tvIds)->get()->keyBy('id');

        $torrents = Torrent::query()
            ->with(['type:id,name,position', 'resolution:id,name,position'])
            ->withCount([
                'seeds'   => fn ($query) => $query->where('active', '=', true)->where('visible', '=', true),
                'leeches' => fn ($query) => $query->where('active', '=', true)->where('visible', '=', true),
            ])
            ->withExists([
                'freeleechTokens'    => fn ($query) => $query->where('user_id', '=', $user->id),
                'bookmarks'          => fn ($query) => $query->where('user_id', '=', $user->id),
                'history as seeding' => fn ($query) => $query->where('user_id', '=', $user->id)
                    ->where('active', '=', 1)
                    ->where('seeder', '=', 1),
                'history as leeching' => fn ($query) => $query->where('user_id', '=', $user->id)
                    ->where('active', '=', 1)
                    ->where('seeder', '=', 0),
                'history as not_completed' => fn ($query) => $query->where('user_id', '=', $user->id)
                    ->where('active', '=', 0)
                    ->where('seeder', '=', 0)
                    ->whereNull('completed_at'),
                'history as not_seeding' => fn ($query) => $query->where('user_id', '=', $user->id)
                    ->where('active', '=', 0)
                    ->where(
                        fn ($query) => $query
                            ->where('seeder', '=', 1)
                            ->orWhereNotNull('completed_at')
                    ),
            ])
            ->select([
                'id',
                'name',
                'info_hash',
                'size',
                'leechers',
                'seeders',
                'times_completed',
                'category_id',
                'user_id',
                'season_number',
                'episode_number',
                'tmdb',
                'stream',
                'free',
                'doubleup',
                'highspeed',
                'featured',
                'sticky',
                'sd',
                'internal',
                'release_year',
                'created_at',
                'bumped_at',
                'type_id',
                'resolution_id',
                'personal_release',
            ])
            ->selectRaw("CASE WHEN category_id IN (SELECT `id` from `categories` where `movie_meta` = 1) THEN 'movie' WHEN category_id IN (SELECT `id` from `categories` where `tv_meta` = 1) THEN 'tv' END as meta")
            ->where(
                fn ($query) => $query
                    ->where(
                        fn ($query) => $query
                            ->whereIn('category_id', Category::select('id')->where('movie_meta', '=', 1))
                            ->whereIntegerInRaw('tmdb', $movieIds)
                    )
                    ->orWhere(
                        fn ($query) => $query
                            ->whereIn('category_id', Category::select('id')->where('tv_meta', '=', 1))
                            ->whereIntegerInRaw('tmdb', $tvIds)
                    )
            )
            ->where($this->filters()->toSqlQueryBuilder())
            ->get()
            ->groupBy('meta')
            ->map(fn ($movieOrTv, $key) => match ($key) {
                'movie' => $movieOrTv
                    ->groupBy('tmdb')
                    ->map(
                        function ($movie) {
                            $category_id = $movie->first()->category_id;
                            $movie = $movie
                                ->sortBy('type.position')
                                ->values()
                                ->groupBy(fn ($torrent) => $torrent->type->name)
                                ->map(
                                    fn ($torrentsByType) => $torrentsByType
                                        ->sortBy([
                                            ['resolution.position', 'asc'],
                                            ['internal', 'desc'],
                                            ['size', 'desc']
                                        ])
                                        ->values()
                                );
                            $movie->put('category_id', $category_id);

                            return $movie;
                        }
                    ),
                'tv' => $movieOrTv
                    ->groupBy([
                        fn ($torrent) => $torrent->tmdb,
                    ])
                    ->map(
                        function ($tv) {
                            $category_id = $tv->first()->category_id;
                            $tv = $tv
                                ->groupBy(fn ($torrent) => $torrent->season_number === 0 ? ($torrent->episode_number === 0 ? 'Complete Pack' : 'Specials') : 'Seasons')
                                ->map(fn ($packOrSpecialOrSeasons, $key) => match ($key) {
                                    'Complete Pack' => $packOrSpecialOrSeasons
                                        ->sortBy('type.position')
                                        ->values()
                                        ->groupBy(fn ($torrent) => $torrent->type->name)
                                        ->map(
                                            fn ($torrentsByType) => $torrentsByType
                                                ->sortBy([
                                                    ['resolution.position', 'asc'],
                                                    ['internal', 'desc'],
                                                    ['size', 'desc']
                                                ])
                                                ->values()
                                        ),
                                    'Specials' => $packOrSpecialOrSeasons
                                        ->groupBy(fn ($torrent) => 'Special '.$torrent->episode_number)
                                        ->sortKeys(SORT_NATURAL)
                                        ->map(
                                            fn ($episode) => $episode
                                                ->sortBy('type.position')
                                                ->values()
                                                ->groupBy(fn ($torrent) => $torrent->type->name)
                                                ->map(
                                                    fn ($torrentsByType) => $torrentsByType
                                                        ->sortBy([
                                                            ['resolution.position', 'asc'],
                                                            ['internal', 'desc'],
                                                            ['size', 'desc']
                                                        ])
                                                        ->values()
                                                )
                                        ),
                                    'Seasons' => $packOrSpecialOrSeasons
                                        ->groupBy(fn ($torrent) => 'Season '.$torrent->season_number)
                                        ->sortKeys(SORT_NATURAL)
                                        ->map(
                                            fn ($season) => $season
                                                ->groupBy(fn ($torrent) => $torrent->episode_number === 0 ? 'Season Pack' : 'Episodes')
                                                ->map(fn ($packOrEpisodes, $key) => match ($key) {
                                                    'Season Pack' => $packOrEpisodes
                                                        ->sortBy('type.position')
                                                        ->values()
                                                        ->groupBy(fn ($torrent) => $torrent->type->name)
                                                        ->map(
                                                            fn ($torrentsByType) => $torrentsByType
                                                                ->sortBy([
                                                                    ['resolution.position', 'asc'],
                                                                    ['internal', 'desc'],
                                                                    ['size', 'desc']
                                                                ])
                                                                ->values()
                                                        ),
                                                    'Episodes' => $packOrEpisodes
                                                        ->groupBy(fn ($torrent) => 'Episode '.$torrent->episode_number)
                                                        ->sortKeys(SORT_NATURAL)
                                                        ->map(
                                                            fn ($episode) => $episode
                                                                ->sortBy('type.position')
                                                                ->values()
                                                                ->groupBy(fn ($torrent) => $torrent->type->name)
                                                                ->map(
                                                                    fn ($torrentsBytype) => $torrentsBytype
                                                                        ->sortBy([
                                                                            ['resolution.position', 'asc'],
                                                                            ['internal', 'desc'],
                                                                            ['size', 'desc']
                                                                        ])
                                                                        ->values()
                                                                )
                                                        ),
                                                    default => abort(500, 'Group found that isn\'t one of: Season Pack, Episodes.'),
                                                })
                                        ),
                                    default => abort(500, 'Group found that isn\'t one of: Complete Pack, Specials, Seasons'),
                                });
                            $tv->put('category_id', $category_id);

                            return $tv;
                        }
                    ),
                default => abort(500, 'Group found that isn\'t one of: movie, tv'),
            });

        $medias = $groups->through(function ($group) use ($torrents, $movies, $tv) {
            switch ($group->meta) {
                case 'movie':
                    if ($movies->has($group->tmdb)) {
                        $media = $movies[$group->tmdb];
                        $media->setAttribute('meta', 'movie');
                        $media->setRelation('torrents', $torrents['movie'][$group->tmdb] ?? collect());
                        $media->setAttribute('category_id', $media->torrents->pop());
                    } else {
                        $media = null;
                    }

                    break;
                case 'tv':
                    if ($tv->has($group->tmdb)) {
                        $media = $tv[$group->tmdb];
                        $media->setAttribute('meta', 'tv');
                        $media->setRelation('torrents', $torrents['tv'][$group->tmdb] ?? collect());
                        $media->setAttribute('category_id', $media->torrents->pop());
                    } else {
                        $media = null;
                    }

                    break;
                default:
                    $media = null;
            }

            return $media;
        });

        return $medias;
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Torrent>
     */
    #[Computed]
    final public function groupedPosters()
    {
        // Whitelist which columns are allowed to be ordered by
        if (!\in_array($this->sortField, [
            'bumped_at',
            'times_completed',
        ])) {
            $this->reset('sortField');
        }

        $groups = Torrent::query()
            ->select('tmdb')
            ->selectRaw('MAX(sticky) as sticky')
            ->selectRaw('MAX(bumped_at) as bumped_at')
            ->selectRaw('SUM(times_completed) as times_completed')
            ->selectRaw('MIN(category_id) as category_id')
            ->selectRaw("CASE WHEN category_id IN (SELECT `id` from `categories` where `movie_meta` = 1) THEN 'movie' WHEN category_id IN (SELECT `id` from `categories` where `tv_meta` = 1) THEN 'tv' END as meta")
            ->havingNotNull('meta')
            ->where('tmdb', '!=', 0)
            ->where($this->filters()->toSqlQueryBuilder())
            ->groupBy('tmdb', 'meta')
            ->latest('sticky')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(min($this->perPage, 100));

        $movieIds = $groups->getCollection()->where('meta', '=', 'movie')->pluck('tmdb');
        $tvIds = $groups->getCollection()->where('meta', '=', 'tv')->pluck('tmdb');

        $movies = Movie::with('genres', 'directors')->whereIntegerInRaw('id', $movieIds)->get()->keyBy('id');
        $tv = Tv::with('genres', 'creators')->whereIntegerInRaw('id', $tvIds)->get()->keyBy('id');

        $groups = $groups->through(function ($group) use ($movies, $tv) {
            switch ($group->meta) {
                case 'movie':
                    $group->movie = $movies[$group->tmdb] ?? null;

                    break;
                case 'tv':
                    $group->tv = $tv[$group->tmdb] ?? null;

                    break;
            }

            return $group;
        });

        return $groups;
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.torrent-search', [
            'categories'        => $this->categories,
            'types'             => $this->types,
            'resolutions'       => $this->resolutions,
            'genres'            => $this->genres,
            'primaryLanguages'  => $this->primaryLanguages,
            'regions'           => $this->regions,
            'distributors'      => $this->distributors,
            'user'              => auth()->user()->load('group'),
            'personalFreeleech' => $this->personalFreeleech,
            'torrents'          => match ($this->view) {
                'group'  => $this->groupedTorrents,
                'poster' => $this->groupedPosters,
                default  => $this->torrents,
            },
        ]);
    }
}
