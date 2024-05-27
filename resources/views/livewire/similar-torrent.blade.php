<div>
    <div style="display: flex; flex-direction: column; gap: 16px">
        @if ($checked && $user->group->is_modo)
            <menu style="list-style-type: none; padding: 0; margin: 0">
                <li>
                    <button class="form__button form__button--filled" wire:click="alertConfirm()">
                        Delete ({{ count($checked) }})
                    </button>
                </li>
            </menu>
        @endif

        <table class="data-table" id="torrent-similar">
            <thead>
                <tr>
                    @if ($user->group->is_modo)
                        <th>
                            <input
                                type="checkbox"
                                wire:model.live="selectPage"
                                style="vertical-align: middle"
                            />
                        </th>
                    @endif

                    <th
                        class="torrents-filename"
                        wire:click="sortBy('name')"
                        role="columnheader button"
                    >
                        {{ __('common.name') }}
                        @include('livewire.includes._sort-icon', ['field' => 'name'])
                    </th>
                    <th wire:click="sortBy('created_at')" role="columnheader button">
                        {{ __('common.created_at') }}
                        @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                    </th>
                    <th wire:click="sortBy('size')" role="columnheader button">
                        <i class="{{ config('other.font-awesome') }} fa-database"></i>
                        @include('livewire.includes._sort-icon', ['field' => 'size'])
                    </th>
                    <th wire:click="sortBy('seeders')" role="columnheader button">
                        <i class="{{ config('other.font-awesome') }} fa-arrow-alt-circle-up"></i>
                        @include('livewire.includes._sort-icon', ['field' => 'seeders'])
                    </th>
                    <th wire:click="sortBy('leechers')" role="columnheader button">
                        <i class="{{ config('other.font-awesome') }} fa-arrow-alt-circle-down"></i>
                        @include('livewire.includes._sort-icon', ['field' => 'leechers'])
                    </th>
                    <th wire:click="sortBy('times_completed')" role="columnheader button">
                        <i class="{{ config('other.font-awesome') }} fa-check-circle"></i>
                        @include('livewire.includes._sort-icon', ['field' => 'times_completed'])
                    </th>
                </tr>
            </thead>
        </table>
        @foreach ($torrents->sortBy('type.position')->values()->groupBy('type.name') as $type => $torrents)
            <section class="panelV2" x-data>
                <h2 class="panel__heading">{{ $type }}</h2>
                <div class="data-table-wrapper">
                    <table class="data-table">
                        @foreach ($torrents->sortBy('resolution.position')->values()->groupBy('resolution.name') as $resolution => $torrents)
                            <tbody>
                                <tr>
                                    <th colspan="100">{{ $resolution }}</th>
                                </tr>
                                @foreach ($torrents as $torrent)
                                    @if ($user->group->is_modo)
                                        <tr>
                                            <td
                                                colspan="0"
                                                rowspan="2"
                                                x-on:click.self="$el.firstElementChild.click()"
                                            >
                                                <input
                                                    type="checkbox"
                                                    value="{{ $torrent->id }}"
                                                    wire:model.live="checked"
                                                />
                                            </td>
                                        </tr>
                                    @endif

                                    <x-torrent.row
                                        :torrent="$torrent"
                                        :meta="$work"
                                        :personal_freeleech="$personalFreeleech"
                                    />
                                @endforeach
                            </tbody>
                        @endforeach
                    </table>
                </div>
            </section>
        @endforeach
    </div>

    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('request.requests') }}</h2>
            <div class="panel__actions">
                <div class="panel__action">
                    <a
                        href="{{ route('requests.create') }}"
                        class="form__button form__button--text"
                    >
                        {{ __('request.add-request') }}
                    </a>
                </div>
            </div>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <tbody>
                    @forelse ($torrentRequests as $torrentRequest)
                        <tr>
                            <td>
                                <a
                                    href="{{ route('requests.show', ['torrentRequest' => $torrentRequest]) }}"
                                >
                                    {{ $torrentRequest->name }}
                                </a>
                            </td>
                            <td>{{ $torrentRequest->category->name }}</td>
                            <td>{{ $torrentRequest->type->name }}</td>
                            <td>{{ $torrentRequest->resolution->name ?? 'Unknown' }}</td>
                            <td>
                                <x-user_tag
                                    :user="$torrentRequest->user"
                                    :anon="$torrentRequest->anon"
                                />
                            </td>
                            <td>{{ $torrentRequest->votes }}</td>
                            <td>{{ $torrentRequest->comments_count }}</td>
                            <td>{{ number_format($torrentRequest->bounty) }}</td>
                            <td>
                                <time
                                    datetime="{{ $torrentRequest->created_at }}"
                                    title="{{ $torrentRequest->created_at }}"
                                >
                                    {{ $torrentRequest->created_at->diffForHumans() }}
                                </time>
                            </td>
                            <td>
                                @switch(true)
                                    @case($torrentRequest->claimed && $torrentRequest->torrent_id === null)
                                        <i class="fas fa-circle text-blue"></i>
                                        {{ __('request.claimed') }}

                                        @break
                                    @case($torrentRequest->torrent_id !== null && $torrentRequest->approved_by === null)
                                        <i class="fas fa-circle text-purple"></i>
                                        {{ __('request.pending') }}

                                        @break
                                    @case($torrentRequest->torrent_id === null)
                                        <i class="fas fa-circle text-red"></i>
                                        {{ __('request.unfilled') }}

                                        @break
                                    @default
                                        <i class="fas fa-circle text-green"></i>
                                        {{ __('request.filled') }}

                                        @break
                                @endswitch
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">{{ __('common.no-result') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

@section('javascripts')
    @if ($user->group->is_modo)
        <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
            window.addEventListener('swal:modal', event => {
              Swal.fire({
                title: event.detail.message,
                text: event.detail.text,
                icon: event.detail.type,
              })
            })

            window.addEventListener('swal:confirm', event => {
              const { value: text } = Swal.fire({
                input: 'textarea',
                inputLabel: 'Delete Reason',
                inputPlaceholder: 'Type your reason here...',
                inputAttributes: {
                  'aria-label': 'Type your reason here'
                },
                inputValidator: (value) => {
                  if (!value) {
                    return 'You need to write something!'
                  }
                },
                title: event.detail.message,
                html: event.detail.body,
                icon: event.detail.type,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
              }).then((result) => {
                if (result.isConfirmed) {
                @this.set('reason', result.value);
                  Livewire.dispatch('destroy')
                }
              })
            })
        </script>
    @endif
@endsection
