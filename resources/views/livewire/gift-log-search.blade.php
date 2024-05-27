<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('staff.gifts-log') }}</h2>
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
                    <label class="form__label form__label--floating" for="sender">
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
                    <label class="form__label form__label--floating" for="receiver">
                        {{ __('bon.receiver') }}
                    </label>
                </div>
            </div>
            <div class="panel__action">
                <div class="form__group">
                    <input
                        id="comment"
                        class="form__text"
                        type="text"
                        wire:model.live="comment"
                        placeholder=" "
                    />
                    <label class="form__label form__label--floating" for="comment">
                        {{ __('common.message') }}
                    </label>
                </div>
            </div>
            <div class="panel__action">
                <div class="form__group">
                    <select id="quantity" class="form__select" wire:model.live="perPage" required>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    <label class="form__label form__label--floating" for="quantity">
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
                    <th wire:click="sortBy('sender_id')" role="columnheader button">
                        {{ __('user.sender') }}
                        @include('livewire.includes._sort-icon', ['field' => 'sender_id'])
                    </th>
                    <th wire:click="sortBy('receiver_id')" role="columnheader button">
                        {{ __('bon.receiver') }}
                        @include('livewire.includes._sort-icon', ['field' => 'receiver_id'])
                    </th>
                    <th wire:click="sortBy('cost')" role="columnheader button">
                        {{ __('bon.points') }}
                        @include('livewire.includes._sort-icon', ['field' => 'cost'])
                    </th>
                    <th wire:click="sortBy('comment')" role="columnheader button">
                        {{ __('common.message') }}
                        @include('livewire.includes._sort-icon', ['field' => 'comment'])
                    </th>
                    <th wire:click="sortBy('created_at')" role="columnheader button">
                        {{ __('user.created-on') }}
                        @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($gifts as $gift)
                    <tr>
                        <td>
                            @if ($gift->sender === null)
                                Deleted user
                            @else
                                <x-user_tag :user="$gift->sender" :anon="false" />
                            @endif
                        </td>
                        <td>
                            @if ($gift->recipient === null)
                                Deleted user
                            @else
                                <x-user_tag :user="$gift->recipient" :anon="false" />
                            @endif
                        </td>
                        <td>{{ $gift->bon }}</td>
                        <td>{{ $gift->message }}</td>
                        <td>
                            <time
                                datetime="{{ $gift->created_at }}"
                                title="{{ $gift->created_at }}"
                            >
                                {{ $gift->created_at }}
                            </time>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No gifts</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $gifts->links('partials.pagination') }}
</section>
