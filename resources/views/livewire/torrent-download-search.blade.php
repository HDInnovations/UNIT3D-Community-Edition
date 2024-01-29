@section('title')
    <title>
        Torrent Downloads - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}
    </title>
@endsection

@section('meta')
    <meta name="description" content="Torrent Downloads - {{ __('staff.staff-dashboard') }}" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">Torrent Downloads</li>
@endsection

<div style="display: flex; flex-direction: column; row-gap: 1rem">
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('common.search') }}</h2>
        </header>
        <div class="panel__body" style="padding: 5px">
            <form class="form">
                <div class="form__group--short-horizontal">
                    <div class="form__group">
                        <input
                            id="username"
                            wire:model="username"
                            class="form__text"
                            placeholder=" "
                        />
                        <label class="form__label form__label--floating" for="username">
                            Username
                        </label>
                    </div>
                    <div class="form__group">
                        <input
                            id="torrentName"
                            wire:model="torrentName"
                            class="form__text"
                            placeholder=" "
                        />
                        <label class="form__label form__label--floating" for="torrentName">
                            Torrent Name
                        </label>
                    </div>
                    <div class="form__group">
                        <input
                            id="torrentDownloadType"
                            wire:model="torrentDownloadType"
                            class="form__text"
                            placeholder=" "
                        />
                        <label class="form__label form__label--floating" for="torrentDownloadType">
                            Type
                        </label>
                    </div>
                    <div class="form__group">
                        <input
                            id="from"
                            type="date"
                            wire:model="from"
                            class="form__text"
                            placeholder=" "
                        />
                        <label class="form__label form__label--floating" for="from">From</label>
                    </div>
                    <div class="form__group">
                        <input
                            id="until"
                            type="date"
                            wire:model="until"
                            class="form__text"
                            placeholder=" "
                        />
                        <label class="form__label form__label--floating" for="until">Until</label>
                    </div>
                    <div class="form__group">
                        <select
                            id="groupBy"
                            wire:model="groupBy"
                            class="form__select"
                            placeholder=" "
                        >
                            <option value="none">None</option>
                            <option value="user_id">User</option>
                        </select>
                        <label class="form__label form__label--floating" for="groupBy">
                            Group By
                        </label>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">Torrent Downloads</h2>
            <div class="panel__actions">
                <div class="panel__action">
                    <div class="form__group">
                        <select id="quantity" class="form__select" wire:model="perPage" required>
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
        <div class="panel__body" wire:loading.block>Loading...</div>
        <div class="data-table-wrapper">
            @switch($this->groupBy)
                @case('user_id')
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th wire:click="sortBy('user_id')" role="columnheader button">
                                    User
                                    @include('livewire.includes._sort-icon', ['field' => 'user_id'])
                                </th>
                                <th
                                    wire:click="sortBy('download_count')"
                                    role="columnheader button"
                                >
                                    Download Count
                                    @include('livewire.includes._sort-icon', ['field' => 'download_count'])
                                </th>
                                <th
                                    wire:click="sortBy('distinct_torrent_count')"
                                    role="columnheader button"
                                >
                                    Distinct Torrent Count
                                    @include('livewire.includes._sort-icon', ['field' => 'distinct_torrent_count'])
                                </th>
                                <th
                                    wire:click="sortBy('created_at_min')"
                                    role="columnheader button"
                                >
                                    First Downloaded At
                                    @include('livewire.includes._sort-icon', ['field' => 'created_at_min'])
                                </th>
                                <th
                                    wire:click="sortBy('created_at_max')"
                                    role="columnheader button"
                                >
                                    Last Downloaded At
                                    @include('livewire.includes._sort-icon', ['field' => 'created_at_max'])
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($torrentDownloads as $torrentDownload)
                                <tr>
                                    <td>
                                        <x-user_tag
                                            :user="$torrentDownload->user"
                                            :anon="false"
                                        />
                                    </td>
                                    <td>{{ $torrentDownload->download_count }}</td>
                                    <td>{{ $torrentDownload->distinct_torrent_count }}</td>
                                    <td>
                                        <time
                                            datetime="{{ $torrentDownload->created_at_min }}"
                                            title="{{ $torrentDownload->created_at_min }}"
                                        >
                                            {{ $torrentDownload->created_at_min->format('Y-m-d') }}
                                        </time>
                                    </td>
                                    <td>
                                        <time
                                            datetime="{{ $torrentDownload->created_at_max }}"
                                            title="{{ $torrentDownload->created_at_max }}"
                                        >
                                            {{ $torrentDownload->created_at_max->format('Y-m-d') }}
                                        </time>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @break
                @default
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th wire:click="sortBy('id')" role="columnheader button">
                                    ID
                                    @include('livewire.includes._sort-icon', ['field' => 'id'])
                                </th>
                                <th wire:click="sortBy('user_id')" role="columnheader button">
                                    User
                                    @include('livewire.includes._sort-icon', ['field' => 'user_id'])
                                </th>
                                <th wire:click="sortBy('torrent_id')" role="columnheader button">
                                    Torrent ID
                                    @include('livewire.includes._sort-icon', ['field' => 'torrent_id'])
                                </th>
                                <th wire:click="sortBy('torrent_id')" role="columnheader button">
                                    Torrent
                                    @include('livewire.includes._sort-icon', ['field' => 'torrent_id'])
                                </th>
                                <th wire:click="sortBy('type')" role="columnheader button">
                                    Type
                                    @include('livewire.includes._sort-icon', ['field' => 'type'])
                                </th>
                                <th wire:click="sortBy('created_at')" role="columnheader button">
                                    {{ __('common.created_at') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($torrentDownloads as $torrentDownload)
                                <tr>
                                    <td>{{ $torrentDownload->id }}</td>
                                    <td>
                                        <x-user_tag
                                            :user="$torrentDownload->user"
                                            :anon="false"
                                        />
                                    </td>
                                    <td>{{ $torrentDownload->torrent?->id ?? 'Not Found' }}</td>
                                    <td>
                                        @if ($torrentDownload->torrent !== null)
                                            <a
                                                href="{{ route('torrents.show', ['id' => $torrentDownload->torrent->id]) }}"
                                            >
                                                {{ $torrentDownload->torrent->name ?? 'Not Found' }}
                                            </a>
                                        @else
                                                Not Found
                                        @endif
                                    </td>
                                    <td>{{ $torrentDownload->type }}</td>
                                    <td>
                                        <time
                                            datetime="{{ $torrentDownload->created_at }}"
                                            title="{{ $torrentDownload->created_at }}"
                                        >
                                            {{ $torrentDownload->created_at->format('Y-m-d') }}
                                        </time>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            @endswitch
        </div>
        {{ $torrentDownloads->links('partials.pagination') }}
    </section>
</div>
