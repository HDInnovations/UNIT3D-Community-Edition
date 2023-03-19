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

use App\Models\Peer;
use Livewire\Component;
use Livewire\WithPagination;

class PeerSearch extends Component
{
    use WithPagination;

    public int $perPage = 25;
    public string $ip = '';
    public string $port = '';
    public string $agent = '';
    public string $torrent = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'page'    => ['except' => 1],
        'perPage' => ['except' => ''],
    ];

    final public function paginationView(): string
    {
        return 'vendor.pagination.livewire-pagination';
    }

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function updatingIp(): void
    {
        $this->resetPage();
    }

    final public function updatingPort(): void
    {
        $this->resetPage();
    }

    final public function updatingAgent(): void
    {
        $this->resetPage();
    }

    final public function updatingTorrent(): void
    {
        $this->resetPage();
    }

    final public function getPeersProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Peer::query()
            ->select([
                'peers.torrent_id',
                'peers.user_id',
                'peers.uploaded',
                'peers.downloaded',
                'peers.left',
                'peers.port',
                'peers.agent',
                'peers.created_at',
                'peers.updated_at',
                'peers.seeder',
                'torrents.size',
                'torrents.name',
            ])
            ->selectRaw('INET6_NTOA(ip) as ip')
            ->with('user', 'user.group')
            ->join('users', 'users.id', '=', 'peers.user_id')
            ->join('torrents', 'torrents.id', '=', 'peers.torrent_id')
            ->when($this->ip !== '', fn ($query) => $query->having('ip', 'LIKE', '%'.$this->ip.'%'))
            ->when($this->port !== '', fn ($query) => $query->where('port', '=', $this->port))
            ->when($this->agent !== '', fn ($query) => $query->where('agent', 'LIKE', '%'.$this->agent.'%'))
            ->when($this->torrent !== '', fn ($query) => $query->where('torrents.name', 'LIKE', '%'.str_replace(' ', '%', $this->torrent).'%'))
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
        return view('livewire.peer-search', [
            'peers' => $this->peers,
        ]);
    }
}
