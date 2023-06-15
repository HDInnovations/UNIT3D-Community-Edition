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

use App\Models\Invite;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class InviteLogSearch extends Component
{
    use WithPagination;

    public string $sender = '';

    public string $email = '';

    public string $code = '';

    public string $receiver = '';

    public int $perPage = 25;

    protected $queryString = [
        'sender'   => ['except' => ''],
        'email'    => ['except' => ''],
        'code'     => ['except' => ''],
        'receiver' => ['except' => ''],
        'page'     => ['except' => 1],
        'perPage'  => ['except' => ''],
    ];

    final public function paginationView(): string
    {
        return 'vendor.pagination.livewire-pagination';
    }

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function getInvitesProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Invite::withTrashed()
            ->with(['sender', 'receiver'])
            ->when($this->sender, fn ($query) => $query->whereIn('user_id', User::select('id')->where('username', '=', $this->sender)))
            ->when($this->email, fn ($query) => $query->where('email', 'LIKE', '%'.$this->email.'%'))
            ->when($this->code, fn ($query) => $query->where('code', 'LIKE', '%'.$this->code.'%'))
            ->when($this->receiver, fn ($query) => $query->whereIn('accepted_by', User::select('id')->where('username', '=', $this->receiver)))
            ->latest()
            ->paginate($this->perPage);
    }

    final public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.invite-log-search', [
            'invites' => $this->invites,
        ]);
    }
}
