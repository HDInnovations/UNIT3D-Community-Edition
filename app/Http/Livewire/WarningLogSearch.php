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

use App\Models\Torrent;
use App\Models\User;
use App\Models\Warning;
use Livewire\Component;
use Livewire\WithPagination;

class WarningLogSearch extends Component
{
    use WithPagination;

    public string $sender = '';

    public string $receiver = '';

    public string $torrent = '';

    public string $reason = '';

    public bool $show = false;

    public int $perPage = 25;

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    protected $queryString = [
        'sender'   => ['except' => ''],
        'receiver' => ['except' => ''],
        'torrent'  => ['except' => ''],
        'reason'   => ['except' => ''],
        'show'     => ['except' => false],
        'page'     => ['except' => 1],
        'perPage'  => ['except' => ''],
    ];

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function toggleProperties($property): void
    {
        if ($property === 'show') {
            $this->show = !$this->show;
        }
    }

    final public function getWarningsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Warning::query()
            ->with(['warneduser.group', 'staffuser.group', 'torrenttitle'])
            ->when($this->sender, fn ($query) => $query->whereIn('warned_by', User::select('id')->where('username', '=', $this->sender)))
            ->when($this->receiver, fn ($query) => $query->whereIn('user_id', User::select('id')->where('username', '=', $this->receiver)))
            ->when($this->torrent, fn ($query) => $query->whereIn('torrent', Torrent::select('id')->where('name', 'LIKE', '%'.$this->torrent.'%')))
            ->when($this->reason, fn ($query) => $query->where('reason', 'LIKE', '%'.$this->reason.'%'))
            ->when($this->show === true, fn ($query) => $query->onlyTrashed())
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

    final public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.warning-log-search', [
            'warnings' => $this->warnings,
        ]);
    }
}
