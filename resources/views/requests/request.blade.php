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
        <section class="panelV2">
            <h2 class="panel__heading">
                {{ $torrentRequest->name }} {{ __('request.for') }}
                <i class="{{ config('other.font-awesome') }} fa-coins text-gold"></i>
                {{ $torrentRequest->bounty }} {{ __('bon.bon') }}
            </h2>
            @switch(true)
                @case($torrentRequest->category->movie_meta)
                    @include('torrent.partials.movie_meta', ['torrent' => $torrentRequest])
                    @break
                @case($torrentRequest->category->tv_meta)
                    @include('torrent.partials.tv_meta', ['torrent' => $torrentRequest])
                    @break
                @case($torrentRequest->category->game_meta)
                    @include('torrent.partials.game_meta', ['torrent' => $torrentRequest])
                    @break
            @endswitch
        </section>
        <section class="panelV2">
            <h2 class="panel__heading">{{ $torrentRequest->name }}</h2>
            <div class="panel__body">
                @joypixels($torrentRequest->getDescriptionHtml())
            </div>
        </section>
        <livewire:comments :model="$torrentRequest"/>
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

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('request.request-details') }}</h2>
        <dl class="key-value">
            <dt>{{ __('torrent.category') }}</dt>
            <dd>{{ $torrentRequest->category->name }}</dd>
            <dt>{{ __('torrent.type') }}</dt>
            <dd>{{ $torrentRequest->type->name }}</dd>
            <dt>{{ __('torrent.resolution') }}</dt>
            <dd>{{ $torrentRequest->resolution->name ?? 'No Res' }}</dd>
            <dt>{{ __('request.requested-by') }}</dt>
            <dd>
                <x-user_tag :user="$torrentRequest->user" :anon="$torrentRequest->anon" />
            </dd>
            <dt>{{ __('common.created_at') }}</dt>
            <dd>
                <time datetime="{{ $torrentRequest->created_at }}" title="{{ $torrentRequest->created_at }}">
                    {{ $torrentRequest->created_at->diffForHumans() }}
                </time>
            </dd>
            <dt>{{ __('common.status') }}</dt>
            <dd>
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
            </dd>
        </dl>
    </section>
    @if ($torrentRequest->claimed && $torrentRequest->torrent_id === null)
        <section class="panelV2">
            <h2 class="panel__heading">{{ __('request.claimed') }}</h2>
            <dl class="key-value">
                <dt>{{ __('request.claimed') }} by</dt>
                <dd>
                    @if ($torrentRequestClaim->anon)
                        {{ strtoupper(__('common.anonymous')) }}
                        @if ($user->group->is_modo || $torrentRequestClaim->username == $user->username)
                            ({{ $torrentRequestClaim->username }})
                        @endif
                    @else
                        <a href="{{ route('users.show', ['username' => $torrentRequestClaim->username]) }}">
                            {{ $torrentRequestClaim->username }}
                        </a>
                    @endif
                </dd>
                <dt>{{ __('request.claimed') }} at</dt>
                <dd>
                    <time datetime="{{ $torrentRequestClaim->created_at }}" title="{{ $torrentRequestClaim->created_at }}">
                        {{ $torrentRequestClaim->created_at->diffForHumans() }}
                    </time>
                </dd>
            </dl>
        </section>
    @endif
    @if ($torrentRequest->torrent_id !== null)
        <section class="panelV2">
            <h2 class="panel__heading">{{ __('request.filled') }}</h2>
            <dl class="key-value">
                <dt>{{ __('request.filled') }} by</dt>
                <dd>
                    <x-user_tag :user="$torrentRequest->FillUser" :anon="$torrentRequest->filled_anon" />
                </dd>
                <dt>{{ __('request.filled') }} at</dt>
                <dd>
                    <time datetime="{{ $torrentRequest->filled_when }}" title="{{ $torrentRequest->filled_when }}">
                        {{ $torrentRequest->filled_when->diffForHumans() }}
                    </time>
                </dd>
                <dt>{{ __('request.filled') }} with</dt>
                <dd>
                    <a
                        href="{{ route('torrent', ['id' => $torrentRequest->torrent->id]) }}"
                        title="{{ $torrentRequest->torrent->name }}"
                    >
                        {{ __('torrent.torrent') }} {{ $torrentRequest->torrent->id }}
                    </a>
                </dd>
            </dl>
            @if ($torrentRequest->approved_by === null && ($torrentRequest->user_id == $user->id || auth()->user()->group->is_modo))
                <div class="panel__body">
                    <form
                        method="POST"
                        action="{{ route('approveRequest', ['id' => $torrentRequest->id]) }}"
                    >
                        <div class="form__group form__group--horizontal">
                            @csrf
                            <button class="form__button form__button--filled">
                                {{ __('request.approve') }}
                            </button>
                        </div>
                    </form>
                    <form
                        method="POST"
                        action="{{ route('rejectRequest', ['id' => $torrentRequest->id]) }}"
                    >
                    <div class="form__group form__group--horizontal">
                            @csrf
                            <button class="form__button form__button--filled">
                                {{ __('request.reject') }}
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </section>
    @endif
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.actions') }}</h2>
        <div class="panel__body">
            <div class="form__group--short-horizontal">
                @switch(true)
                    {{-- Claimed --}}
                    @case ($torrentRequest->claimed && $torrentRequest->torrent_id === null)
                        @includeWhen($user->group->is_modo || $torrentRequestClaim->username == $user->username, 'requests.partials.unclaim')
                        @includeWhen($torrentRequest->category->movie_meta || $torrentRequest->category->tv_meta, 'requests.partials.upload')
                        @includeWhen($user->group->is_modo || $torrentRequestClaim->username == $user->username, 'requests.partials.fulfill')
                        @include('requests.partials.report')
                        @includeWhen($user->group->is_modo || $torrentRequest->user->id == $user->id, 'requests.partials.edit')
                        @includeWhen($user->group->is_modo || $torrentRequest->user->id == $user->id, 'requests.partials.delete')
                        @break
                    {{-- Pending --}}
                    @case ($torrentRequest->torrent_id !== null && $torrentRequest->approved_by === null)
                        @include('requests.partials.report')
                        @includeWhen($user->group->is_modo, 'requests.partials.reset')
                        @includeWhen($user->group->is_modo, 'requests.partials.edit')
                        @includeWhen($user->group->is_modo, 'requests.partials.delete')
                        @break
                    {{-- Unfilled --}}
                    @case ($torrentRequest->torrent_id === null)
                        @include('requests.partials.claim')
                        @includeWhen($user->group->is_modo || $torrentRequestClaim?->username == $user->username, 'requests.partials.upload')
                        @include('requests.partials.fulfill')
                        @include('requests.partials.report')
                        @includeWhen($user->group->is_modo || $torrentRequest->user->id == $user->id, 'requests.partials.edit')
                        @includeWhen($user->group->is_modo || $torrentRequest->user->id == $user->id, 'requests.partials.delete')
                        @break
                    {{-- Filled --}}
                    @default
                        @include('requests.partials.report')
                        @includeWhen($user->group->is_modo, 'requests.partials.reset')
                        @includeWhen($user->group->is_modo, 'requests.partials.edit')
                        @includeWhen($user->group->is_modo, 'requests.partials.delete')
                        @break
                @endswitch
            </div>
        </div>
    </section>
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('request.voters') }}</h2>
            <div class="panel__actions">
                @includeWhen($torrentRequest->torrent_id === null, 'requests.partials.vote')
            </div>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('common.user') }}</th>
                        <th>{{ __('bon.bon') }}</th>
                        <th>{{ __('request.last-vote') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($voters as $voter)
                        <tr>
                            <td>
                                <x-user_tag :user="$voter->user" :anon="$voter->anon" />
                            </td>
                            <td>{{ $voter->seedbonus }}</td>
                            <td>
                                <time datetime="{{ $voter->created_at }}" title="{{ $voter->created_at }}">
                                    {{ $voter->created_at->diffForHumans() }}
                                </time>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
