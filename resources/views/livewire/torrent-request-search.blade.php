<div class="sidebar2 sidebar--inverse">
    <div>
        <section class="panelV2">
            <header class="panel__header">
                <h2 class="panel__heading">{{ __('request.requests') }}</h2>
                <div class="panel__actions">
                    <div class="panel__action">
                        <a href="{{ route('add_request') }}" class="form__button form__button--text">
                            {{ __('request.add-request') }}
                        </a>
                    </div>
                </div>
            </header>
            <div class="data-table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('name')" role="columnheader button">
                                {{ __('common.name') }}
                                @include('livewire.includes._sort-icon', ['field' => 'name'])
                            </th>
                            <th wire:click="sortBy('category_id')" role="columnheader button">
                                {{ __('common.category') }}
                                @include('livewire.includes._sort-icon', ['field' => 'category_id'])
                            </th>
                            <th wire:click="sortBy('type_id')" role="columnheader button">
                                {{ __('common.type') }}
                                @include('livewire.includes._sort-icon', ['field' => 'type_id'])
                            </th>
                            <th wire:click="sortBy('resolution_id')" role="columnheader button">
                                {{ __('common.resolution') }}
                                @include('livewire.includes._sort-icon', ['field' => 'resolution_id'])
                            </th>
                            <th wire:click="sortBy('user_id')" role="columnheader button">
                                {{ __('common.author') }}
                                @include('livewire.includes._sort-icon', ['field' => 'user_id'])
                            </th>
                            <th wire:click="sortBy('votes')" role="columnheader button">
                                <i class="{{ config('other.font-awesome') }} fa-thumbs-up"></i>
                                @include('livewire.includes._sort-icon', ['field' => 'votes'])
                            </th>
                            <th>
                                <i class="{{ config('other.font-awesome') }} fa-comment-alt-lines"></i>
                            </th>
                            <th wire:click="sortBy('bounty')" role="columnheader button">
                                <i class="{{ config('other.font-awesome') }} fa-coins"></i>
                                @include('livewire.includes._sort-icon', ['field' => 'bounty'])
                            </th>
                            <th wire:click="sortBy('created_at')" role="columnheader button">
                                {{ __('common.created_at') }}
                                @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                            </th>
                            <th>{{ __('common.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($torrentRequests as $torrentRequest)
                            <tr>
                                <td>
                                    <a href="{{ route('request', ['id' => $torrentRequest->id]) }}">
                                        {{ $torrentRequest->name }}
                                    </a>
                                </td>
                                <td>{{ $torrentRequest->category->name }}</td>
                                <td>{{ $torrentRequest->type->name }}</td>
                                <td>{{ $torrentRequest->resolution->name ?? 'Unknown' }}</td>
                                <td>
                                    <x-user_tag :user="$torrentRequest->user" :anon="$torrentRequest->anon" />
                                </td>
                                <td>{{ $torrentRequest->votes }}</td>
                                <td>{{ $torrentRequest->comments_count }}</td>
                                <td>{{ number_format($torrentRequest->bounty) }}</td>
                                <td>
                                    <time datetime="{{ $torrentRequest->created_at }}" title="{{ $torrentRequest->created_at }}">
                                        {{ $torrentRequest->created_at->diffForHumans() }}
                                    </time>
                                </td>
                                <td>
                                    @switch(true)
                                        @case ($torrentRequest->claimed && $torrentRequest->torrent_id === null)
                                            <i class="fas fa-circle text-blue"></i>
                                            {{ __('request.claimed') }}
                                            @break
                                        @case ($torrentRequest->torrent_id !== null && $torrentRequest->approved_by === null)
                                            <i class="fas fa-circle text-purple"></i>
                                            {{ __('request.pending') }}
                                            @break
                                        @case ($torrentRequest->torrent_id === null)
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
                {{ $torrentRequests->links('partials.pagination') }}
            </div>
        </section>
    </div>
    <aside>
        <section x-data="{ open: false }" class="panelV2">
            <h2 class="panel__heading">{{ __('torrent.filters') }}</h2>
            <div class="panel__body">
                <form class="form">
                    <div class="form__group--horizontal">
                        <p class="form__group">
                            <input
                                id="name"
                                wire:model="name"
                                type="search"
                                class="form__text"
                                placeholder=""
                            />
                            <label class="form__label form__label--floating">
                                {{ __('common.name') }}
                            </label>
                        </p>
                        <p class="form__group">
                            <input wire:model="requestor" class="form__text" placeholder="">
                            <label class="form__label form__label--floating">{{ __('common.author') }}</label>
                        </p>
                    </div>
                    <div class="form__group--short-horizontal">
                        <p class="form__group">
                            <input wire:model="tmdbId" class="form__text" placeholder="">
                            <label class="form__label form__label--floating">TMDb ID</label>
                        </p>
                        <p class="form__group">
                            <input wire:model="imdbId" class="form__text" placeholder="">
                            <label class="form__label form__label--floating">IMDb ID</label>
                        </p>
                        <p class="form__group">
                            <input wire:model="tvdbId" class="form__text" placeholder="">
                            <label class="form__label form__label--floating">TVDb ID</label>
                        </p>
                        <p class="form__group">
                            <input wire:model="malId" class="form__text" placeholder="">
                            <label class="form__label form__label--floating">MAL ID</label>
                        </p>
                    </div>
                    <div class="form__group--short-horizontal">
                        <div class="form__group">
                            <fieldset class="form__fieldset">
                                <legend class="form__legend">{{ __('torrent.category') }}</legend>
                                <div class="form__fieldset-checkbox-container">
                                    @foreach (App\Models\Category::select(['id', 'name', 'position'])->get()->sortBy('position') as $category)
                                        <p class="form__group">
                                            <label class="form__label">
                                                <input
                                                    class="form__checkbox"
                                                    type="checkbox"
                                                    value="{{ $category->id }}"
                                                    wire:model="categories"
                                                >
                                                {{ $category->name }}
                                            </label>
                                        </p>
                                    @endforeach
                                </div>
                            </fieldset>
                        </div>
                        <div class="form__group">
                            <fieldset class="form__fieldset">
                                <legend class="form__legend">{{ __('common.type') }}</legend>
                                <div class="form__fieldset-checkbox-container">
                                    @foreach (App\Models\Type::select(['id', 'name', 'position'])->get()->sortBy('position') as $type)
                                        <p class="form__group">
                                            <label class="form__label">
                                                <input
                                                    class="form__checkbox"
                                                    type="checkbox"
                                                    value="{{ $type->id }}"
                                                    wire:model="types"
                                                >
                                                {{ $type->name }}
                                            </label>
                                        </p>
                                    @endforeach
                                </div>
                            </fieldset>
                        </div>
                        <div class="form__group">
                            <fieldset class="form__fieldset">
                                <legend class="form__legend">{{ __('common.resolution') }}</legend>
                                <div class="form__fieldset-checkbox-container">
                                    @foreach (App\Models\Resolution::select(['id', 'name', 'position'])->get()->sortBy('position') as $resolution)
                                        <p class="form__group">
                                            <label class="form__label">
                                                <input
                                                    class="form__checkbox"
                                                    type="checkbox"
                                                    value="{{ $resolution->id }}"
                                                    wire:model="resolutions"
                                                >
                                                {{ $resolution->name }}
                                            </label>
                                        </p>
                                    @endforeach
                                </div>
                            </fieldset>
                        </div>
                        <div class="form__group">
                            <fieldset class="form__fieldset">
                                <legend class="form__legend">{{ __('common.status') }}</legend>
                                <div class="form__fieldset-checkbox-container">
                                    <p class="form__group">
                                        <label class="form__label">
                                            <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="unfilled"
                                            >
                                            {{ __('request.unfilled') }}
                                        </label>
                                    </p>
                                    <p class="form__group">
                                        <label class="form__label">
                                            <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="claimed"
                                            >
                                            {{ __('request.claimed') }}
                                        </label>
                                    </p>
                                    <p class="form__group">
                                        <label class="form__label">
                                            <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="pending"
                                            >
                                            {{ __('request.pending') }}
                                        </label>
                                    </p>
                                    <p class="form__group">
                                        <label class="form__label">
                                            <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="filled"
                                            >
                                            {{ __('request.filled') }}
                                        </label>
                                    </p>
                                </div>
                            </fieldset>
                        </div>
                        <div class="form__group">
                            <fieldset class="form__fieldset">
                                <legend class="form__legend">{{ __('common.extra') }}</legend>
                                <div class="form__fieldset-checkbox-container">
                                    <p class="form__group">
                                        <label class="form__label">
                                            <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="myRequests"
                                            >
                                            {{ __('request.my-requests') }}
                                        </label>
                                    </p>
                                    <p class="form__group">
                                        <label class="form__label">
                                            <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="myClaims"
                                            >
                                            {{ __('request.my-claims') }}
                                        </label>
                                    </p>
                                    <p class="form__group">
                                        <label class="form__label">
                                            <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="myVoted"
                                            >
                                            {{ __('request.my-voted') }}
                                        </label>
                                    </p>
                                    <p class="form__group">
                                        <label class="form__label">
                                            <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="myFilled"
                                            >
                                            {{ __('request.my-filled') }}
                                        </label>
                                    </p>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        <section class="panelV2">
            <h2 class="panel__heading">{{ __('stat.stats') }}</h2>
            <dl class="key-value">
                <dt>{{ __('request.requests') }}:</dt>
                <dd>{{ number_format($torrentRequestStat->total) }}</dd>
                <dt>{{ __('request.filled') }}:</dt>
                <dd>{{ number_format($torrentRequestStat->filled) }}</dd>
                <dt>{{ __('request.unfilled') }}:</dt>
                <dd>{{ number_format($torrentRequestStat->unfilled) }}</dd>
                <dt>{{ __('request.total-bounty') }}:</dt>
                <dd>{{ number_format($torrentRequestBountyStat->total) }} {{ __('bon.bon') }}</dd>
                <dt>{{ __('request.bounty-claimed') }}:</dt>
                <dd>{{ number_format($torrentRequestBountyStat->claimed) }} {{ __('bon.bon') }}</dd>
                <dt>{{ __('request.bounty-unclaimed') }}:</dt>
                <dd>{{ number_format($torrentRequestBountyStat->unclaimed) }} {{ __('bon.bon') }}</dd>
            </dl>
        </section>
    </aside>
</div>

