<div style="display: flex; flex-direction: column; gap: 1rem">
    <section class="panelV2 user-resurrections__filters">
        <h2 class="panel__heading">{{ __('common.search') }}</h2>
        <div class="panel__body">
            <form class="form">
                <div class="form__group">
                    <p class="form__group">
                        <input
                            id="name"
                            wire:model="name"
                            class="form__text"
                            placeholder=" "
                            autofocus=""
                        />
                        <label class="form__label form__label--floating" for="name">
                            {{ __('torrent.name') }}
                        </label>
                    </p>
                </div>
                <p class="form__group">
                    <label
                        style="user-select: none"
                        class="form__label"
                        x-data="{ state: @entangle('rewarded'), ...ternaryCheckbox() }"
                    >
                        <input
                            type="checkbox"
                            class="user-resurrections__checkbox"
                            x-init="updateTernaryCheckboxProperties($el, state)"
                            x-on:click="
                                state = getNextTernaryCheckboxState(state);
                                updateTernaryCheckboxProperties($el, state)
                            "
                            x-bind:checked="state === 'include'"
                        />
                        {{ __('graveyard.rewarded') }}
                    </label>
                </p>
            </form>
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('user.resurrections') }}</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <th
                        class="user-resurrections__name-header"
                        wire:click="sortBy('name')"
                        role="columnheader button"
                    >
                        {{ __('torrent.name') }}
                        @include('livewire.includes._sort-icon', ['field' => 'name'])
                    </th>
                    <th
                        class="user-resurrections__size-header"
                        wire:click="sortBy('size')"
                        role="columnheader button"
                    >
                        {{ __('torrent.size') }}
                        @include('livewire.includes._sort-icon', ['field' => 'size'])
                    </th>
                    <th
                        class="user-resurrections__seeders-header"
                        wire:click="sortBy('seeders')"
                        role="columnheader button"
                    >
                        {{ __('torrent.seeders') }}
                        @include('livewire.includes._sort-icon', ['field' => 'seeders'])
                    </th>
                    <th
                        class="user-resurrections__leechers-header"
                        wire:click="sortBy('leechers')"
                        role="columnheader button"
                    >
                        {{ __('torrent.leechers') }}
                        @include('livewire.includes._sort-icon', ['field' => 'leechers'])
                    </th>
                    <th
                        class="user-resurrections__times-completed-header"
                        wire:click="sortBy('times_completed')"
                        role="columnheader button"
                    >
                        {{ __('torrent.completed') }}
                        @include('livewire.includes._sort-icon', ['field' => 'times_completed'])
                    </th>
                    <th
                        class="user-resurrections__created-at-header"
                        wire:click="sortBy('created_at')"
                        role="columnheader button"
                    >
                        {{ __('graveyard.resurrect-date') }}
                        @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                    </th>
                    <th class="user-resurrections__current-seedtime-header">
                        {{ __('graveyard.current-seedtime') }}
                    </th>
                    <th
                        class="user-resurrections__seedtime-header"
                        wire:click="sortBy('seedtime')"
                        role="columnheader button"
                    >
                        {{ __('graveyard.seedtime-goal') }}
                        @include('livewire.includes._sort-icon', ['field' => 'seedtime'])
                    </th>
                    <th
                        class="user-resurrections__rewarded-header"
                        wire:click="sortBy('rewarded')"
                        role="columnheader button"
                    >
                        {{ __('graveyard.rewarded') }}
                        @include('livewire.includes._sort-icon', ['field' => 'rewarded'])
                    </th>
                    <th class="user-resurrections__actions-header">
                        {{ __('common.actions') }}
                    </th>
                </thead>
                <tbody>
                    @foreach ($resurrections as $resurrection)
                        <tr>
                            <td class="user-resurrections__name">
                                <a
                                    href="{{ route('torrents.show', ['id' => $resurrection->torrent->id]) }}"
                                >
                                    {{ $resurrection->torrent->name }}
                                </a>
                            </td>
                            <td class="user-resurrections__size">
                                {{ App\Helpers\StringHelper::formatBytes($resurrection->torrent->size) }}
                            </td>
                            <td class="user-resurrections__seeders">
                                <a
                                    href="{{ route('peers', ['id' => $resurrection->torrent->id]) }}"
                                >
                                    <span class="text-green">
                                        {{ $resurrection->torrent->seeders }}
                                    </span>
                                </a>
                            </td>
                            <td class="user-resurrections__leechers">
                                <a
                                    href="{{ route('peers', ['id' => $resurrection->torrent->id]) }}"
                                >
                                    <span class="text-red">
                                        {{ $resurrection->torrent->leechers }}
                                    </span>
                                </a>
                            </td>
                            <td class="user-resurrections__times_completed">
                                <a
                                    href="{{ route('history', ['id' => $resurrection->torrent->id]) }}"
                                >
                                    <span class="text-orange">
                                        {{ $resurrection->torrent->times_completed }}
                                    </span>
                                </a>
                            </td>
                            <td class="user-resurrections__created-at">
                                {{ $resurrection->created_at->diffForHumans() }}
                            </td>
                            <td class="user-resurrections__current-seedtime">
                                @php
                                    $history = App\Models\History::select(['seedtime'])
                                        ->where('user_id', '=', $user->id)
                                        ->where('torrent_id', '=', $resurrection->torrent_id)
                                        ->first()
                                @endphp

                                {{ empty($history) ? '0' : App\Helpers\StringHelper::timeElapsed($history->seedtime) }}
                            </td>
                            <td class="user-resurrections__seedtime">
                                {{ App\Helpers\StringHelper::timeElapsed($resurrection->seedtime) }}
                            </td>
                            <td class="user-resurrections__rewarded">
                                @if ($resurrection->rewarded)
                                    <i
                                        class="{{ config('other.font-awesome') }} fa-check text-green"
                                    ></i>
                                @else
                                    <i
                                        class="{{ config('other.font-awesome') }} fa-times text-red"
                                    ></i>
                                @endif
                            </td>
                            <td class="user-resurrections__actions">
                                <menu class="data-table__actions">
                                    <li class="data-table__action">
                                        <form
                                            action="{{ route('users.resurrections.destroy', ['user' => auth()->user(), 'resurrection' => $resurrection]) }}"
                                            method="POST"
                                            x-data="confirmation"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                x-on:click.prevent="confirmAction"
                                                data-b64-deletion-message="{{ base64_encode('Are you sure you want to cancel this resurrection: ' . $resurrection->torrent->name . '?') }}"
                                                class="form__button form__button--text"
                                            >
                                                {{ __('common.cancel') }}
                                            </button>
                                        </form>
                                    </li>
                                </menu>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $resurrections->links('partials.pagination') }}
    </section>
    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
        function ternaryCheckbox() {
            return {
                updateTernaryCheckboxProperties(el, state) {
                    el.indeterminate = state === 'exclude';
                    el.checked = state === 'include';
                },
                getNextTernaryCheckboxState(state) {
                    return state === 'include'
                        ? 'exclude'
                        : state === 'exclude'
                          ? 'any'
                          : 'include';
                },
            };
        }
    </script>
</div>
