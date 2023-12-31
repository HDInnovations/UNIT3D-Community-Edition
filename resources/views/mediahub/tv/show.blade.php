@extends('layout.default')

@section('title')
    <title>{{ $show->name }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ $show->name }}" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('mediahub.index') }}" class="breadcrumb__link">
            {{ __('mediahub.title') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('mediahub.shows.index') }}" class="breadcrumb__link">TV Shows</a>
    </li>
    <li class="breadcrumb--active">
        {{ $show->name }}
    </li>
@endsection

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('mediahub.seasons') }}</h2>
        <div class="panel__body">
            @foreach ($show->seasons as $season)
                <div class="row">
                    <div class="col-md-12">
                        <div
                            class="card is-torrent"
                            style="height: auto; margin-top: 0; margin-bottom: 20px"
                        >
                            <div class="card_head">
                                <span class="text-bold" style="float: right">
                                    {{ $season->episodes->count() }} Episodes
                                </span>
                                <span class="text-bold" style="float: right">
                                    Season {{ $season->season_number }}
                                </span>
                            </div>
                            <div class="card_body" style="height: 190px">
                                <div class="body_poster">
                                    <img
                                        src="{{ isset($season->poster) ? tmdb_image('poster_mid', $season->poster) : 'https://via.placeholder.com/200x300' }}"
                                        class="show-poster"
                                        style="height: 190px"
                                    />
                                </div>
                                <div class="body_description" style="height: 190px">
                                    <h3 class="description_title">
                                        <a
                                            href="{{ route('mediahub.season.show', ['id' => $season->id]) }}"
                                        >
                                            {{ $season->name }}
                                            @if ($season->air_date)
                                                <span class="text-bold text-pink">
                                                    ({{ substr($season->air_date, 0, 4) }})
                                                </span>
                                            @endif
                                        </a>
                                    </h3>
                                    <p class="description_plot">
                                        {{ $season->overview }}
                                    </p>
                                </div>
                            </div>
                            <div class="card_footer text-center">
                                <a
                                    data-toggle="collapse"
                                    data-target="#{{ $season->season_number }}"
                                >
                                    <i class="fas fa-chevron-double-down"></i>
                                    <span class="text-bold">
                                        {{ $season->torrents->where('season_number', '=', $season->season_number)->count() }}
                                        Torrents Matched
                                    </span>
                                    <i class="fas fa-chevron-double-down"></i>
                                </a>
                            </div>
                            <div id="{{ $season->season_number }}" class="collapse">
                                <div class="card_footer" style="height: auto">
                                    <div class="data-table-wrapper">
                                        <table class="data-table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('common.name') }}</th>
                                                    <th>{{ __('torrent.size') }}</th>
                                                    <th>S</th>
                                                    <th>L</th>
                                                    <th>C</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($season->torrents->where('season_number', '=', $season->season_number)->sortByDesc('created_at') as $torrent)
                                                    <tr>
                                                        <td>
                                                            <a
                                                                href="{{ route('torrents.show', ['id' => $torrent->id]) }}"
                                                                style="color: #8fa8e0"
                                                            >
                                                                {{ $torrent->name }}
                                                            </a>
                                                        </td>
                                                        <td>{{ $torrent->getSize() }}</td>
                                                        <td>{{ $torrent->seeders }}</td>
                                                        <td>{{ $torrent->leechers }}</td>
                                                        <td>{{ $torrent->times_completed }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ $show->name }}</h2>
        <img
            src="{{ isset($show->poster) ? tmdb_image('cast_big', $show->poster) : 'https://via.placeholder.com/300x450' }}"
            alt="{{ $show->name }}"
        />
        <dl class="key-value">
            <dt>Seasons</dt>
            <dd>{{ $show->number_of_seasons }}</dd>
            <dt>Status</dt>
            <dd>{{ $show->status }}</dd>
            <dt>Networks</dt>
            <dd>
                @foreach ($show->networks as $network)
                    <a
                        href="{{ route('torrents.index', ['view' => 'group', 'networkId' => $network->id]) }}"
                    >
                        {{ $network->name }}
                    </a>
                    @if (! $loop->last)
                        ,
                    @endif
                @endforeach
            </dd>
            <dt>Companies</dt>
            <dd>
                @foreach ($show->companies as $company)
                    <a
                        href="{{ route('torrents.index', ['view' => 'group', 'companyId' => $company->id]) }}"
                    >
                        {{ $company->name }}
                    </a>
                    @if (! $loop->last)
                        ,
                    @endif
                @endforeach
            </dd>
            <dt>Runtime</dt>
            <dd>{{ $show->episode_run_time }}</dd>
            <dt>Torrents</dt>
            <dd>{{ $show->torrents_count }}</dd>
            <dt>Genres</dt>
            <dd>
                @foreach ($show->genres as $genre)
                    <a
                        href="{{ route('torrents.index', ['view' => 'group', 'genres' => $genre->id]) }}"
                    >
                        {{ $genre->name }}
                    </a>
                    @if (! $loop->last)
                        ,
                    @endif
                @endforeach
            </dd>
        </dl>
    </section>
@endsection
