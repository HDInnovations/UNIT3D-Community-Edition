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
        <a href="{{ route('dying') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('stat.top-dying') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        @include('partials.statstorrentmenu')

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
