<div style="display: flex; flex-direction: column; row-gap: 1rem;">
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('common.search') }}</h2>
        </header>
        <div class="panel__body" style="padding: 5px;">
            <form class="form">
                <div class="form__group--short-horizontal">
                    <p class="form__group">
                        <input wire:model="torrent" class="form__text" placeholder="">
                        <label class="form__label form__label--floating">Torrent Name</label>
                    </p>
                    <p class="form__group">
                        <input wire:model="ip" class="form__text" placeholder="">
                        <label class="form__label form__label--floating">IP Address</label>
                    </p>
                    <p class="form__group">
                        <input wire:model="port" class="form__text" placeholder="">
                        <label class="form__label form__label--floating">Port</label>
                    </p>
                    <p class="form__group">
                        <input wire:model="agent" class="form__text" placeholder="">
                        <label class="form__label form__label--floating">Agent</label>
                    </p>
                </div>
            </form>
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">Peers</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th wire:click="sortBy('username')" role="columnheader button">
                            {{ __('user.user') }}
                            @include('livewire.includes._sort-icon', ['field' => 'username'])
                        </th>
                        <th wire:click="sortBy('name')" role="columnheader button">
                            {{ __('torrent.torrent') }}
                            @include('livewire.includes._sort-icon', ['field' => 'name'])
                        </th>
                        <th wire:click="sortBy('agent')" role="columnheader button">
                            {{ __('torrent.agent') }}
                            @include('livewire.includes._sort-icon', ['field' => 'agent'])
                        </th>
                        <th wire:click="sortBy('ip')" role="columnheader button" style="text-align: right">
                            IP
                            @include('livewire.includes._sort-icon', ['field' => 'ip'])
                        </th>
                        <th wire:click="sortBy('port')" role="columnheader button" style="text-align: right">
                            Port
                            @include('livewire.includes._sort-icon', ['field' => 'port'])
                        </th>
                        @if (\config('announce.connectable_check'))
                            <th>Connectable</th>
                        @endif
                        <th wire:click="sortBy('uploaded')" role="columnheader button" style="text-align: right">
                            {{ __('torrent.uploaded') }}
                            @include('livewire.includes._sort-icon', ['field' => 'uploaded'])
                        </th>
                        <th wire:click="sortBy('downloaded')" role="columnheader button" style="text-align: right">
                            {{ __('torrent.downloaded') }}
                            @include('livewire.includes._sort-icon', ['field' => 'downloaded'])
                        </th>
                        <th wire:click="sortBy('left')" role="columnheader button" style="text-align: right">
                            {{ __('torrent.left') }}
                            @include('livewire.includes._sort-icon', ['field' => 'left'])
                        </th>
                        <th wire:click="sortBy('size')" role="columnheader button" style="text-align: right">
                            {{ __('torrent.size') }}
                            @include('livewire.includes._sort-icon', ['field' => 'size'])
                        </th>
                        <th wire:click="sortBy('created_at')" role="columnheader button" style="text-align: right">
                            Started
                            @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($peers as $peer)
                        <tr>
                            <td>
                                <x-user_tag :user="$peer->user" :anon="false" />
                            </td>
                            <td>
                                <a href="{{ route('torrent', ['id' => $peer->torrent_id]) }}">
                                    {{ $peer->name }}
                                </a>
                            </td>
                            <td>
                                {{ $peer->agent }}
                            </td>
                            <td style="text-align: right">
                                {{ $peer->ip }}
                            </td>
                            <td style="text-align: right">
                                {{ $peer->port }}
                            </td>
                            @if (\config('announce.connectable_check'))
                                <td>
                                    @if ($connectable)
                                        <i class="{{ config('other.font-awesome') }} text-green fa-check" title="Connectable"></i>
                                    @else
                                        <i class="{{ config('other.font-awesome') }} text-red fa-times" title="Not Connectable"></i>
                                    @endif
                                </td>
                            @endif
                            <td style="text-align: right">
                                {{ App\Helpers\StringHelper::formatBytes($peer->uploaded, 2) }}
                            </td>
                            <td style="text-align: right">
                                {{ App\Helpers\StringHelper::formatBytes($peer->downloaded, 2) }}
                            </td>
                            <td style="text-align: right">
                                {{ App\Helpers\StringHelper::formatBytes($peer->left, 2) }}
                            </td>
                            <td style="text-align: right">
                                {{ App\Helpers\StringHelper::formatBytes($peer->size) }}
                            </td>
                            <td style="text-align: right">{{ $peer->created_at?->diffForHumans() ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $peers->links('partials.pagination') }}
        </div>
    </section>
</div>
