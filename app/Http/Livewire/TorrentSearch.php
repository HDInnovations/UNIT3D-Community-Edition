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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Closure;

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
    public bool $trumpable = false;

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
    #[Computed(seconds: 3600, cache: true)]
    final public function categories(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::query()->orderBy('position')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Type>
     */
    #[Computed(seconds: 3600, cache: true)]
    final public function types(): \Illuminate\Database\Eloquent\Collection
    {
        return Type::query()->orderBy('position')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Resolution>
     */
    #[Computed(seconds: 3600, cache: true)]
    final public function resolutions(): \Illuminate\Database\Eloquent\Collection
    {
        return Resolution::query()->orderBy('position')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Genre>
     */
    #[Computed(seconds: 3600, cache: true)]
    final public function genres(): \Illuminate\Database\Eloquent\Collection
    {
        return Genre::query()->orderBy('name')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Region>
     */
    #[Computed(seconds: 3600, cache: true)]
    final public function regions(): \Illuminate\Database\Eloquent\Collection
    {
        return Region::query()->orderBy('position')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Distributor>
     */
    #[Computed(seconds: 3600, cache: true)]
    final public function distributors(): \Illuminate\Database\Eloquent\Collection
    {
        return Distributor::query()->orderBy('name')->get();
    }

    /**
     * @return \Illuminate\Support\Collection<int, Movie>
     */
    #[Computed(seconds: 3600, cache: true)]
    final public function primaryLanguages(): \Illuminate\Support\Collection
    {
        return Movie::query()
            ->select('original_language')
            ->distinct()
            ->orderBy('original_language')
            ->pluck('original_language');
    }

    /**
     * @return Closure(Builder<Torrent>): Builder<Torrent>
     */
    final public function filters(): Closure
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
            trumpable: $this->trumpable,
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
        ))->toSqlQueryBuilder();
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
                'trump',
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
            ->where($this->filters())
            ->latest('sticky')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(min($this->perPage, 100));

        // See app/Traits/TorrentMeta.php
        $this->scopeMeta($torrents);

        return $torrents;
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
            ->where($this->filters())
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
            ->where($this->filters())
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
            ->where($this->filters())
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
