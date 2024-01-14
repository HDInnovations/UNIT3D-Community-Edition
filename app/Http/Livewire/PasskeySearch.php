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

use App\Models\Passkey;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * @property \Illuminate\Contracts\Pagination\LengthAwarePaginator<Passkey> $passkeys
 */
class PasskeySearch extends Component
{
    use WithPagination;

    public string $username = '';

    public string $passkey = '';

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public int $perPage = 25;

    /**
     * @var array<string, mixed>
     */
    protected $queryString = [
        'username' => ['except' => ''],
        'passkey'  => ['except' => ''],
        'page'     => ['except' => 1],
        'perPage'  => ['except' => ''],
    ];

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Passkey>
     */
    final public function getPasskeysProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Passkey::with([
            'user' => fn ($query) => $query->withTrashed()->with('group'),
        ])
            ->when($this->username, fn ($query) => $query->whereIn('user_id', User::withTrashed()->select('id')->where('username', '=', $this->username)))
            ->when($this->passkey, fn ($query) => $query->where('content', 'LIKE', '%'.$this->passkey.'%'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    final public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.passkey-search', [
            'passkeys' => $this->passkeys,
        ]);
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
}
