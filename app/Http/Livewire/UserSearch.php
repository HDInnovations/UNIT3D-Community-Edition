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
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class UserSearch extends Component
{
    use CastLivewireProperties;
    use LivewireSort;
    use WithPagination;

    #[Url]
    public bool $show = false;

    #[Url]
    public int $perPage = 25;

    #[Url]
    public string $username = '';

    #[Url]
    public string $email = '';

    #[Url]
    public string $rsskey = '';

    #[Url]
    public string $apikey = '';

    #[Url]
    public string $passkey = '';

    #[Url]
    public ?int $groupId = null;

    #[Url]
    public string $sortField = 'created_at';

    #[Url]
    public string $sortDirection = 'desc';

    final public function updatedPage(): void
    {
        $this->dispatch('paginationChanged');
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
    #[Computed]
    final public function users(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return User::query()
            ->with('group')
            ->when($this->username !== '', fn ($query) => $query->where('username', 'LIKE', '%'.$this->username.'%'))
            ->when($this->email !== '', fn ($query) => $query->where('email', 'LIKE', '%'.$this->email.'%'))
            ->when($this->rsskey !== '', fn ($query) => $query->where('rsskey', 'LIKE', '%'.$this->rsskey.'%'))
            ->when($this->apikey !== '', fn ($query) => $query->where('api_token', 'LIKE', '%'.$this->apikey.'%'))
            ->when($this->passkey !== '', fn ($query) => $query->where('passkey', 'LIKE', '%'.$this->passkey.'%'))
            ->when($this->groupId !== null, fn ($query) => $query->where('group_id', '=', $this->groupId))
            ->when($this->show === true, fn ($query) => $query->onlyTrashed())
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    /**
     * @return \Illuminate\Support\Collection<int, Group>
     */
    #[Computed]
    final public function groups()
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
