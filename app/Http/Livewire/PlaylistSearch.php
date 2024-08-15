<?php

declare(strict_types=1);

/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.tx
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Livewire;

use App\Models\Playlist;
use App\Traits\LivewireSort;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PlaylistSearch extends Component
{
    use LivewireSort;
    use WithPagination;

    #TODO: Update URL attributes once Livewire 3 fixes upstream bug. See: https://github.com/livewire/livewire/discussions/7746

    #[Url(history: true)]
    public string $name = '';

    #[Url(history: true)]
    public int $perPage = 24;

    #[Url(history: true)]
    public string $sortField = 'name';

    #[Url(history: true)]
    public string $username = '';

    #[Url(history: true)]
    public string $sortDirection = 'desc';

    final public function updatingName(): void
    {
        $this->resetPage();
    }

    /**
     * @return \Illuminate\Pagination\LengthAwarePaginator<Playlist>
     */
    #[Computed]
    final public function playlists()
    {
        return Playlist::with([
            'user:id,username,group_id,image' => ['group'],
        ])
            ->withCount('torrents')
            ->when(
                ! auth()->user()->group->is_modo,
                fn ($query) => $query
                    ->where(
                        fn ($query) => $query
                            ->where('is_private', '=', 0)
                            ->orWhere(fn ($query) => $query->where('is_private', '=', 1)->where('user_id', '=', auth()->id()))
                    )
            )
            ->when($this->name !== '', fn ($query) => $query->where('name', '=', $this->name))
            ->when($this->username !== '', fn ($query) => $query->whereRelation('user', 'username', '=', $this->username))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(min($this->perPage, 100));
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.playlist-search', [
            'playlists' => $this->playlists,
        ]);
    }
}
