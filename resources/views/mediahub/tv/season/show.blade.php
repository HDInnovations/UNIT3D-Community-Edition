@extends('layout.default')

@section('title')
    <title>{{ $season->name }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ $season->name }}" />
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
    <li class="breadcrumbV2">
        <a href="{{ route('mediahub.shows.show', ['id' => $show->id]) }}" class="breadcrumb__link">
            {{ $show->name }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $season->name }}
    </li>
@endsection

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('mediahub.episodes') }}</h2>
        <div class="panel__body">
            @foreach ($season->episodes as $episode)
                <div class="row">
                    <div class="col-md-12">
                        <div
                            class="card is-torrent"
                            style="margin-top: 0; margin-bottom: 20px; height: auto"
                        >
                            <div class="card_head">
                                <span class="text-bold" style="float: right">
                                    Episode {{ $episode->episode_number }}
                                </span>
                            </div>
                            <div class="card_body">
                                <div class="body_poster">
                                    <img
                                        src="{{ isset($episode->still) ? tmdb_image('still_mid', $episode->still) : 'https://via.placeholder.com/400x225' }}"
                                        class="show-poster"
                                    />
                                </div>
                                <div class="body_description" style="height: 190px">
                                    <h3 class="description_title">
                                        {{ $episode->name }} -
                                        @if ($episode->air_date)
                                            <span class="text-bold text-pink">
                                                {{ $episode->air_date }}
                                            </span>
                                        @endif
                                    </h3>
                                    <p class="description_plot">
                                        {{ $episode->overview }}
                                    </p>
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
        <h2 class="panel__heading">{{ $season->name }} ({{ $season->air_date }})</h2>
        <img
            src="{{ isset($season->poster) ? tmdb_image('cast_big', $season->poster) : 'https://via.placeholder.com/300x450' }}"
            alt="{{ $show->name }}"
        />
        <div class="panel__body">
            {{ $season->overview }}
        </div>
        <dl class="key-value">
            <dt>Air date</dt>
            <dd>{{ $season->air_date }}</dd>
        </dl>
    </section>
@endsection
