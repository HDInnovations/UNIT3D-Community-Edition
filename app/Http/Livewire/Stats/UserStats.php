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
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Livewire\Stats;

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy(isolate: true)]
class UserStats extends Component
{
    #[Computed(cache: true, seconds: 10 * 60)]
    final public function allUsers(): int
    {
        return User::query()->withTrashed()->count();
    }

    #[Computed(cache: true, seconds: 10 * 60)]
    final public function activeUsers(): int
    {
        return User::query()->whereHas('group', fn ($query) => $query->whereNotIn('slug', ['banned', 'validating', 'disabled', 'pruned']))->count();
    }

    #[Computed(cache: true, seconds: 10 * 60)]
    final public function disableUsers(): int
    {
        return User::query()->whereRelation('group', 'slug', '=', 'disabled')->count();
    }

    #[Computed(cache: true, seconds: 10 * 60)]
    final public function prunedUsers(): int
    {
        return User::query()->onlyTrashed()->whereRelation('group', 'slug', '=', 'pruned')->count();
    }

    #[Computed(cache: true, seconds: 10 * 60)]
    final public function bannedUsers(): int
    {
        return User::query()->whereRelation('group', 'slug', '=', 'banned')->count();
    }

    #[Computed(cache: true, seconds: 10 * 60)]
    final public function usersActiveToday(): int
    {
        return User::query()->where('last_action', '>', now()->subDay())->count();
    }

    #[Computed(cache: true, seconds: 10 * 60)]
    final public function usersActiveThisWeek(): int
    {
        return User::query()->where('last_action', '>', now()->subWeek())->count();
    }

    #[Computed(cache: true, seconds: 10 * 60)]
    final public function usersActiveThisMonth(): int
    {
        return User::query()->where('last_action', '>', now()->subMonth())->count();
    }

    final public function placeholder(): string
    {
        return <<<'HTML'
        <section class="panelV2">
            <h2 class="panel__heading">{{ __('common.users') }}</h2>
            <div class="panel__body">Loading...</div>
        </section>
        HTML;
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.stats.user-stats', [
            'all_user'                => $this->allUsers,
            'active_user'             => $this->activeUsers,
            'disabled_user'           => $this->disableUsers,
            'pruned_user'             => $this->prunedUsers,
            'banned_user'             => $this->bannedUsers,
            'users_active_today'      => $this->usersActiveToday,
            'users_active_this_week'  => $this->usersActiveThisWeek,
            'users_active_this_month' => $this->usersActiveThisMonth,
        ]);
    }
}
