<?php
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

use App\Models\Category;
use App\Models\Movie;
use App\Models\Person;
use App\Models\Torrent;
use App\Models\Tv;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class TorrentSearch extends Component
{
    use WithPagination;

    public string $name = '';

    public string $description = '';

    public string $mediainfo = '';

    public string $uploader = '';

    public string $keywords = '';

    public string $startYear = '';

    public string $endYear = '';

    public array $categories = [];

    public array $types = [];

    public array $resolutions = [];

    public array $genres = [];

    public array $regions = [];

    public array $distributors = [];

    public string $tmdbId = '';

    public string $imdbId = '';

    public string $tvdbId = '';

    public string $malId = '';

    public string $playlistId = '';

    public string $collectionId = '';

    public string $networkId = '';

    public string $companyId = '';

    public array $free = [];

    public bool $doubleup = false;

    public bool $featured = false;

    public bool $stream = false;

    public bool $sd = false;

    public bool $highspeed = false;

    public bool $bookmarked = false;

    public bool $wished = false;

    public bool $internal = false;

    public bool $personalRelease = false;

    public bool $alive = false;

    public bool $dying = false;

    public bool $dead = false;

    public bool $notDownloaded = false;

    public bool $downloaded = false;

    public bool $seeding = false;

    public bool $leeching = false;

    public bool $incomplete = false;

    public int $perPage = 25;

    public string $sortField = 'bumped_at';

    public string $sortDirection = 'desc';

    public string $view = 'list';

    protected $queryString = [
        'name'            => ['except' => ''],
        'description'     => ['except' => ''],
        'mediainfo'       => ['except' => ''],
        'uploader'        => ['except' => ''],
        'keywords'        => ['except' => ''],
        'startYear'       => ['except' => ''],
        'endYear'         => ['except' => ''],
        'categories'      => ['except' => []],
        'types'           => ['except' => []],
        'resolutions'     => ['except' => []],
        'genres'          => ['except' => []],
        'regions'         => ['except' => []],
        'distributors'    => ['except' => []],
        'tmdbId'          => ['except' => ''],
        'imdbId'          => ['except' => ''],
        'tvdbId'          => ['except' => ''],
        'malId'           => ['except' => ''],
        'playlistId'      => ['except' => ''],
        'collectionId'    => ['except' => ''],
        'companyId'       => ['except' => ''],
        'networkId'       => ['except' => ''],
        'free'            => ['except' => []],
        'doubleup'        => ['except' => false],
        'featured'        => ['except' => false],
        'stream'          => ['except' => false],
        'sd'              => ['except' => false],
        'highspeed'       => ['except' => false],
        'bookmarked'      => ['except' => false],
        'wished'          => ['except' => false],
        'internal'        => ['except' => false],
        'personalRelease' => ['except' => false],
        'alive'           => ['except' => false],
        'dying'           => ['except' => false],
        'dead'            => ['except' => false],
        'downloaded'      => ['except' => false],
        'seeding'         => ['except' => false],
        'leeching'        => ['except' => false],
        'incomplete'      => ['except' => false],
        'page'            => ['except' => 1],
        'perPage'         => ['except' => ''],
        'view'            => ['except' => 'list'],
    ];

    final public function paginationView(): string
    {
        return 'vendor.pagination.livewire-pagination';
    }

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function updatingName(): void
    {
        $this->resetPage();
    }

    final public function updatedView(): void
    {
        $this->perPage = $this->view === 'card' ? 24 : 25;
    }

    final public function getPersonalFreeleechProperty()
    {
        return cache()->get('personal_freeleech:'.auth()->user()->id);
    }

    final public function getTorrentsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $user = auth()->user();
        $isRegexAllowed = $user->group->is_modo;
        $isRegex = fn ($field) => $isRegexAllowed
            && \strlen($field) > 2
            && $field[0] === '/'
            && $field[-1] === '/'
            && @preg_match($field, 'Validate regex') !== false;

        $torrents = Torrent::with(['user:id,username,group_id', 'user.group', 'category', 'type', 'resolution'])
            ->when($this->view === 'list', fn ($query) => $query->withCount(['thanks', 'comments']))
            ->withExists([
                'bookmarks'       => fn ($query) => $query->where('user_id', '=', auth()->id()),
                'freeleechTokens' => fn ($query) => $query->where('user_id', '=', auth()->id()),
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
            ->when($this->name !== '', fn ($query) => $query->ofName($this->name, $isRegex($this->name)))
            ->when($this->description !== '', fn ($query) => $query->ofDescription($this->description, $isRegex($this->description)))
            ->when($this->mediainfo !== '', fn ($query) => $query->ofMediainfo($this->mediainfo, $isRegex($this->mediainfo)))
            ->when($this->uploader !== '', fn ($query) => $query->ofUploader($this->uploader))
            ->when($this->keywords !== '', fn ($query) => $query->ofKeyword(array_map('trim', explode(',', $this->keywords))))
            ->when($this->startYear !== '', fn ($query) => $query->releasedAfterOrIn((int) $this->startYear))
            ->when($this->endYear !== '', fn ($query) => $query->releasedBeforeOrIn((int) $this->endYear))
            ->when($this->categories !== [], fn ($query) => $query->ofCategory($this->categories))
            ->when($this->types !== [], fn ($query) => $query->ofType($this->types))
            ->when($this->resolutions !== [], fn ($query) => $query->ofResolution($this->resolutions))
            ->when($this->genres !== [], fn ($query) => $query->ofGenre($this->genres))
            ->when($this->regions !== [], fn ($query) => $query->ofRegion($this->regions))
            ->when($this->distributors !== [], fn ($query) => $query->ofDistributor($this->distributors))
            ->when($this->tmdbId !== '', fn ($query) => $query->ofTmdb((int) $this->tmdbId))
            ->when($this->imdbId !== '', fn ($query) => $query->ofImdb((int) (preg_match('/tt0*(?=(\d{7,}))/', $this->imdbId, $matches) ? $matches[1] : $this->imdbId)))
            ->when($this->tvdbId !== '', fn ($query) => $query->ofTvdb((int) $this->tvdbId))
            ->when($this->malId !== '', fn ($query) => $query->ofMal((int) $this->malId))
            ->when($this->playlistId !== '', fn ($query) => $query->ofPlaylist((int) $this->playlistId))
            ->when($this->collectionId !== '', fn ($query) => $query->ofCollection((int) $this->collectionId))
            ->when($this->free !== [], fn ($query) => $query->ofFreeleech($this->free))
            ->when($this->doubleup !== false, fn ($query) => $query->doubleup())
            ->when($this->featured !== false, fn ($query) => $query->featured())
            ->when($this->stream !== false, fn ($query) => $query->streamOptimized())
            ->when($this->sd !== false, fn ($query) => $query->sd())
            ->when($this->highspeed !== false, fn ($query) => $query->highspeed())
            ->when($this->bookmarked !== false, fn ($query) => $query->bookmarkedBy($user))
            ->when($this->wished !== false, fn ($query) => $query->wishedBy($user))
            ->when($this->internal !== false, fn ($query) => $query->internal())
            ->when($this->personalRelease !== false, fn ($query) => $query->personalRelease())
            ->when($this->alive !== false, fn ($query) => $query->alive())
            ->when($this->dying !== false, fn ($query) => $query->dying())
            ->when($this->dead !== false, fn ($query) => $query->dead())
            ->when($this->notDownloaded !== false, fn ($query) => $query->notDownloadedBy($user))
            ->when($this->downloaded !== false, fn ($query) => $query->downloadedBy($user))
            ->when($this->seeding !== false, fn ($query) => $query->seededBy($user))
            ->when($this->leeching !== false, fn ($query) => $query->leechedBy($user))
            ->when($this->incomplete !== false, fn ($query) => $query->uncompletedBy($user))
            ->latest('sticky')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $movieIds = $torrents->getCollection()->where('meta', '=', 'movie')->pluck('tmdb');
        $tvIds = $torrents->getCollection()->where('meta', '=', 'tv')->pluck('tmdb');
        $gameIds = $torrents->getCollection()->where('meta', '=', 'game')->pluck('igdb');

        $movies = Movie::with('genres')->whereIntegerInRaw('id', $movieIds)->get()->keyBy('id');
        $tv = Tv::with('genres')->whereIntegerInRaw('id', $tvIds)->get()->keyBy('id');
        if ($gameIds->isNotEmpty()) {
            $games = \MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url', 'image_id']])->whereIntegerInRaw('id', $gameIds);
        }

        $torrents = $torrents->through(function ($torrent) use ($movies, $tv) {
            $torrent->meta = match ($torrent->meta) {
                'movie' => $movies[$torrent->tmdb] ?? null,
                'tv'    => $tv[$torrent->tmdb] ?? null,
                'game'  => $games[$torrent->igdb] ?? null,
                default => null,
            };

            return $torrent;
        });

        return $torrents;
    }

    final public function getGroupedTorrentsProperty()
    {
        $user = auth()->user();
        $isRegexAllowed = $user->group->is_modo;
        $isRegex = fn ($field) => $isRegexAllowed
            && \strlen($field) > 2
            && $field[0] === '/'
            && $field[-1] === '/'
            && @preg_match($field, 'Validate regex') !== false;

        $groups = Torrent::query()
            ->select('tmdb')
            ->selectRaw('MAX(sticky) as sticky')
            ->selectRaw('MAX(bumped_at) as bumped_at')
            ->selectRaw("CASE WHEN category_id IN (SELECT `id` from `categories` where `movie_meta` = 1) THEN 'movie' WHEN category_id IN (SELECT `id` from `categories` where `tv_meta` = 1) THEN 'tv' END as meta")
            ->havingNotNull('meta')
            ->where('tmdb', '!=', 0)
            ->when($this->name !== '', fn ($query) => $query->ofName($this->name, $isRegex($this->name)))
            ->when($this->description !== '', fn ($query) => $query->ofDescription($this->description, $isRegex($this->description)))
            ->when($this->mediainfo !== '', fn ($query) => $query->ofMediainfo($this->mediainfo, $isRegex($this->mediainfo)))
            ->when($this->uploader !== '', fn ($query) => $query->ofUploader($this->uploader))
            ->when($this->keywords !== '', fn ($query) => $query->ofKeyword(array_map('trim', explode(',', $this->keywords))))
            ->when($this->startYear !== '', fn ($query) => $query->releasedAfterOrIn((int) $this->startYear))
            ->when($this->endYear !== '', fn ($query) => $query->releasedBeforeOrIn((int) $this->endYear))
            ->when($this->categories !== [], fn ($query) => $query->ofCategory($this->categories))
            ->when($this->types !== [], fn ($query) => $query->ofType($this->types))
            ->when($this->resolutions !== [], fn ($query) => $query->ofResolution($this->resolutions))
            ->when($this->genres !== [], fn ($query) => $query->ofGenre($this->genres))
            ->when($this->regions !== [], fn ($query) => $query->ofRegion($this->regions))
            ->when($this->distributors !== [], fn ($query) => $query->ofDistributor($this->distributors))
            ->when($this->tmdbId !== '', fn ($query) => $query->ofTmdb((int) $this->tmdbId))
            ->when($this->imdbId !== '', fn ($query) => $query->ofImdb((int) (preg_match('/tt0*(?=(\d{7,}))/', $this->imdbId, $matches) ? $matches[1] : $this->imdbId)))
            ->when($this->tvdbId !== '', fn ($query) => $query->ofTvdb((int) $this->tvdbId))
            ->when($this->malId !== '', fn ($query) => $query->ofMal((int) $this->malId))
            ->when($this->playlistId !== '', fn ($query) => $query->ofPlaylist((int) $this->playlistId))
            ->when($this->collectionId !== '', fn ($query) => $query->ofCollection((int) $this->collectionId))
            ->when($this->companyId !== '', fn ($query) => $query->ofCompany((int) $this->companyId))
            ->when($this->networkId !== '', fn ($query) => $query->ofNetwork((int) $this->networkId))
            ->when($this->free !== [], fn ($query) => $query->ofFreeleech($this->free))
            ->when($this->doubleup !== false, fn ($query) => $query->doubleup())
            ->when($this->featured !== false, fn ($query) => $query->featured())
            ->when($this->stream !== false, fn ($query) => $query->streamOptimized())
            ->when($this->sd !== false, fn ($query) => $query->sd())
            ->when($this->highspeed !== false, fn ($query) => $query->highspeed())
            ->when($this->bookmarked !== false, fn ($query) => $query->bookmarkedBy($user))
            ->when($this->wished !== false, fn ($query) => $query->wishedBy($user))
            ->when($this->internal !== false, fn ($query) => $query->internal())
            ->when($this->personalRelease !== false, fn ($query) => $query->personalRelease())
            ->when($this->alive !== false, fn ($query) => $query->alive())
            ->when($this->dying !== false, fn ($query) => $query->dying())
            ->when($this->dead !== false, fn ($query) => $query->dead())
            ->when($this->notDownloaded !== false, fn ($query) => $query->notDownloadedBy($user))
            ->when($this->downloaded !== false, fn ($query) => $query->downloadedBy($user))
            ->when($this->seeding !== false, fn ($query) => $query->seededBy($user))
            ->when($this->leeching !== false, fn ($query) => $query->leechedBy($user))
            ->when($this->incomplete !== false, fn ($query) => $query->uncompletedBy($user))
            ->groupBy('tmdb', 'meta')
            ->latest('sticky')
            ->orderByDesc('bumped_at')
            ->paginate($this->perPage);

        $movieIds = $groups->getCollection()->where('meta', '=', 'movie')->pluck('tmdb');
        $tvIds = $groups->getCollection()->where('meta', '=', 'tv')->pluck('tmdb');

        $movies = Movie::with('genres')->whereIntegerInRaw('id', $movieIds)->get()->keyBy('id');
        $tv = Tv::with('genres')->whereIntegerInRaw('id', $tvIds)->get()->keyBy('id');

        $torrents = Torrent::query()
            ->with('type:id,name,position', 'resolution:id,name,position')
            ->withExists([
                'freeleechTokens' => fn ($query) => $query->where('user_id', '=', auth()->id()),
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
            ->when($this->name !== '', fn ($query) => $query->ofName($this->name, $isRegex($this->name)))
            ->when($this->description !== '', fn ($query) => $query->ofDescription($this->description, $isRegex($this->description)))
            ->when($this->mediainfo !== '', fn ($query) => $query->ofMediainfo($this->mediainfo, $isRegex($this->mediainfo)))
            ->when($this->uploader !== '', fn ($query) => $query->ofUploader($this->uploader))
            ->when($this->keywords !== '', fn ($query) => $query->ofKeyword(array_map('trim', explode(',', $this->keywords))))
            ->when($this->startYear !== '', fn ($query) => $query->releasedAfterOrIn((int) $this->startYear))
            ->when($this->endYear !== '', fn ($query) => $query->releasedBeforeOrIn((int) $this->endYear))
            ->when($this->categories !== [], fn ($query) => $query->ofCategory($this->categories))
            ->when($this->types !== [], fn ($query) => $query->ofType($this->types))
            ->when($this->resolutions !== [], fn ($query) => $query->ofResolution($this->resolutions))
            ->when($this->genres !== [], fn ($query) => $query->ofGenre($this->genres))
            ->when($this->regions !== [], fn ($query) => $query->ofRegion($this->regions))
            ->when($this->distributors !== [], fn ($query) => $query->ofDistributor($this->distributors))
            ->when($this->tmdbId !== '', fn ($query) => $query->ofTmdb((int) $this->tmdbId))
            ->when($this->imdbId !== '', fn ($query) => $query->ofImdb((int) (preg_match('/tt0*(?=(\d{7,}))/', $this->imdbId, $matches) ? $matches[1] : $this->imdbId)))
            ->when($this->tvdbId !== '', fn ($query) => $query->ofTvdb((int) $this->tvdbId))
            ->when($this->malId !== '', fn ($query) => $query->ofMal((int) $this->malId))
            ->when($this->playlistId !== '', fn ($query) => $query->ofPlaylist((int) $this->playlistId))
            ->when($this->collectionId !== '', fn ($query) => $query->ofCollection((int) $this->collectionId))
            ->when($this->companyId !== '', fn ($query) => $query->ofCompany((int) $this->companyId))
            ->when($this->networkId !== '', fn ($query) => $query->ofNetwork((int) $this->networkId))
            ->when($this->free !== [], fn ($query) => $query->ofFreeleech($this->free))
            ->when($this->doubleup !== false, fn ($query) => $query->doubleup())
            ->when($this->featured !== false, fn ($query) => $query->featured())
            ->when($this->stream !== false, fn ($query) => $query->streamOptimized())
            ->when($this->sd !== false, fn ($query) => $query->sd())
            ->when($this->highspeed !== false, fn ($query) => $query->highspeed())
            ->when($this->bookmarked !== false, fn ($query) => $query->bookmarkedBy($user))
            ->when($this->wished !== false, fn ($query) => $query->wishedBy($user))
            ->when($this->internal !== false, fn ($query) => $query->internal())
            ->when($this->personalRelease !== false, fn ($query) => $query->personalRelease())
            ->when($this->alive !== false, fn ($query) => $query->alive())
            ->when($this->dying !== false, fn ($query) => $query->dying())
            ->when($this->dead !== false, fn ($query) => $query->dead())
            ->when($this->notDownloaded !== false, fn ($query) => $query->notDownloadedBy($user))
            ->when($this->downloaded !== false, fn ($query) => $query->downloadedBy($user))
            ->when($this->seeding !== false, fn ($query) => $query->seededBy($user))
            ->when($this->leeching !== false, fn ($query) => $query->leechedBy($user))
            ->when($this->incomplete !== false, fn ($query) => $query->uncompletedBy($user))
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
                                        ->map(
                                            fn ($season) => $season
                                                ->sortKeys(SORT_NATURAL)
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
                                                })
                                        ),
                                });
                            $tv->put('category_id', $category_id);

                            return $tv;
                        }
                    ),
            });

        $movieDirectors = Person::query()
            ->join('crew_movie', 'person_id', '=', 'person.id')
            ->whereIntegerInRaw('movie_id', $movieIds)
            ->where('department', '=', 'Directing')
            ->where('job', '=', 'Director')
            ->get()
            ->groupBy('movie_id')
            ->map
            ->take(3);

        $medias = $groups->through(function ($group) use ($torrents, $movies, $tv, $movieDirectors) {
            $media = collect(['meta' => 'no']);

            switch ($group->meta) {
                case 'movie':
                    $media = $movies[$group->tmdb] ?? collect();
                    $media->meta = 'movie';
                    $media->torrents = $torrents['movie'][$group->tmdb] ?? collect();
                    $media->category_id = $media->torrents->pop();
                    $media->directors = $movieDirectors[$group->tmdb] ?? collect();
                    break;

                case 'tv':
                    $media = $tv[$group->tmdb] ?? collect();
                    $media->meta = 'tv';
                    $media->torrents = $torrents['tv'][$group->tmdb] ?? collect();
                    $media->category_id = $media->torrents->pop();
                    break;
            }

            return $media;
        });

        return $medias;
    }

    final public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.torrent-search', [
            'user'              => User::with(['group'])->findOrFail(auth()->user()->id),
            'personalFreeleech' => $this->personalFreeleech,
            'torrents'          => $this->view === 'group' ? $this->groupedTorrents : $this->torrents,
        ]);
    }
}
