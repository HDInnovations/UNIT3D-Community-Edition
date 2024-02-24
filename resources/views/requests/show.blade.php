@extends('layout.default')

@section('title')
    <title>Request - {{ config('other.title') }} - {{ $torrentRequest->name }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('requests.index') }}" class="breadcrumb__link">
            {{ __('request.requests') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $torrentRequest->name }}
    </li>
@endsection

@section('page', 'page__request--show')

@section('main')
    @if ($user->can_request)
        @switch(true)
            @case($torrentRequest->category->movie_meta)
                @include('torrent.partials.movie_meta', ['torrent' => $torrentRequest, 'category' => $torrentRequest->category, 'tmdb' => $torrentRequest->tmdb])

                @break
            @case($torrentRequest->category->tv_meta)
                @include('torrent.partials.tv_meta', ['torrent' => $torrentRequest, 'category' => $torrentRequest->category, 'tmdb' => $torrentRequest->tmdb])

                @break
            @case($torrentRequest->category->game_meta)
                @include('torrent.partials.game_meta', ['torrent' => $torrentRequest, 'category' => $torrentRequest->category, 'igdb' => $torrentRequest->igdb])

                @break
            @default
                @include('torrent.partials.no_meta', ['torrent' => $torrentRequest, 'category' => $torrentRequest->category])

                @break
        @endswitch
        <menu class="torrent__buttons form__group--short-horizontal">
            @includeWhen($torrentRequest->torrent === null, 'requests.partials.vote')

            @switch(true)
                {{-- Claimed --}}
                @case($torrentRequest->claimed && $torrentRequest->torrent === null)
                    @includeWhen($user->group->is_modo || $torrentRequest->claim->user->is($user), 'requests.partials.unclaim')
                    @includeWhen($user->group->is_modo || $torrentRequest->claim->user->is($user), 'requests.partials.fulfill')
                    @include('requests.partials.report')
                    @includeWhen($user->group->is_modo || $torrentRequest->user->is($user), 'requests.partials.edit')
                    @includeWhen($user->group->is_modo || $torrentRequest->user->is($user), 'requests.partials.delete')

                    @break
                    {{-- Pending --}}
                @case($torrentRequest->torrent_id !== null && $torrentRequest->approved_by === null)
                    @include('requests.partials.report')
                    @includeWhen($user->group->is_modo, 'requests.partials.edit')
                    @includeWhen($user->group->is_modo, 'requests.partials.delete')

                    @break
                    {{-- Unfilled --}}
                @case($torrentRequest->torrent === null)
                    @include('requests.partials.claim')
                    @include('requests.partials.fulfill')
                    @include('requests.partials.report')
                    @includeWhen($user->group->is_modo || $torrentRequest->user->is($user), 'requests.partials.edit')
                    @includeWhen($user->group->is_modo || $torrentRequest->user->is($user), 'requests.partials.delete')

                    @break
                    {{-- Filled --}}
                @default
                    @include('requests.partials.report')
                    @includeWhen($user->group->is_modo, 'requests.partials.reset')
                    @includeWhen($user->group->is_modo, 'requests.partials.edit')
                    @includeWhen($user->group->is_modo, 'requests.partials.delete')

                    @break
            @endswitch
        </menu>
        <ul class="request__tags">
            <li class="request__category">
                <span>
                    {{ $torrentRequest->category->name }}
                </span>
            </li>
            <li class="request__type">
                <span>
                    {{ $torrentRequest->type->name }}
                </span>
            </li>
            <li class="request__resolution">
                <span>
                    {{ $torrentRequest->resolution->name ?? 'No Res' }}
                </span>
            </li>
            <li class="request__requester">
                <x-user_tag :user="$torrentRequest->user" :anon="$torrentRequest->anon" />
            </li>
            <li class="request__created-at">
                <time
                    datetime="{{ $torrentRequest->created_at }}"
                    title="{{ $torrentRequest->created_at }}"
                >
                    {{ $torrentRequest->created_at->diffForHumans() }}
                </time>
            </li>
            <li>
                <span>
                    @switch(true)
                        @case($torrentRequest->claim !== null && $torrentRequest->torrent === null)
                            <i class="fas fa-circle text-blue"></i>
                            {{ __('request.claimed') }}

                            @break
                        @case($torrentRequest->torrent !== null && $torrentRequest->approved_by === null)
                            <i class="fas fa-circle text-purple"></i>
                            {{ __('request.pending') }}

                            @break
                        @case($torrentRequest->torrent === null)
                            <i class="fas fa-circle text-red"></i>
                            {{ __('request.unfilled') }}

                            @break
                        @default
                            <i class="fas fa-circle text-green"></i>
                            {{ __('request.filled') }}

                            @break
                    @endswitch
                </span>
            </li>
        </ul>
        <section class="panelV2">
            <h2 class="panel__heading">
                {{ $torrentRequest->name }} {{ __('request.for') }}
                <i class="{{ config('other.font-awesome') }} fa-coins text-gold"></i>
                {{ $torrentRequest->bounty }} {{ __('bon.bon') }}
            </h2>
            <div class="panel__body bbcode-rendered">
                @joypixels($torrentRequest->getDescriptionHtml())
            </div>
        </section>
        @if ($torrentRequest->claim !== null && $torrentRequest->torrent === null)
            <section class="panelV2">
                <h2 class="panel__heading">{{ __('request.claimed') }}</h2>
                <dl class="key-value">
                    <dt>{{ __('request.claimed') }} by</dt>
                    <dd>
                        <x-user_tag
                            :user="$torrentRequest->claim->user"
                            :anon="$torrentRequest->claim->anon"
                        />
                    </dd>
                    <dt>{{ __('request.claimed') }} at</dt>
                    <dd>
                        <time
                            datetime="{{ $torrentRequest->claim->created_at }}"
                            title="{{ $torrentRequest->claim->created_at }}"
                        >
                            {{ $torrentRequest->claim->created_at->diffForHumans() }}
                        </time>
                    </dd>
                </dl>
            </section>
        @endif

        @if ($torrentRequest->filled_by !== null)
            <section class="panelV2">
                <h2 class="panel__heading">{{ __('request.filled') }}</h2>
                <dl class="key-value">
                    <dt>{{ __('request.filled') }} by</dt>
                    <dd>
                        <x-user_tag
                            :user="$torrentRequest->filler"
                            :anon="$torrentRequest->filled_anon"
                        />
                    </dd>
                    <dt>{{ __('request.filled') }} at</dt>
                    <dd>
                        <time
                            datetime="{{ $torrentRequest->filled_when }}"
                            title="{{ $torrentRequest->filled_when }}"
                        >
                            {{ $torrentRequest->filled_when->diffForHumans() }}
                        </time>
                    </dd>
                    <dt>{{ __('request.filled') }} with</dt>
                    <dd>
                        @if ($torrentRequest->torrent === null)
                            Filled torrent has been deleted
                        @else
                            <a
                                href="{{ route('torrents.show', ['id' => $torrentRequest->torrent->id]) }}"
                            >
                                {{ $torrentRequest->torrent->name }}
                            </a>
                        @endif
                    </dd>
                </dl>
                @if ($torrentRequest->approved_by === null && ($torrentRequest->user_id == $user->id || auth()->user()->group->is_modo))
                    <div class="panel__body">
                        <div class="form__group">
                            <form
                                method="POST"
                                action="{{ route('requests.approved_fills.store', ['torrentRequest' => $torrentRequest]) }}"
                                style="display: contents"
                            >
                                @csrf
                                <button
                                    class="form__button form__button--filled form__button--centered"
                                >
                                    {{ __('request.approve') }}
                                </button>
                            </form>
                            <form
                                method="POST"
                                action="{{ route('requests.fills.destroy', ['torrentRequest' => $torrentRequest]) }}"
                                style="display: contents"
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                    class="form__button form__button--filled form__button--centered"
                                >
                                    {{ __('request.reject') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </section>
        @endif

        <section class="panelV2">
            <header class="panel__header">
                <h2 class="panel__heading">{{ __('request.voters') }}</h2>
            </header>
            <div class="data-table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>{{ __('common.user') }}</th>
                            <th>{{ __('bon.bon') }}</th>
                            <th>{{ __('request.last-vote') }}</th>
                            <th>{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($torrentRequest->bounties as $bounty)
                            <tr>
                                <td>
                                    <x-user_tag :user="$bounty->user" :anon="$bounty->anon" />
                                </td>
                                <td>{{ $bounty->seedbonus }}</td>
                                <td>
                                    <time
                                        datetime="{{ $bounty->created_at }}"
                                        title="{{ $bounty->created_at }}"
                                    >
                                        {{ $bounty->created_at->diffForHumans() }}
                                    </time>
                                </td>
                                <td>
                                    <menu class="data-table__actions">
                                        @if ($bounty->user_id == auth()->id() || auth()->user()->group->is_modo)
                                            <li class="data-table__action" x-data="dialog">
                                                <button
                                                    class="form__button form__button--text"
                                                    x-bind="showDialog"
                                                >
                                                    {{ __('common.edit') }}
                                                </button>
                                                <dialog class="dialog" x-bind="dialogElement">
                                                    <h4 class="dialog__heading">
                                                        {{ __('common.edit') }}
                                                        {{ __('request.vote') }}
                                                    </h4>
                                                    <form
                                                        class="dialog__form"
                                                        method="POST"
                                                        action="{{ route('requests.bounties.update', ['torrentRequest' => $torrentRequest, 'torrentRequestBounty' => $bounty]) }}"
                                                        x-bind="dialogForm"
                                                    >
                                                        @csrf
                                                        @method('PATCH')
                                                        <p class="form__group">
                                                            <input
                                                                type="hidden"
                                                                name="anon"
                                                                value="0"
                                                            />
                                                            <input
                                                                id="anon_{{ $bounty->id }}"
                                                                class="form__checkbox"
                                                                name="anon"
                                                                type="checkbox"
                                                                value="1"
                                                                @checked($bounty->anon)
                                                            />
                                                            <label
                                                                class="form__label"
                                                                for="anon_{{ $bounty->id }}"
                                                            >
                                                                {{ __('common.anonymous') }}?
                                                            </label>
                                                        </p>
                                                        <p class="form__group">
                                                            <button
                                                                class="form__button form__button--filled"
                                                            >
                                                                {{ __('common.edit') }}
                                                            </button>
                                                            <button
                                                                formmethod="dialog"
                                                                formnovalidate
                                                                class="form__button form__button--outlined"
                                                            >
                                                                {{ __('common.cancel') }}
                                                            </button>
                                                        </p>
                                                    </form>
                                                </dialog>
                                            </li>
                                        @endif
                                    </menu>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr></tr>
                    </tfoot>
                </table>
            </div>
        </section>
        <livewire:comments :model="$torrentRequest" />
    @else
        <section class="panelV2">
            <h2 class="panel__heading">
                <i class="{{ config('other.font-awesome') }} fa-times text-danger"></i>
                {{ __('request.no-privileges') }}!
            </h2>
            <p class="panel__body">{{ __('request.no-privileges-desc') }}!</p>
        </section>
    @endif
@endsection
