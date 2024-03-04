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

use App\Models\Warning;
use App\Traits\LivewireSort;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class WarningLogSearch extends Component
{
    use LivewireSort;
    use WithPagination;

    #[Url]
    public string $sender = '';

    #[Url]
    public string $receiver = '';

    #[Url]
    public string $torrent = '';

    #[Url]
    public string $reason = '';

    #[Url]
    public bool $show = false;

    #[Url]
    public int $perPage = 25;

    #[Url]
    public string $sortField = 'created_at';

    #[Url]
    public string $sortDirection = 'desc';

    final public function updatedPage(): void
    {
        $this->dispatch('paginationChanged');
    }

    final public function toggleProperties($property): void
    {
        if ($property === 'show') {
            $this->show = !$this->show;
        }
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Warning>
     */
    #[Computed]
    final public function warnings(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Warning::query()
            ->with(['warneduser.group', 'staffuser.group', 'torrenttitle'])
            ->when($this->sender, fn ($query) => $query->whereRelation('staffuser', 'username', '=', $this->sender))
            ->when($this->receiver, fn ($query) => $query->whereRelation('warneduser', 'username', '=', $this->receiver))
            ->when($this->torrent, fn ($query) => $query->whereRelation('torrenttitle', 'name', 'LIKE', '%'.$this->torrent.'%'))
            ->when($this->reason, fn ($query) => $query->where('reason', 'LIKE', '%'.$this->reason.'%'))
            ->when($this->show === true, fn ($query) => $query->onlyTrashed())
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    final public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.warning-log-search', [
            'warnings' => $this->warnings,
        ]);
    }
}
