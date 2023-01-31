<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('common.users') }}</h2>
        <div class="panel__actions">
            <div class="panel__action">
                <div class="form__group">
                    <input
                        id="show"
                        class="form__checkbox"
                        type="checkbox"
                        wire:click="toggleProperties('show')"
                    >
                    <label class="form__label" for="show">
                        Show Soft Deletes
                    </label>
                </div>
            </div>
            <div class="panel__action">
                <div class="form__group">
                    <select
                        id="quantity"
                        class="form__select"
                        wire:model="perPage"
                        required
                    >
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    <label class="form__label form__label--floating">
                        {{ __('common.quantity') }}
                    </label>
                </div>
            </div>
            <div class="panel__action">
                <div class="form__group">
                    <input
                        id="search"
                        class="form__text"
                        type="text"
                        wire:model="search"
                        placeholder=""
                    />
                    <label class="form__label form__label--floating">
                        {{ __('user.search') }}
                    </label>
                </div>
            </div>
        </div>
    </header>
    <div class="data-table-wrapper">
        <table class="data-table">
            <tbody>
            <tr>
                <th>Avatar</th>
                <th wire:click="sortBy('username')" role="columnheader button">
                    {{ __('common.username') }}
                    @include('livewire.includes._sort-icon', ['field' => 'username'])
                </th>
                <th wire:click="sortBy('group_id')" role="columnheader button">
                    {{ __('common.group') }}
                    @include('livewire.includes._sort-icon', ['field' => 'group_id'])
                </th>
                <th wire:click="sortBy('email')" role="columnheader button">
                    {{ __('common.email') }}
                    @include('livewire.includes._sort-icon', ['field' => 'email'])
                </th>
                <th wire:click="sortBy('created_at')" role="columnheader button">
                        {{ __('user.registration-date') }}
                    @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                </th>
                <th wire:click="sortBy('last_login')" role="columnheader button">
                    {{ __('user.last-login') }}
                    @include('livewire.includes._sort-icon', ['field' => 'last_login'])
                </th>
                <th>{{ __('common.action') }}</th>
            </tr>
            @forelse ($users as $user)
                <tr>
                    <td>
                        <img
                            src="{{ url($user->image === null ? 'img/profile.png' : 'files/img/' . $user->image) }}"
                            alt="{{ $user->username }}"
                            class="user-search__avatar"
                        >
                    </td>
                    <td colspan="2">
                        <x-user_tag :anon="false" :user="$user" />
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <time datetime="{{ $user->created_at }}">{{ $user->created_at }}</time>
                    </td>
                    <td>
                        <time datetime="{{ $user->last_login }}">{{ $user->last_login ?? 'Never' }}</time>
                    </td>
                    <td>
                        <menu class="data-table__actions">
                            <li class="data-table__action">
                                <a
                                    class="form__button form__button--text"
                                    href="{{ route('user_setting', ['username' => $user->username, 'id' => $user->id]) }}"
                                >
                                    {{ __('common.edit') }}
                                </a>
                            </li>
                        </menu>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No users</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $users->links('partials.pagination') }}
</section>
