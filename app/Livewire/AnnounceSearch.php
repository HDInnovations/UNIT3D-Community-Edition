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

use App\Models\Announce;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * @property \Illuminate\Contracts\Pagination\LengthAwarePaginator<Announce> $announces
 */
class AnnounceSearch extends Component
{
    use WithPagination;

    public int $perPage = 50;

    public string $torrentId = '';

    public string $userId = '';

    public string $sortField = '';

    public string $sortDirection = 'desc';

    /**
     * @var array<string, mixed>
     */
    protected $queryString = [
        'page'          => ['except' => 1],
        'perPage'       => ['except' => 25],
        'torrentId'     => ['except' => ''],
        'userId'        => ['except' => ''],
        'sortField'     => ['except' => ''],
        'sortDirection' => ['except' => 'desc'],
    ];

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function updatingUserId(): void
    {
        $this->resetPage();
    }

    final public function updatingTorrentId(): void
    {
        $this->resetPage();
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Announce>
     */
    final public function getAnnouncesProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Announce::query()
            ->when($this->torrentId !== '', fn ($query) => $query->where('torrent_id', '=', $this->torrentId))
            ->when($this->userId !== '', fn ($query) => $query->where('user_id', '=', $this->userId))
            ->when($this->sortField !== '', fn ($query) => $query->orderBy($this->sortField, $this->sortDirection))
            ->paginate($this->perPage);
    }

    final public function sortBy(string $field): void
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
        return view('livewire.announce-search', [
            'announces' => $this->announces,
        ]);
    }
}
