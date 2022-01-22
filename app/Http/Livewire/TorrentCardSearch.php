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

namespace App\Http\Livewire;

use App\Models\Bookmark;
use App\Models\Category;
use App\Models\History;
use App\Models\Keyword;
use App\Models\PersonalFreeleech;
use App\Models\PlaylistTorrent;
use App\Models\Torrent;
use App\Models\User;
use App\Models\Wish;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TorrentCardSearch extends Component
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

    public $free0;

    public $free25;

    public $free50;

    public $free75;

    public $free100;

    public $doubleup;

    public $featured;

    public $stream;

    public $sd;

    public $highspeed;

    public $bookmarked;

    public $wished;

    public $internal;

    public $personalRelease;

    public $alive;

    public $dying;

    public $dead;

    public $notDownloaded;

    public $downloaded;

    public $seeding;

    public $leeching;

    public $incomplete;

    public int $perPage = 24;

    public string $sortField = 'bumped_at';

    public string $sortDirection = 'desc';

    protected $queryString = [
        'name'             => ['except' => ''],
        'description'      => ['except' => ''],
        'mediainfo'        => ['except' => ''],
        'uploader'         => ['except' => ''],
        'keywords'         => ['except' => ''],
        'startYear'        => ['except' => ''],
        'endYear'          => ['except' => ''],
        'categories'       => ['except' => []],
        'types'            => ['except' => []],
        'resolutions'      => ['except' => []],
        'genres'           => ['except' => []],
        'regions'          => ['except' => []],
        'distributors'     => ['except' => []],
        'tmdbId'           => ['except' => ''],
        'imdbId'           => ['except' => ''],
        'tvdbId'           => ['except' => ''],
        'malId'            => ['except' => ''],
        'playlistId'       => ['except' => ''],
        'collectionId'     => ['except' => ''],
        'free0'            => ['except' => false],
        'free25'           => ['except' => false],
        'free50'           => ['except' => false],
        'free75'           => ['except' => false],
        'free100'          => ['except' => false],
        'doubleup'         => ['except' => false],
        'featured'         => ['except' => false],
        'stream'           => ['except' => false],
        'sd'               => ['except' => false],
        'highspeed'        => ['except' => false],
        'bookmarked'       => ['except' => false],
        'wished'           => ['except' => false],
        'internal'         => ['except' => false],
        'personalRelease'  => ['except' => false],
        'alive'            => ['except' => false],
        'dying'            => ['except' => false],
        'dead'             => ['except' => false],
        'downloaded'       => ['except' => false],
        'seeding'          => ['except' => false],
        'leeching'         => ['except' => false],
        'incomplete'       => ['except' => false],
        'sortField'        => ['except' => 'bumped_at'],
        'sortDirection'    => ['except' => 'desc'],
        'page'             => ['except' => 1],
        'perPage'          => ['except' => ''],
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

    final public function getTorrentsStatProperty(): ?object
    {
        return DB::table('torrents')
            ->selectRaw('count(*) as total')
            ->selectRaw('count(case when seeders > 0 then 1 end) as alive')
            ->selectRaw('count(case when seeders = 0 then 1 end) as dead')
            ->first();
    }

    final public function getPersonalFreeleechProperty()
    {
        return PersonalFreeleech::where('user_id', '=', \auth()->user()->id)->first();
    }

    final public function getTorrentsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Torrent::with(['user:id,username,group_id', 'user.group', 'category', 'type', 'resolution'])
            ->withCount(['thanks', 'comments'])
            ->when($this->name, function ($query) {
                $terms = \explode(' ', $this->name);
                $search = '';
                foreach ($terms as $term) {
                    $search .= '%'.$term.'%';
                }

                $query->where('name', 'LIKE', $search);
            })
            ->when($this->description, function ($query) {
                $query->where('description', 'LIKE', '%'.$this->description.'%');
            })
            ->when($this->mediainfo, function ($query) {
                $query->where('mediainfo', 'LIKE', '%'.$this->mediainfo.'%');
            })
            ->when($this->uploader, function ($query) {
                $match = User::where('username', '=', $this->uploader)->first();
                if ($match) {
                    $query->where('user_id', '=', $match->id)->where('anon', '=', 0);
                }
            })
            ->when($this->keywords, function ($query) {
                $keywords = self::parseKeywords($this->keywords);
                $keyword = Keyword::whereIn('name', $keywords)->pluck('torrent_id');
                $query->whereIntegerInRaw('id', $keyword);
            })
            ->when($this->startYear && $this->endYear, function ($query) {
                $query->whereBetween('release_year', [$this->startYear, $this->endYear]);
            })
            ->when($this->categories, function ($query) {
                $query->whereIntegerInRaw('category_id', $this->categories);
            })
            ->when($this->types, function ($query) {
                $query->whereIntegerInRaw('type_id', $this->types);
            })
            ->when($this->resolutions, function ($query) {
                $query->whereIntegerInRaw('resolution_id', $this->resolutions);
            })
            ->when($this->genres, function ($query) {
                $this->validate();

                $tvCollection = DB::table('genre_tv')->whereIntegerInRaw('genre_id', $this->genres)->pluck('tv_id');
                $movieCollection = DB::table('genre_movie')->whereIntegerInRaw('genre_id', $this->genres)->pluck('movie_id');
                $mergedCollection = $tvCollection->merge($movieCollection);

                $query->whereRaw("tmdb in ('".\implode("','", $mergedCollection->toArray())."')"); // Protected with Validation that IDs passed are not malicious
            })
            ->when($this->regions, function ($query) {
                $query->whereIntegerInRaw('region_id', $this->regions);
            })
            ->when($this->distributors, function ($query) {
                $query->whereIntegerInRaw('distributor_id', $this->distributors);
            })
            ->when($this->tmdbId === '0' || $this->tmdbId, function ($query) {
                $query->where('tmdb', '=', $this->tmdbId);
            })
            ->when($this->imdbId === '0' || $this->imdbId, function ($query) {
                if (\preg_match('/tt0*?(?=(\d{7,8}))/', $this->imdbId, $matches)) {
                    $query->where('imdb', '=', $matches[1]);
                } else {
                    $query->where('imdb', '=', $this->imdbId);
                }
            })
            ->when($this->tvdbId === '0' || $this->tvdbId, function ($query) {
                $query->where('tvdb', '=', $this->tvdbId);
            })
            ->when($this->malId === '0' || $this->malId, function ($query) {
                $query->where('mal', '=', $this->malId);
            })
            ->when($this->playlistId, function ($query) {
                $playlist = PlaylistTorrent::where('playlist_id', '=', $this->playlistId)->pluck('torrent_id');
                $query->whereIntegerInRaw('id', $playlist);
            })
            ->when($this->collectionId, function ($query) {
                $categories = Category::where('movie_meta', '=', 1)->pluck('id');
                $collection = DB::table('collection_movie')->where('collection_id', '=', $this->collectionId)->pluck('movie_id');
                $query->whereIntegerInRaw('category_id', $categories)->whereIntegerInRaw('tmdb', $collection);
            })
            ->when($this->free0 === '0' || $this->free0, function ($query) {
                $query->where('free', '=', 0);
            })
            ->when($this->free25, function ($query) {
                $query->orWhere('free', '=', 25);
            })
            ->when($this->free50, function ($query) {
                $query->orWhere('free', '=', 50);
            })
            ->when($this->free75, function ($query) {
                $query->orWhere('free', '=', 75);
            })
            ->when($this->free100, function ($query) {
                $query->orWhere('free', '=', 100);
            })
            ->when($this->doubleup, function ($query) {
                $query->where('doubleup', '=', 1);
            })
            ->when($this->featured, function ($query) {
                $query->where('featured', '=', 1);
            })
            ->when($this->stream, function ($query) {
                $query->where('stream', '=', 1);
            })
            ->when($this->sd, function ($query) {
                $query->where('sd', '=', 1);
            })
            ->when($this->highspeed, function ($query) {
                $query->where('highspeed', '=', 1);
            })
            ->when($this->bookmarked, function ($query) {
                $bookmarks = Bookmark::where('user_id', '=', \auth()->user()->id)->pluck('torrent_id');
                $query->whereIntegerInRaw('id', $bookmarks);
            })
            ->when($this->wished, function ($query) {
                $wishes = Wish::where('user_id', '=', \auth()->user()->id)->pluck('tmdb');
                $query->whereIn('tmdb', $wishes);
            })
            ->when($this->internal, function ($query) {
                $query->where('internal', '=', 1);
            })
            ->when($this->personalRelease, function ($query) {
                $query->where('personal_release', '=', 1);
            })
            ->when($this->alive, function ($query) {
                $query->where('seeders', '>=', 1);
            })
            ->when($this->dying, function ($query) {
                $query->where('seeders', '=', 1)->where('times_completed', '>=', 3);
            })
            ->when($this->dead, function ($query) {
                $query->where('seeders', '=', 0);
            })
            ->when($this->notDownloaded, function ($query) {
                $history = History::where('user_id', '=', \auth()->user()->id)->pluck('info_hash')->toArray();
                if (! $history || ! \is_array($history)) {
                    $history = [];
                }

                $query->whereNotIn('info_hash', $history);
            })
            ->when($this->downloaded, function ($query) {
                $query->whereHas('history', function ($query) {
                    $query->where('user_id', '=', \auth()->user()->id);
                });
            })
            ->when($this->seeding, function ($query) {
                $query->whereHas('history', function ($q) {
                    $q->where('user_id', '=', \auth()->user()->id)->where('active', '=', true)->where('seeder', '=', true);
                });
            })
            ->when($this->leeching, function ($query) {
                $query->whereHas('history', function ($q) {
                    $q->where('user_id', '=', \auth()->user()->id)->where('active', '=', true)->where('seedtime', '=', '0');
                });
            })
            ->when($this->incomplete, function ($query) {
                $query->whereHas('history', function ($q) {
                    $q->where('user_id', '=', \auth()->user()->id)->where('active', '=', false)->where('seeder', '=', false)->where('seedtime', '=', '0');
                });
            })
            ->orderByDesc('sticky')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    private static function parseKeywords($text): array
    {
        $parts = \explode(', ', $text);
        $result = [];
        foreach ($parts as $part) {
            $part = \trim($part);
            if ($part !== '') {
                $result[] = $part;
            }
        }

        return $result;
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
        return \view('livewire.torrent-card-search', [
            'user'              => User::with(['history:id,seeder,active,completed_at,info_hash', 'group'])->findOrFail(\auth()->user()->id),
            'torrents'          => $this->torrents,
            'torrentsStat'      => $this->torrentsStat,
            'personalFreeleech' => $this->personalFreeleech,
        ]);
    }
}
