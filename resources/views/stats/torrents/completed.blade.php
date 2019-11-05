@extends('layout.default')

@section('title')
    <title>@lang('stat.stats') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li class="active">
        <a href="{{ route('stats') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('stat.stats')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('completed') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('stat.top-completed')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        @include('partials.statstorrentmenu')

        <div class="block">
            <h2>@lang('stat.top-completed')</h2>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <p class="text-info"><strong><i class="{{ config('other.font-awesome') }} fa-line-chart"></i> @lang('stat.top-downloaded')
                        </strong></p>
                    </div>
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('torrent.torrent')</th>
                            <th>@lang('torrent.seeders')</th>
                            <th>@lang('torrent.leechers')</th>
                            <th>@lang('torrent.completed')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($completed as $key => $c)
                            <tr>
                                <td>
                                    {{ ++$key }}
                                </td>
                                <td>
                                    <a class="text-bold" href="{{ route('torrent', ['id' => $c->id]) }}">
                                        {{ $c->name }}
                                    </a>
                                </td>
                                <td>{{ $c->seeders }}</td>
                                <td>{{ $c->leechers }}</td>
                                <td>
                                    <span class="text-orange">{{ $c->times_completed }}</span>
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
