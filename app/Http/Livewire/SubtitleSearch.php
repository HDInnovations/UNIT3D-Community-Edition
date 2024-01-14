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

use App\Models\Subtitle;
use App\Models\Torrent;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class SubtitleSearch extends Component
{
    use WithPagination;

    public int $perPage = 25;

    public string $search = '';

    public array $categories = [];

    public string $language = '';

    public string $username = '';

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function updatingSearch(): void
    {
        $this->resetPage();
    }

    final public function getSubtitlesProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Subtitle::with(['user.group', 'torrent.category', 'language'])
            ->whereHas('torrent')
            ->when($this->search, fn ($query) => $query->where('title', 'like', '%'.$this->search.'%'))
            ->when($this->categories, function ($query) {
                $torrents = Torrent::whereIntegerInRaw('category_id', $this->categories)->pluck('id');

                return $query->whereIntegerInRaw('torrent_id', $torrents);
            })
            ->when($this->language, fn ($query) => $query->where('language_id', '=', $this->language))
            ->when(
                $this->username,
                fn ($query) => $query
                    ->whereIn('user_id', User::select('id')->where('username', '=', $this->username))
                    ->when(
                        !auth()->user()->group->is_modo,
                        fn ($query) => $query->where(fn ($query) => $query->where('anon', '=', false)->orWhere('user_id', '=', auth()->id()))
                    )
            )
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
        return view('livewire.subtitle-search', [
            'subtitles' => $this->subtitles,
        ]);
    }
}
