<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('ticket.helpdesk') }}</h2>
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
                        Show Closed Tickets
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
                        {{ __('ticket.subject') }}
                    </label>
                </div>
            </div>
            <div class="panel__action">
                <a href="{{ route('tickets.create') }}" class="form__button form__button--text">
                    {{ __('ticket.create-ticket') }}
                </a>
            </div>
        </div>
    </header>
    <div class="data-table-wrapper">
        <table class="data-table">
            <tbody>
            <tr>
                <th wire:click="sortBy('id')" role="columnheader button">
                    #
                    @include('livewire.includes._sort-icon', ['field' => 'id'])
                </th>
                <th wire:click="sortBy('subject')" role="columnheader button">
                    {{ __('ticket.subject') }}
                    @include('livewire.includes._sort-icon', ['field' => 'subject'])
                </th>
                <th wire:click="sortBy('priority_id')" role="columnheader button">
                    {{ __('ticket.priority') }}
                    @include('livewire.includes._sort-icon', ['field' => 'priority_id'])
                </th>
                <th wire:click="sortBy('user_id')" role="columnheader button">
                    {{ __('common.username') }}
                    @include('livewire.includes._sort-icon', ['field' => 'user_id'])
                </th>
                <th wire:click="sortBy('closed_at')" role="columnheader button">
                    {{ __('common.status') }}
                    @include('livewire.includes._sort-icon', ['field' => 'closed_at'])
                </th>
                <th wire:click="sortBy('staff_id')" role="columnheader button">
                    {{ __('ticket.assigned-staff') }}
                    @include('livewire.includes._sort-icon', ['field' => 'staff_id'])
                </th>
                <th wire:click="sortBy('created_at')" role="columnheader button">
                    {{ __('ticket.created') }}
                    @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                </th>
                <th>{{ __('common.action') }}</th>
            </tr>
            @forelse ($tickets as $ticket)
                <tr>
                    <td>
                        {{ $ticket->id }}
                    </td>
                    <td>
                        <a href="{{ route('tickets.show', ['id' => $ticket->id]) }}">{{ $ticket->subject }}</a>
                        @if (auth()->user()->group->is_modo)
                            @php
                                $myTicketUnread = DB::table('tickets')
                                    ->where('id', '=', $ticket->id)
                                    ->where('staff_id', '=', auth()->user()->id)
                                    ->where('staff_read', '=', 0)
                                    ->count();

                                $unasignedTicketUnread = DB::table('tickets')
                                    ->where('id', '=', $ticket->id)
                                    ->whereNull('staff_id')
                                    ->whereNull('closed_at')
                                    ->count()
                            @endphp
                        @else
                            @php
                                $myTicketUnread = DB::table('tickets')
                                    ->where('id', '=', $ticket->id)
                                    ->where('user_id', '=', auth()->user()->id)
                                    ->where('user_read', '=', 0)
                                    ->count();

                                $unasignedTicketUnread = 0
                            @endphp
                        @endif
                        @if ($myTicketUnread > 0 || $unasignedTicketUnread > 0)
                            <i style="color: #0dffff;vertical-align: 1px;" class="fas fa-circle fa-xs"></i>
                        @endif
                    </td>
                    <td>
                        @switch($ticket->priority->name)
							@case ('Low')
                                <i class="fas fa-circle text-yellow"></i>
                                @break
                            @case ('Medium')
                                <i class="fas fa-circle text-orange"></i>
                                @break
                            @case ('High')
                                <i class="fas fa-circle text-red"></i>
                                @break
                        @endswitch
                        {{ $ticket->priority->name }}
                    </td>
                    <td>
                        <x-user_tag :user="$ticket->user" :anon="false" />
                    </td>
                    <td>
                        @if($ticket->closed_at)
                            <i class="fas fa-circle text-danger"></i>
                            Closed
                        @else
                            <i class="fas fa-circle text-success"></i>
                            Open
                        @endif
                    </td>
                    <td>
                        @if ($ticket->staff)
                            <x-user_tag :user="$ticket->staff" :anon="false" />
                        @else
                            Unassigned
                        @endif
                    </td>
                    <td>
						<time datetime="{{ $ticket->created_at }}" title="{{ $ticket->created_at }}">
							{{ $ticket->created_at->diffForHumans() }}
                        </time>
                    </td>
                    <td>
                        <menu class="data-table__actions">
                            <li class="data-table__action">
                                <form method="POST" action="{{ route('tickets.close', ['id' => $ticket->id]) }}">
                                    @csrf
                                    <button class="form__button form__button--text">
                                        {{ __('ticket.close') }}
                                    </button>
                                </form>
                            </li>
                        </menu>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">{{ __('common.no-result') }}</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        {{ $tickets->links() }}
    </div>
</section>
