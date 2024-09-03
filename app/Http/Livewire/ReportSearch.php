<?php

declare(strict_types=1);

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

use App\Models\Report;
use App\Traits\LivewireSort;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ReportSearch extends Component
{
    use LivewireSort;
    use WithPagination;

    #TODO: Update URL attributes once Livewire 3 fixes upstream bug. See: https://github.com/livewire/livewire/discussions/7746

    #[Url(history: true)]
    public string $type = '';

    #[Url(history: true)]
    public string $title = '';

    #[Url(history: true)]
    public string $reported = '';

    #[Url(history: true)]
    public string $reporter = '';

    #[Url(history: true)]
    public string $judge = '';

    #[Url(history: true)]
    public string $sortField = 'created_at';

    #[Url(history: true)]
    public string $sortDirection = 'desc';

    #[Url(history: true)]
    public int $perPage = 25;

    final public function updatingShow(): void
    {
        $this->resetPage();
    }

    /**
     * @return \Illuminate\Pagination\LengthAwarePaginator<Report>
     */
    #[Computed]
    final public function reports(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return Report::query()
            ->with([
                'reported' => fn ($query) => $query->with('group'),
                'reporter' => fn ($query) => $query->with('group'),
                'staff'    => fn ($query) => $query->with('group'),
            ])
            ->orderBy('solved')
            ->latest()
            ->when($this->type !== '', fn ($query) => $query->where('type', $this->type))
            ->when($this->title !== '', fn ($query) => $query->where('title', 'LIKE', '%'.$this->title.'%'))
            ->when($this->reported !== '', fn ($query) => $query->whereHas('reported', fn ($subQuery) => $subQuery->where('username', 'LIKE', '%'.$this->reported.'%')))
            ->when($this->reporter !== '', fn ($query) => $query->whereHas('reporter', fn ($subQuery) => $subQuery->where('username', 'LIKE', '%'.$this->reporter.'%')))
            ->when($this->judge !== '', fn ($query) => $query->whereHas('staff', fn ($subQuery) => $subQuery->where('username', 'LIKE', '%'.$this->judge.'%')))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.report-search', [
            'reports' => $this->reports,
        ]);
    }
}
