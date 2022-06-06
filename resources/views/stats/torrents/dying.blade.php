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
            <h2>{{ __('stat.top-dying') }}</h2>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <p class="text-orange"><strong><i
                                    class="{{ config('other.font-awesome') }} fa-exclamation-triangle"></i>
                            {{ __('stat.top-dying') }}</strong></p>
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
                        @foreach ($dying as $key => $d)
                            <tr>
                                <td>
                                    {{ ++$key }}
                                </td>
                                <td>
                                    <a class="text-bold" href="{{ route('torrent', ['id' => $d->id]) }}">
                                        {{ $d->name }}
                                    </a>
                                </td>
                                <td>{{ $d->seeders }}</td>
                                <td>{{ $d->leechers }}</td>
                                <td>
                                    <span>{{ $d->times_completed }}</span>
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
