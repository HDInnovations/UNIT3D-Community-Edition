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

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Resolution;
use App\Models\TorrentRequest;
use App\Models\TorrentRequestBounty;
use App\Models\TorrentRequestClaim;
use App\Models\Type;
use App\Traits\CastLivewireProperties;
use App\Traits\LivewireSort;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TorrentRequestSearch extends Component
{
    use CastLivewireProperties;
    use LivewireSort;
    use WithPagination;

    #TODO: Update URL attributes once Livewire 3 fixes upstream bug. See: https://github.com/livewire/livewire/discussions/7746

    #[Url(history: true)]
    public string $name = '';

    #[Url(history: true)]
    public string $requestor = '';

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
     * @var array<string>
     */
    #[Url(history: true)]
    public array $primaryLanguageNames = [];

    #[Url(history: true)]
    public ?int $tmdbId = null;

    #[Url(history: true)]
    public string $imdbId = '';

    #[Url(history: true)]
    public ?int $tvdbId = null;

    #[Url(history: true)]
    public ?int $malId = null;

    #[Url(history: true)]
    public bool $unfilled = false;

    #[Url(history: true)]
    public bool $claimed = false;

    #[Url(history: true)]
    public bool $pending = false;

    #[Url(history: true)]
    public bool $filled = false;

    #[Url(history: true)]
    public bool $myRequests = false;

    #[Url(history: true)]
    public bool $myClaims = false;

    #[Url(history: true)]
    public bool $myVoted = false;

    #[Url(history: true)]
    public bool $myFilled = false;

    #[Url(history: true)]
    public int $perPage = 25;

    #[Url(history: true)]
    public string $sortField = 'created_at';

    #[Url(history: true)]
    public string $sortDirection = 'desc';

    final public function updating(string $field, mixed &$value): void
    {
        $this->castLivewireProperties($field, $value);
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

    #[Computed]
    final public function torrentRequestStat(): ?object
    {
        return DB::table('requests')
            ->selectRaw('count(*) as total')
            ->selectRaw('count(case when filled_by is not null then 1 end) as filled')
            ->selectRaw('count(case when filled_by is null then 1 end) as unfilled')
            ->first();
    }

    #[Computed]
    final public function torrentRequestBountyStat(): ?object
    {
        return DB::table('requests')
            ->selectRaw('coalesce(sum(bounty), 0) as total')
            ->selectRaw('coalesce(sum(case when filled_by is not null then bounty end), 0) as claimed')
            ->selectRaw('coalesce(sum(case when filled_by is null then bounty end), 0) as unclaimed')
            ->first();
    }

    /**
     * @return \Illuminate\Pagination\LengthAwarePaginator<TorrentRequest>
     */
    #[Computed]
    final public function torrentRequests(): \Illuminate\Pagination\LengthAwarePaginator
    {
        $user = auth()->user();
        $isRegexAllowed = $user->group->is_modo;
        $isRegex = fn ($field) => $isRegexAllowed
            && \strlen((string) $field) > 2
            && $field[0] === '/'
            && $field[-1] === '/'
            && @preg_match($field, 'Validate regex') !== false;

        return TorrentRequest::with(['user:id,username,group_id', 'user.group', 'category', 'type', 'resolution'])
            ->withCount(['comments'])
            ->when($this->name !== '', fn ($query) => $query->ofName($this->name, $isRegex($this->name)))
            ->when($this->requestor !== '', fn ($query) => $query->ofUploader($this->requestor))
            ->when($this->categoryIds !== [], fn ($query) => $query->ofCategory($this->categoryIds))
            ->when($this->typeIds !== [], fn ($query) => $query->ofType($this->typeIds))
            ->when($this->resolutionIds !== [], fn ($query) => $query->ofResolution($this->resolutionIds))
            ->when($this->tmdbId !== null, fn ($query) => $query->ofTmdb($this->tmdbId))
            ->when($this->imdbId !== '', fn ($query) => $query->ofImdb((int) (preg_match('/tt0*(?=(\d{7,}))/', $this->imdbId, $matches) ? $matches[1] : $this->imdbId)))
            ->when($this->tvdbId !== null, fn ($query) => $query->ofTvdb((int) $this->tvdbId))
            ->when($this->malId !== null, fn ($query) => $query->ofMal((int) $this->malId))
            ->when($this->genreIds !== [], fn ($query) => $query->ofGenre($this->genreIds))
            ->when($this->primaryLanguageNames !== [], fn ($query) => $query->ofPrimaryLanguage($this->primaryLanguageNames))
            ->when($this->unfilled || $this->claimed || $this->pending || $this->filled, function ($query): void {
                $query->where(function ($query): void {
                    $query->where(function ($query): void {
                        if ($this->unfilled) {
                            $query->whereNull('torrent_id')->whereNull('claimed');
                        }
                    })
                        ->orWhere(function ($query): void {
                            if ($this->claimed) {
                                $query->whereNotNull('claimed')->whereNull('torrent_id')->whereNull('approved_by');
                            }
                        })
                        ->orWhere(function ($query): void {
                            if ($this->pending) {
                                $query->whereNotNull('torrent_id')->whereNull('approved_by');
                            }
                        })
                        ->orWhere(function ($query): void {
                            if ($this->filled) {
                                $query->whereNotNull('torrent_id')->whereNotNull('approved_by');
                            }
                        });
                });
            })
            ->when($this->myRequests, function ($query) use ($user): void {
                $query->where('user_id', '=', $user->id);
            })
            ->when($this->myClaims, function ($query) use ($user): void {
                $requestClaims = TorrentRequestClaim::where('user_id', '=', $user->id)->pluck('request_id');
                $query->whereIntegerInRaw('id', $requestClaims)->whereNull('torrent_id')->whereNull('approved_by');
            })
            ->when($this->myVoted, function ($query) use ($user): void {
                $requestVotes = TorrentRequestBounty::where('user_id', '=', $user->id)->pluck('requests_id');
                $query->whereIntegerInRaw('id', $requestVotes);
            })
            ->when($this->myFilled, function ($query) use ($user): void {
                $query->where('filled_by', '=', $user->id);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.torrent-request-search', [
            'user'                     => auth()->user(),
            'categories'               => $this->categories,
            'types'                    => $this->types,
            'resolutions'              => $this->resolutions,
            'genres'                   => $this->genres,
            'primaryLanguages'         => $this->primaryLanguages,
            'torrentRequests'          => $this->torrentRequests,
            'torrentRequestStat'       => $this->torrentRequestStat,
            'torrentRequestBountyStat' => $this->torrentRequestBountyStat,
        ]);
    }
}
