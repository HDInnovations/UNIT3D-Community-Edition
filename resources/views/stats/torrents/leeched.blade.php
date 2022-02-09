@extends('layout.default')

@section('title')
    <title>{{ __('stat.stats') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li class="active">
        <a href="{{ route('stats') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('stat.stats') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('leeched') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('stat.top-leeched') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        @include('partials.statstorrentmenu')

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
