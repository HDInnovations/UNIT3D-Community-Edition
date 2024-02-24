<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Livewire;

use App\Models\History;
use App\Models\User;
use App\Traits\CastLivewireProperties;
use App\Traits\LivewireSort;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class LeakerSearch extends Component
{
    use CastLivewireProperties;
    use LivewireSort;
    use WithPagination;

    public int $perPage = 50;

    public string $torrentIds = '';

    public ?int $minutesLeakedWithin = null;

    public string $agent = '';

    public string $sortField = 'leak_count';

    public string $sortDirection = 'desc';

    /**
     * @var array<mixed>
     */
    protected $queryString = [
        'page'                => ['except' => 1],
        'perPage'             => ['except' => 25],
        'torrentIds'          => ['except' => ''],
        'agent'               => ['except' => ''],
        'minutesLeakedWithin' => ['except' => null],
        'sortField'           => ['except' => ''],
        'sortDirection'       => ['except' => 'desc'],
    ];

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<User>
     */
    final public function getLeakersProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return History::query()
            ->select([
                'history.user_id',
                DB::raw('count(*) as leak_count'),
            ])
            ->with([
                'user' => [
                    'history' => fn ($query) => $query->select(['user_id', 'agent'])->distinct()->orderBy('agent'),
                    'peers'   => fn ($query) => $query->select(['user_id', 'ip'])->distinct()->orderBy('ip'),
                ],
            ])
            ->join('torrents', 'history.torrent_id', '=', 'torrents.id')
            ->whereIn('history.torrent_id', array_filter(array_map('trim', explode(',', $this->torrentIds))))
            ->when(
                $this->minutesLeakedWithin !== null,
                fn ($query) => $query->whereRaw('history.created_at < TIMESTAMPADD(MINUTE, '.(int) $this->minutesLeakedWithin.', torrents.created_at)')
            )
            ->whereColumn('history.user_id', '<>', 'torrents.user_id')
            ->groupBy('history.user_id')
            ->when($this->agent !== '', fn ($query) => $query->where('agent', 'LIKE', $this->agent))
            ->when($this->sortField !== '', fn ($query) => $query->orderBy($this->sortField, $this->sortDirection))
            ->paginate($this->perPage);
    }

    final public function getTorrentIdCountProperty(): int
    {
        return \count(array_filter(array_map('trim', explode(',', $this->torrentIds))));
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.leaker-search', [
            'leakers'        => $this->leakers,
            'torrentIdCount' => $this->torrentIdCount,
        ]);
    }
}
