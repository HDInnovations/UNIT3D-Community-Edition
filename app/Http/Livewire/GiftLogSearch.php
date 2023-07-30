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

use App\Models\BonTransactions;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * @property \Illuminate\Contracts\Pagination\LengthAwarePaginator $gifts
 */
class GiftLogSearch extends Component
{
    use WithPagination;

    public string $sender = '';

    public string $receiver = '';

    public string $comment = '';

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public int $perPage = 25;

    protected $queryString = [
        'sender'   => ['except' => ''],
        'receiver' => ['except' => ''],
        'page'     => ['except' => 1],
        'perPage'  => ['except' => ''],
    ];

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function getGiftsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return BonTransactions::with([
            'sender'   => fn ($query) => $query->withTrashed()->with('group'),
            'receiver' => fn ($query) => $query->withTrashed()->with('group'),
        ])
            ->where('name', '=', 'gift')
            ->when($this->sender, fn ($query) => $query->whereIn('sender_id', User::select('id')->where('username', '=', $this->sender)))
            ->when($this->receiver, fn ($query) => $query->whereIn('receiver_id', User::select('id')->where('username', '=', $this->receiver)))
            ->when($this->comment, fn ($query) => $query->where('comment', 'LIKE', '%'.$this->comment.'%'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    final public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.gift-log-search', [
            'gifts' => $this->gifts,
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
