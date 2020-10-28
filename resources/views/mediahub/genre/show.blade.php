@extends('layout.default')

@section('title')
    <title>{{ $genre->name }} Network - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ $genre->name }} Network">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('mediahub.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">MediaHub</span>
        </a>
    </li>
    <li>
        <a href="{{ route('mediahub.genres.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Genres</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('mediahub.genres.show', ['id' => $genre->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $genre->name }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div style="width: 100% !important; display: table !important;">
                <div class="header mediahub" style="width: 100% !important; display: table-cell !important;">
                    <h1 class="text-center" style=" height: 100px; font-family: Shrikhand, cursive; font-size: 7em; font-weight: 400; margin: 0;">
                        {{ $genre->name }}
                    </h1>
                    <hr>
                    <h2 class="text-center">{{ $genre->tv->count() }} TV Shows | {{ $genre->movie->count() }} Movies</h2>
                    @foreach($movies as $movie)
                        <div class="col-md-12">
                            <div class="card is-torrent">
                                <div class="card_head">
                                <span class="badge-user text-bold" style="float:right;">
                                    <i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-up text-green"></i> {{ $movie->runtime }}
                                </span>
                                </div>
                                <div class="card_body">
                                    <div class="body_poster">
                                        <img src="{{ $movie->poster }}" class="show-poster" data-image='<img src="{{ $movie->poster }}" style="height: 1000px;">'>
                                    </div>
                                    <div class="body_description">
                                        <h3 class="description_title">
                                            <a href="#">{{ $movie->title }}
                                                <span class="text-bold text-pink"> {{ substr($movie->release_date, 0, 4) }}</span>
                                            </a>
                                        </h3>
                                        @foreach ($movie->genres as $genre)
                                            <span class="genre-label">{{ $genre->name }}</span>
                                        @endforeach
                                        <p class="description_plot">
                                            {{ $movie->overview }}
                                        </p>
                                    </div>
                                </div>
                                <div class="card_footer">
                                    <div style="float: left;">

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @foreach($shows as $show)
                        <div class="col-md-12">
                            <div class="card is-torrent">
                                <div class="card_head">
                                <span class="badge-user text-bold" style="float:right;">
                                    <i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-up text-green"></i> {{ $show->episode_run_time }}
                                </span>
                                </div>
                                <div class="card_body">
                                    <div class="body_poster">
                                        <img src="{{ $show->poster }}" class="show-poster" data-image='<img src="{{ $show->poster }}" style="height: 1000px;">'>
                                    </div>
                                    <div class="body_description">
                                        <h3 class="description_title">
                                            <a href="{{ route('mediahub.shows.show', ['id' => $show->id]) }}">{{ $show->name }}
                                                <span class="text-bold text-pink"> {{ substr($show->first_air_date, 0, 4) }}</span>
                                            </a>
                                        </h3>
                                        @foreach ($show->genres as $genre)
                                            <span class="genre-label">{{ $genre->name }}</span>
                                        @endforeach
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
                    @endforeach
                    <div class="text-center">
                        {{ $shows->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection