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
                            wire:model="sender"
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
                            wire:model="receiver"
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
                            wire:model="email"
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
                            wire:model="code"
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
        </div>
    </header>
    <div class="data-table-wrapper">
        <table class="data-table">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ __('user.sender') }}</th>
                <th>{{ __('common.email') }}</th>
                <th>Code</th>
                <th>{{ __('user.created-on') }}</th>
                <th>{{ __('user.expires-on') }}</th>
                <th>{{ __('user.accepted-by') }}</th>
                <th>{{ __('user.accepted-at') }}</th>
                <th>{{ __('user.deleted-on') }}</th>
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

