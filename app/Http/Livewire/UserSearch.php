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

use App\Models\Group;
use App\Models\User;
use App\Traits\CastLivewireProperties;
use App\Traits\LivewireSort;
use Livewire\Component;
use Livewire\WithPagination;

class UserSearch extends Component
{
    use CastLivewireProperties;
    use LivewireSort;
    use WithPagination;

    public bool $show = false;

    public int $perPage = 25;

    public string $username = '';

    public string $email = '';

    public string $rsskey = '';

    public string $apikey = '';

    public string $passkey = '';

    public ?int $groupId = null;

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    /**
     * @var array<mixed>
     */
    protected $queryString = [
        'username' => ['except' => ''],
        'email'    => ['except' => ''],
        'apikey'   => ['except' => ''],
        'rsskey'   => ['except' => ''],
        'passkey'  => ['except' => ''],
        'show'     => ['except' => false],
        'page'     => ['except' => 1],
        'perPage'  => ['except' => ''],
    ];

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function updatingShow(): void
    {
        $this->resetPage();
    }

    final public function toggleProperties($property): void
    {
        if ($property === 'show') {
            $this->show = !$this->show;
        }
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<User>
     */
    final public function getUsersProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return User::query()
            ->with('group')
            ->when($this->username !== '', fn ($query) => $query->where('username', 'LIKE', '%'.$this->username.'%'))
            ->when($this->email !== '', fn ($query) => $query->where('username', 'LIKE', '%'.$this->email.'%'))
            ->when($this->rsskey !== '', fn ($query) => $query->where('username', 'LIKE', '%'.$this->rsskey.'%'))
            ->when($this->apikey !== '', fn ($query) => $query->where('username', 'LIKE', '%'.$this->apikey.'%'))
            ->when($this->passkey !== '', fn ($query) => $query->where('username', 'LIKE', '%'.$this->passkey.'%'))
            ->when($this->groupId !== null, fn ($query) => $query->where('group_id', '=', $this->groupId))
            ->when($this->show === true, fn ($query) => $query->onlyTrashed())
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    /**
     * @return \Illuminate\Support\Collection<int, Group>
     */
    final public function getGroupsProperty()
    {
        return Group::orderBy('position')->get();
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.user-search', [
            'users'  => $this->users,
            'groups' => $this->groups,
        ]);
    }
}
