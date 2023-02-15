@extends('layout.default')

@section('title')
    <title>{{ $network->name }} {{ __('mediahub.networks') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ $network->name }} {{ __('mediahub.networks') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('mediahub.index') }}" class="breadcrumb__link">
            {{ __('mediahub.title') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('mediahub.networks.index') }}" class="breadcrumb__link">
            {{ __('mediahub.networks') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $network->name }}
    </li>
@endsection

@section('content')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('mediahub.shows') }} ({{ $network->tv_count }})</h2>
        {{ $shows->links('partials.pagination') }}
        <div class="panel__body">
            @forelse($shows as $show)
                <div class="col-md-12">
                    <div class="card is-torrent">
                        <div class="card_head">
                            <span class="badge-user text-bold" style="float:right;">
                                {{ $show->number_of_seasons }} {{ __('mediahub.seasons') }}
                            </span>
                            <span class="badge-user text-bold" style="float:right;">
                                {{ $show->number_of_episodes }} {{ __('mediahub.episodes') }}
                            </span>
                        </div>
                        <div class="card_body">
                            <div class="body_poster">
                                <img src="{{ isset($show->poster) ? tmdb_image('poster_mid', $show->poster) : 'https://via.placeholder.com/200x300' }}"
                                        class="show-poster">
                            </div>
                            <div class="body_description">
                                <h3 class="description_title">
                                    <a href="{{ route('mediahub.shows.show', ['id' => $show->id]) }}">{{ $show->name }}
                                        <span class="text-bold text-pink"> {{ substr($show->first_air_date, 0, 4) }}</span>
                                    </a>
                                </h3>
                                @if ($show->genres)
                                    @foreach ($show->genres as $genre)
                                        <span class="genre-label">{{ $genre->name }}</span>
                                    @endforeach
                                @endif
                                <p class="description_plot">
                                    {{ $show->overview }}
                                </p>
                            </div>
                        </div>
                        <div class="card_footer">
                            <div style="float: left;">

                            </div>
                        </div>
                    </div>
                </div>
            @empty
                No {{ __('mediahub.shows') }}
            @endforelse
        </div>
        {{ $shows->links('partials.pagination') }}
    </div>
@endsection
