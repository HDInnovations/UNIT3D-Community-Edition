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
use App\Traits\LivewireSort;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class InviteLogSearch extends Component
{
    use LivewireSort;
    use WithPagination;

    #TODO: Update URL attributes once Livewire 3 fixes upstream bug. See: https://github.com/livewire/livewire/discussions/7746

    #[Url(history: true)]
    public string $sender = '';

    #[Url(history: true)]
    public string $email = '';

    #[Url(history: true)]
    public string $code = '';

    #[Url(history: true)]
    public string $receiver = '';

    #[Url(history: true)]
    public string $custom = '';

    #[Url(history: true)]
    public string $groupBy = 'none';

    #[Url(history: true)]
    public int $threshold = 25;

    #[Url(history: true)]
    public string $sortField = 'created_at';

    #[Url(history: true)]
    public string $sortDirection = 'desc';

    #[Url(history: true)]
    public int $perPage = 25;

    final public function mount(): void
    {
        $this->sortField = match ($this->groupBy) {
            'user_id' => 'created_at_max',
            default   => 'created_at',
        };
    }

    final public function updatingGroupBy($value): void
    {
        $this->sortField = match ($value) {
            'user_id' => 'created_at_max',
            default   => 'created_at',
        };
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Invite>
     */
    #[Computed]
    final public function invites(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Invite::withTrashed()
            ->with(['sender.group', 'receiver.group'])
            ->when($this->sender, fn ($query) => $query->whereRelation('sender', 'username', '=', $this->sender))
            ->when($this->email, fn ($query) => $query->where('email', 'LIKE', '%'.$this->email.'%'))
            ->when($this->code, fn ($query) => $query->where('code', 'LIKE', '%'.$this->code.'%'))
            ->when($this->receiver, fn ($query) => $query->whereRelation('sender', 'username', '=', $this->receiver))
            ->when($this->custom, fn ($query) => $query->where('custom', 'LIKE', '%'.$this->custom.'%'))
            ->when(
                $this->groupBy === 'user_id',
                fn ($query) => $query->groupBy('user_id')
                    ->from('invites as i1')
                    ->select([
                        'user_id',
                        DB::raw('MIN(created_at) as created_at_min'),
                        DB::raw('FROM_UNIXTIME(AVG(UNIX_TIMESTAMP(created_at))) as created_at_avg'),
                        DB::raw('MAX(created_at) as created_at_max'),
                        DB::raw('COUNT(*) as sent_count'),
                        DB::raw('SUM(IF(accepted_by IS NULL, 0, 1)) as accepted_by_count'),
                        DB::raw("
                            (select
                                count(*)
                            from
                                users
                            where
                                id in (select accepted_by from invites i2 where i2.user_id = i1.user_id)
                                and group_id in (select id from `groups` where slug in ('banned', 'pruned', 'disabled'))
                            ) as inactive_count
                        "),
                        DB::raw("
                            100.0 * 
                            (select
                                count(*)
                            from
                                users
                            where
                                id in (select accepted_by from invites i2 where i2.user_id = i1.user_id)
                                and group_id in (select id from `groups` where slug in ('banned', 'pruned', 'disabled'))
                            )
                            / COUNT(*) as inactive_ratio
                        "),
                    ])
                    ->withCasts([
                        'created_at_min' => 'datetime',
                        'created_at_avg' => 'datetime',
                        'created_at_max' => 'datetime',
                    ])
            )
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    final public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.invite-log-search', [
            'invites' => $this->invites,
        ]);
    }
}
