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

namespace App\Livewire;

use App\Models\Peer;
use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class UserActive extends Component
{
    use WithPagination;

    public ?User $user = null;

    #[Url]
    public int $perPage = 25;

    #[Url]
    public string $name = '';

    #[Url]
    public string $ip = '';

    #[Url]
    public string $port = '';

    #[Url]
    public string $client = '';

    #[Url]
    public string $seeding = 'any';

    #[Url]
    public string $active = 'include';

    #[Url]
    public string $sortField = 'created_at';

    #[Url]
    public string $sortDirection = 'desc';

    #[Url]
    public $showMorePrecision = false;

    final public function mount($userId): void
    {
        $this->user = User::find($userId);
    }

    final public function updatedPage(): void
    {
        $this->dispatch('paginationChanged');
    }

    final public function updatingSearch(): void
    {
        $this->resetPage();
    }

    final public function getActivesProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Peer::query()
            ->join('torrents', 'peers.torrent_id', '=', 'torrents.id')
            ->select(
                'peers.id',
                'peers.port',
                'peers.agent',
                'peers.uploaded',
                'peers.downloaded',
                'peers.left',
                'peers.seeder',
                'peers.created_at',
                'peers.updated_at',
                'peers.torrent_id',
                'peers.user_id',
                'peers.active',
                'torrents.name',
                'torrents.size',
                'torrents.seeders',
                'torrents.leechers',
                'torrents.times_completed',
            )
            ->selectRaw('INET6_NTOA(ip) as ip')
            ->selectRaw('(1 - (peers.left / NULLIF(torrents.size, 0))) AS progress')
            ->where('peers.user_id', '=', $this->user->id)
            ->when(
                $this->name,
                fn ($query) => $query
                    ->where('name', 'like', '%'.str_replace(' ', '%', $this->name).'%')
            )
            ->when($this->ip !== '', fn ($query) => $query->having('ip', '=', $this->ip))
            ->when($this->port !== '', fn ($query) => $query->where('port', '=', $this->port))
            ->when($this->client !== '', fn ($query) => $query->where('agent', '=', $this->client))
            ->when($this->seeding === 'include', fn ($query) => $query->where('seeder', '=', 1))
            ->when($this->seeding === 'exclude', fn ($query) => $query->where('seeder', '=', 0))
            ->when($this->active === 'include', fn ($query) => $query->where('active', '=', 1))
            ->when($this->active === 'exclude', fn ($query) => $query->where('active', '=', 0))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.user-active', [
            'actives' => $this->actives,
        ]);
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
}
