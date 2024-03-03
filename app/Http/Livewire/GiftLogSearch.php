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

use App\Models\Gift;
use App\Traits\LivewireSort;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * @property \Illuminate\Contracts\Pagination\LengthAwarePaginator $gifts
 */
class GiftLogSearch extends Component
{
    use LivewireSort;
    use WithPagination;

    public string $sender = '';

    public string $receiver = '';

    public string $comment = '';

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public int $perPage = 25;

    /**
     * @var array<mixed>
     */
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

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Gift>
     */
    final public function getGiftsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Gift::with([
            'sender'    => fn ($query) => $query->withTrashed()->with('group'),
            'recipient' => fn ($query) => $query->withTrashed()->with('group'),
        ])
            ->when($this->sender, fn ($query) => $query->whereRelation('sender', 'username', '=', $this->sender))
            ->when($this->receiver, fn ($query) => $query->whereRelation('recipient', 'username', '=', $this->receiver))
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
}
