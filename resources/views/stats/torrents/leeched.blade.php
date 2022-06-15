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

@section('content')
    <div class="container">
        <div class="block">
            <h2>{{ __('stat.top-leeched') }}</h2>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <p class="text-warning"><strong><i class="{{ config('other.font-awesome') }} fa-line-chart"></i>
                            {{ __('stat.top-leeched') }}
                        </strong></p>
                    <table class="table table-condensed table-striped table-bordered">
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
                        @foreach ($leeched as $key => $l)
                            <tr>
                                <td>
                                    {{ ++$key }}
                                </td>
                                <td>
                                    <a class="text-bold" href="{{ route('torrent', ['id' => $l->id]) }}">
                                        {{ $l->name }}
                                    </a>
                                </td>
                                <td>{{ $l->seeders }}</td>
                                <td><span class="text-red">{{ $l->leechers }}</span></td>
                                <td>
                                    <span>{{ $l->times_completed }}</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
