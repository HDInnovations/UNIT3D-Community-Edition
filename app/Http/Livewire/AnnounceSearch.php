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
use App\Traits\LivewireSort;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * @property \Illuminate\Contracts\Pagination\LengthAwarePaginator<Announce> $announces
 */
class AnnounceSearch extends Component
{
    use LivewireSort;
    use WithPagination;

    #TODO: Update URL attributes once Livewire 3 fixes upstream bug. See: https://github.com/livewire/livewire/discussions/7746

    #[Url(history: true)]
    public string $torrentId = '';

    #[Url(history: true)]
    public string $userId = '';

    #[Url(history: true)]
    public string $sortField = '';

    #[Url(history: true)]
    public string $sortDirection = 'desc';

    #[Url(history: true)]
    public int $perPage = 50;

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
    #[Computed]
    final public function announces(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Announce::query()
            ->when($this->torrentId !== '', fn ($query) => $query->where('torrent_id', '=', $this->torrentId))
            ->when($this->userId !== '', fn ($query) => $query->where('user_id', '=', $this->userId))
            ->when($this->sortField !== '', fn ($query) => $query->orderBy($this->sortField, $this->sortDirection))
            ->paginate($this->perPage);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.announce-search', [
            'announces' => $this->announces,
        ]);
    }
}
