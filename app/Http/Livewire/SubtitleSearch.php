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
use App\Traits\LivewireSort;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class SubtitleSearch extends Component
{
    use LivewireSort;
    use WithPagination;

    #TODO: Update URL attributes once Livewire 3 fixes upstream bug. See: https://github.com/livewire/livewire/discussions/7746

    #[Url(history: true)]
    public int $perPage = 25;

    #[Url(history: true)]
    public string $search = '';

    /**
     * @var string[]
     */
    #[Url(history: true)]
    public array $categories = [];

    #[Url(history: true)]
    public string $language = '';

    #[Url(history: true)]
    public string $username = '';

    #[Url(history: true)]
    public string $sortField = 'created_at';

    #[Url(history: true)]
    public string $sortDirection = 'desc';

    final public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Subtitle>
     */
    #[Computed]
    final public function subtitles(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
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

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.subtitle-search', [
            'subtitles' => $this->subtitles,
        ]);
    }
}
