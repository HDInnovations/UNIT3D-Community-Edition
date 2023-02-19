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
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Person;
use App\Models\Torrent;
use App\Models\Tv;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TorrentGroupSearch extends Component
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
    ];

    protected array $rules = [
        'genres.*' => 'exists:genres,id',
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

    final public function getPersonalFreeleechProperty()
    {
        return cache()->get('personal_freeleech:'.auth()->user()->id);
    }

    final public function getTorrentsProperty()
    {
        $user = auth()->user();
        $isRegexAllowed = $user->group->is_modo;
        $isRegex = fn ($field) => $isRegexAllowed
            && \strlen($field) > 2
            && $field[0] === '/'
            && $field[-1] === '/'
            && @preg_match($field, 'Validate regex') !== false;

        $media = DB::query()
            ->fromSub(
                TV::query()
                    ->select([
                        'id',
                        'tmdb_id',
                        'imdb_id',
                        'name',
                        'overview',
                        'poster',
                        'first_air_date as release_date',
                        'created_at',
                        'updated_at',
                    ])
                    ->selectRaw("'tv' as meta")
                    ->whereHas(
                        'torrents',
                        fn ($query) => $query
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
                    )
                    ->unionAll(
                        Movie::query()
                            ->select([
                                'id',
                                'tmdb_id',
                                'imdb_id',
                                'title as name',
                                'overview',
                                'poster',
                                'release_date',
                                'created_at',
                                'updated_at',
                            ])
                            ->selectRaw("'movie' as meta")
                            ->whereHas(
                                'torrents',
                                fn ($query) => $query
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
                            )
                    ),
                'medias'
            )
            ->orderByDesc(
                Torrent::query()
                    ->select('bumped_at')
                    ->whereColumn('torrents.tmdb', 'medias.id')
                    ->whereRaw("CASE WHEN category_id IN (SELECT `id` from `categories` where `movie_meta` = 1) THEN 'movie' WHEN category_id IN (SELECT `id` from `categories` where `tv_meta` = 1) THEN 'tv' ELSE 'no' END = medias.meta")
                    ->orderByDesc('bumped_at')
                    ->take(1)
            )
            ->paginate($this->perPage);

        $movieIds = $media->getCollection()->where('meta', '=', 'movie')->pluck('id');
        $tvIds = $media->getCollection()->where('meta', '=', 'tv')->pluck('id');

        $torrents = Torrent::query()
            ->with('type:id,name,position', 'resolution:id,name,position')
            ->withExists([
                'freeleechTokens' => fn ($query) => $query->where('user_id', '=', auth()->id()),
            ])
            ->where(
                fn ($query) => $query
                    ->where(
                        fn ($query) => $query
                            ->whereIn('category_id', Category::where('movie_meta', '=', 1)->select('id'))
                            ->whereIntegerInRaw('tmdb', $movieIds)
                    )
                    ->orWhere(
                        fn ($query) => $query
                            ->whereIn('category_id', Category::where('tv_meta', '=', 1)->select('id'))
                            ->whereIntegerInRaw('tmdb', $tvIds)
                    )
            )
            ->select([
                'id',
                'name',
                'info_hash',
                'size',
                'leechers',
                'seeders',
                'times_completed',
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
            ->selectRaw("CASE WHEN category_id IN (SELECT `id` from `categories` where `movie_meta` = 1) THEN 'movie' WHEN category_id IN (SELECT `id` from `categories` where `tv_meta` = 1) THEN 'tv' ELSE 'no' END as meta")
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
            ->get()
            ->groupBy('meta')
            ->map(fn ($torrent, $meta) => match ($meta) {
                'movie' => $torrent
                    ->groupBy('tmdb')
                    ->map(
                        fn ($movie) => $movie
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
                'tv' => $torrent
                    ->groupBy([
                        fn ($torrent) => $torrent->tmdb,
                    ])
                    ->map(
                        fn ($tv) => $tv
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
                                            ->sortKeys()
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
                                                    ->sortKeys()
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
                            })
                    ),
            });

        $movieGenres = Genre::query()
            ->join('genre_movie', 'genre_movie.genre_id', '=', 'genres.id')
            ->whereIntegerInRaw('movie_id', $movieIds)
            ->get()
            ->groupBy('movie_id')
            ->map
            ->take(3);

        $tvGenres = Genre::query()
            ->join('genre_tv', 'genre_tv.genre_id', '=', 'genres.id')
            ->whereIntegerInRaw('tv_id', $tvIds)
            ->get()
            ->groupBy('tv_id')
            ->map
            ->take(3);

        $movieDirectors = Person::query()
            ->join('crew_movie', 'person_id', '=', 'person.id')
            ->whereIntegerInRaw('movie_id', $movieIds)
            ->where('department', '=', 'Directing')
            ->where('job', '=', 'Director')
            ->get()
            ->groupBy('movie_id')
            ->map
            ->take(3);

        return $media->through(function ($media) use ($torrents, $movieGenres, $tvGenres, $movieDirectors) {
            switch ($media->meta) {
                case 'movie':
                    $media->torrents = $torrents['movie'][$media->id] ?? [];
                    $media->genres = $movieGenres[$media->id] ?? [];
                    $media->directors = $movieDirectors[$media->id] ?? [];
                    break;

                case 'tv':
                    $media->torrents = $torrents['tv'][$media->id] ?? [];
                    $media->genres = $tvGenres[$media->id] ?? [];
                    break;

                default:
                    $media->torrents = [];
                    $media->genres = [];
            }

            return $media;
        });
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.torrent-group-search', [
            'user'              => User::with(['group'])->findOrFail(auth()->user()->id),
            'medias'            => $this->torrents,
            'personalFreeleech' => $this->personalFreeleech,
        ]);
    }
}
