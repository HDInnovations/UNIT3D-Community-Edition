@extends('layout.default')

@section('title')
    <title>{{ __('stat.stats') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('stats') }}" class="breadcrumb__link">
            {{ __('stat.stats') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('torrent.torrents') }}
    </li>
@endsection

@section('nav-tabs')
    @include('partials.statstorrentmenu')
@endsection

@section('page', 'page__stats--seeded')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('stat.top-seeded') }}</h2>
        <div class="panelV2">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('torrent.torrent') }}</th>
                        <th>{{ __('torrent.seeders') }}</th>
                        <th>{{ __('torrent.leechers') }}</th>
                        <th>{{ __('torrent.completed') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($seeded as $torrent)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <a href="{{ route('torrent', ['id' => $torrent->id]) }}">
                                    {{ $torrent->name }}
                                </a>
                            </td>
                            <td>{{ $torrent->seeders }}</td>
                            <td>{{ $torrent->leechers }}</td>
                            <td>{{ $torrent->times_completed }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
