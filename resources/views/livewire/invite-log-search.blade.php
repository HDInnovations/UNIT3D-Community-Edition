<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('staff.invites-log') }}</h2>
        <div class="panel__actions">
            <div class="panel__action">
                <div class="form__group">
                    <input
                            id="sender"
                            class="form__text"
                            type="text"
                            wire:model.live="sender"
                            placeholder=" "
                    />
                    <label class="form__label form__label--floating">
                        {{ __('user.sender') }}
                    </label>
                </div>
            </div>
            <div class="panel__action">
                <div class="form__group">
                    <input
                            id="receiver"
                            class="form__text"
                            type="text"
                            wire:model.live="receiver"
                            placeholder=" "
                    />
                    <label class="form__label form__label--floating">
                        {{ __('bon.receiver') }}
                    </label>
                </div>
            </div>
            <div class="panel__action">
                <div class="form__group">
                    <input
                            id="email"
                            class="form__text"
                            type="text"
                            wire:model.live="email"
                            placeholder=" "
                    />
                    <label class="form__label form__label--floating">
                        {{ __('common.email') }}
                    </label>
                </div>
            </div>
            <div class="panel__action">
                <div class="form__group">
                    <input
                            id="code"
                            class="form__text"
                            type="text"
                            wire:model.live="code"
                            placeholder=" "
                    />
                    <label class="form__label form__label--floating">
                        {{ __('common.code') }}
                    </label>
                </div>
            </div>
            <div class="panel__action">
                <div class="form__group">
                    <select
                            id="quantity"
                            class="form__select"
                            wire:model.live="perPage"
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
        </div>
    </header>
    <div class="data-table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th wire:click="sortBy('id')" role="columnheader button">
                        ID
                        @include('livewire.includes._sort-icon', ['field' => 'id'])
                    </th>
                    <th wire:click="sortBy('user_id')" role="columnheader button">
                        {{ __('user.sender') }}
                        @include('livewire.includes._sort-icon', ['field' => 'user_id'])
                    </th>
                    <th wire:click="sortBy('email')" role="columnheader button">
                        {{ __('common.email') }}
                        @include('livewire.includes._sort-icon', ['field' => 'email'])
                    </th>
                    <th wire:click="sortBy('code')" role="columnheader button">
                        Code
                        @include('livewire.includes._sort-icon', ['field' => 'code'])
                    </th>
                    <th wire:click="sortBy('created_at')" role="columnheader button">
                        {{ __('user.created-on') }}
                        @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                    </th>
                    <th wire:click="sortBy('expires_on')" role="columnheader button">
                        {{ __('user.expires-on') }}
                        @include('livewire.includes._sort-icon', ['field' => 'expires_on'])
                    </th>
                    <th wire:click="sortBy('accepted_by')" role="columnheader button">
                        {{ __('user.accepted-by') }}
                        @include('livewire.includes._sort-icon', ['field' => 'accepted_by'])
                    </th>
                    <th wire:click="sortBy('accepted_at')" role="columnheader button">
                        {{ __('user.accepted-at') }}
                        @include('livewire.includes._sort-icon', ['field' => 'accepted_at'])
                    </th>
                </tr>
            </thead>
            <tbody>
            @forelse ($invites as $invite)
                <tr>
                    <td>{{ $invite->id }}</td>
                    <td>
                        <x-user_tag :anon="false" :user="$invite->sender" />
                    </td>
                    <td>{{ $invite->email }}</td>
                    <td>{{ $invite->code }}</td>
                    <td>
                        <time datetime="{{ $invite->created_at }}">
                            {{ $invite->created_at }}
                        </time>
                    </td>
                    <td>
                        <time datetime="{{ $invite->expires_on }}">
                            {{ $invite->expires_on }}
                        </time>
                    </td>
                    <td>
                        @if ($invite->accepted_by === null)
                            N/A
                        @else
                            <x-user_tag :anon="false" :user="$invite->receiver" />
                        @endif
                    </td>
                    <td>
                        <time datetime="{{ $invite->accepted_at ?? '' }}">
                            {{ $invite->accepted_at ?? 'N/A' }}
                        </time>
                    </td>
                    <td>
                        <time datetime="{{ $invite->deleted_at ?? '' }}">
                            {{ $invite->deleted_at ?? 'N/A' }}
                        </time>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No invites</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $invites->links('partials.pagination') }}
</section>

