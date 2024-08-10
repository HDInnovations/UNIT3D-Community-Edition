@extends('layout.default')

@section('title')
    <title>{{ __('stat.stats') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('stat.stats') }}
    </li>
@endsection

@section('nav-tabs')
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('clients') }}">
            {{ __('page.blacklist-clients') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('uploaded') }}">
            {{ __('common.users') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('seeded') }}">
            {{ __('torrent.torrents') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('bountied') }}">
            {{ __('request.requests') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('groups') }}">
            {{ __('common.groups') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('languages') }}">
            {{ __('common.languages') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('themes') }}">Themes</a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('yearly_overviews.index') }}">Overview</a>
    </li>
@endsection

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('stat.nerd-stats') }}</h2>
        <div class="panel__body">{{ __('stat.nerd-stats-desc') }}. {{ __('stat.updated') }}</div>
    </section>
    <div class="stats__panels">
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">{{ __('torrent.torrents') }}</h2>
            <dl class="key-value">
                @foreach ($categories as $category)
                    <div class="key-value__group">
                        <dt>{{ $category->name }} {{ __('common.category') }}</dt>
                        <dd>{{ $category->torrents_count }}</dd>
                    </div>
                @endforeach

                <div class="key-value__group">
                    <dt>HD</dt>
                    <dd>{{ $num_hd }}</dd>
                </div>
                <div class="key-value__group">
                    <dt>SD</dt>
                    <dd>{{ $num_sd }}</dd>
                </div>
                <div class="key-value__group">
                    <dt>{{ __('stat.total-torrents') }}</dt>
                    <dd>{{ $num_torrent }}</dd>
                </div>
                <div class="key-value__group">
                    <dt>{{ __('stat.total-torrents') }} {{ __('torrent.size') }}</dt>
                    <dd>{{ App\Helpers\StringHelper::formatBytes($torrent_size, 2) }}</dd>
                </div>
            </dl>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">{{ __('common.users') }}</h2>
            <dl class="key-value">
                <div class="key-value__group">
                    <dt>{{ __('stat.all') }} {{ __('common.users') }}</dt>
                    <dd>{{ $all_user }}</dd>
                </div>
                <div class="key-value__group">
                    <dt>{{ __('stat.active') }} {{ __('common.users') }}</dt>
                    <dd>{{ $active_user }}</dd>
                </div>
                <div class="key-value__group">
                    <dt>{{ __('stat.disabled') }} {{ __('common.users') }}</dt>
                    <dd>{{ $disabled_user }}</dd>
                </div>
                <div class="key-value__group">
                    <dt>{{ __('stat.pruned') }} {{ __('common.users') }}</dt>
                    <dd>{{ $pruned_user }}</dd>
                </div>
                <div class="key-value__group">
                    <dt>{{ __('stat.banned') }} {{ __('common.users') }}</dt>
                    <dd>{{ $banned_user }}</dd>
                </div>
            </dl>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">{{ __('torrent.peers') }}</h2>
            <dl class="key-value">
                <div class="key-value__group">
                    <dt>{{ __('torrent.seeders') }}</dt>
                    <dd>{{ $num_seeders }}</dd>
                </div>
                <div class="key-value__group">
                    <dt>{{ __('torrent.leechers') }}</dt>
                    <dd>{{ $num_leechers }}</dd>
                </div>
                <div class="key-value__group">
                    <dt>Total</dt>
                    <dd>{{ $num_peers }}</dd>
                </div>
            </dl>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">{{ __('stat.total-traffic') }}</h2>
            <dl class="key-value">
                <div class="key-value__group">
                    <dt>{{ __('stat.real') }} {{ __('stat.total-upload') }}</dt>
                    <dd>{{ \App\Helpers\StringHelper::formatBytes($actual_upload ?? 0, 2) }}</dd>
                </div>
                <div class="key-value__group">
                    <dt>{{ __('stat.real') }} {{ __('stat.total-download') }}</dt>
                    <dd>{{ \App\Helpers\StringHelper::formatBytes($actual_download ?? 0, 2) }}</dd>
                </div>
                <div class="key-value__group">
                    <dt>{{ __('stat.real') }} {{ __('stat.total-traffic') }}</dt>
                    <dd>{{ \App\Helpers\StringHelper::formatBytes($actual_up_down ?? 0, 2) }}</dd>
                </div>
                <div class="key-value__group">
                    <dt>{{ __('stat.credited') }} {{ __('stat.total-upload') }}</dt>
                    <dd>{{ \App\Helpers\StringHelper::formatBytes($credited_upload ?? 0, 2) }}</dd>
                </div>
                <div class="key-value__group">
                    <dt>{{ __('stat.credited') }} {{ __('stat.total-download') }}</dt>
                    <dd>
                        {{ \App\Helpers\StringHelper::formatBytes($credited_download ?? 0, 2) }}
                    </dd>
                </div>
                <div class="key-value__group">
                    <dt>{{ __('stat.credited') }} {{ __('stat.total-traffic') }}</dt>
                    <dd>
                        {{ \App\Helpers\StringHelper::formatBytes($credited_up_down ?? 0, 2) }}
                    </dd>
                </div>
            </dl>
        </section>
    </div>
@endsection
