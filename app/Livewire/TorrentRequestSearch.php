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

namespace App\Livewire;

use App\Models\TorrentRequest;
use App\Models\TorrentRequestBounty;
use App\Models\TorrentRequestClaim;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TorrentRequestSearch extends Component
{
    use WithPagination;

    #[Url]
    public string $name = '';

    #[Url]
    public string $requestor = '';

    #[Url]
    public array $categories = [];

    #[Url]
    public array $types = [];

    #[Url]
    public array $resolutions = [];

    #[Url]
    public array $genres = [];

    #[Url]
    public string $tmdbId = '';

    #[Url]
    public string $imdbId = '';

    #[Url]
    public string $tvdbId = '';

    #[Url]
    public string $malId = '';

    #[Url]
    public $unfilled;

    #[Url]
    public $claimed;

    #[Url]
    public $pending;

    #[Url]
    public $filled;

    #[Url]
    public $myRequests;

    #[Url]
    public $myClaims;

    #[Url]
    public $myVoted;

    #[Url]
    public $myFilled;

    public int $perPage = 25;

    #[Url]
    public string $sortField = 'created_at';

    #[Url]
    public string $sortDirection = 'desc';

    public bool $showFilters = false;

    final public function updatedPage(): void
    {
        $this->dispatch('paginationChanged');
    }

    final public function toggleShowFilters(): void
    {
        $this->showFilters = ! $this->showFilters;
    }

    final public function getTorrentRequestStatProperty(): ?object
    {
        return DB::table('requests')
            ->selectRaw('count(*) as total')
            ->selectRaw('count(case when filled_by is not null then 1 end) as filled')
            ->selectRaw('count(case when filled_by is null then 1 end) as unfilled')
            ->first();
    }

    final public function getTorrentRequestBountyStatProperty(): ?object
    {
        return DB::table('requests')
            ->selectRaw('coalesce(sum(bounty), 0) as total')
            ->selectRaw('coalesce(sum(case when filled_by is not null then bounty end), 0) as claimed')
            ->selectRaw('coalesce(sum(case when filled_by is null then bounty end), 0) as unclaimed')
            ->first();
    }

    final public function getTorrentRequestsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
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
            ->when($this->categories !== [], fn ($query) => $query->ofCategory($this->categories))
            ->when($this->types !== [], fn ($query) => $query->ofType($this->types))
            ->when($this->resolutions !== [], fn ($query) => $query->ofResolution($this->resolutions))
            ->when($this->tmdbId !== '', fn ($query) => $query->ofTmdb((int) $this->tmdbId))
            ->when($this->imdbId !== '', fn ($query) => $query->ofImdb((int) (preg_match('/tt0*(?=(\d{7,}))/', $this->imdbId, $matches) ? $matches[1] : $this->imdbId)))
            ->when($this->tvdbId !== '', fn ($query) => $query->ofTvdb((int) $this->tvdbId))
            ->when($this->malId !== '', fn ($query) => $query->ofMal((int) $this->malId))
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
        return view('livewire.torrent-request-search', [
            'user'                     => auth()->user(),
            'torrentRequests'          => $this->torrentRequests,
            'torrentRequestStat'       => $this->torrentRequestStat,
            'torrentRequestBountyStat' => $this->torrentRequestBountyStat,
        ]);
    }
}
