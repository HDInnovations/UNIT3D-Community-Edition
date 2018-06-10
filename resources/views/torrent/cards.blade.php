@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('cards') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Torrents Card View</span>
        </a>
    </li>
@endsection

@section('stylesheets')
    <style>
        .card {
            background: #fff;
            border-radius: .3rem;
            box-shadow: 0 2px 10px 0 rgba(34, 36, 38, .05), 0 2px 20px 0 rgba(34, 36, 38, .05), 0 2px 4px -8px rgba(34, 36, 38, .1);
            display: flex;
            flex-direction: column
        }

        .card.is-primary {
            background: #ff3f7a
        }

        .card.has-white-text .card-content, .card.has-white-text .card-content p {
            color: #fff
        }

        .card.has-white-text .card-meta {
            color: hsla(0, 0%, 100%, .6)
        }

        .card.has-white-text .card-action {
            border-color: hsla(0, 0%, 100%, .08)
        }

        .card.has-white-text .card-action a {
            color: hsla(0, 0%, 100%, .6)
        }

        .card.has-white-text .card-action a:hover {
            color: hsla(0, 0%, 100%, .84)
        }

        .card.horizontal {
            flex-direction: row
        }

        .card.horizontal .card-image {
            flex: 1 1 auto;
            max-width: 160px
        }

        .card.horizontal .card-body {
            display: flex;
            flex: 1;
            flex-direction: column
        }

        .card.horizontal .card-content {
            flex-grow: 1
        }

        .card-content {
            padding: 1.5rem
        }

        .card-content p:last-child {
            margin-bottom: 0
        }

        .card-title {
            display: block;
            font-size: 1.8rem;
            font-weight: 600;
            line-height: 2.3rem
        }

        .card-meta {
            color: rgba(0, 0, 0, .4);
            font-size: 1.4rem;
            margin-bottom: 1rem
        }

        .card-action {
            align-items: center;
            border-top: 1px solid rgba(34, 36, 38, .08);
            display: flex;
            padding: 1rem 1.5rem
        }

        .card-action > :not(:last-child) {
            margin-right: 10px
        }

        .card-action a {
            color: rgba(34, 36, 38, .4);
            font-size: 1.5rem
        }

        .card-action a:hover {
            color: #41b7d8
        }

        .card-image img {
            border-radius: .3rem .3rem 0 0;
            display: block;
            width: 100%
        }

        .card.is-torrent {
            background: #fff;
            box-shadow: 1px -1px 5px 3px rgba(0, 0, 0, 0.17);
            height: 305px;
            border-radius: 5px;
        }

        @media screen and (min-width:768px) {
            .card.is-torrent {
                margin-top: 30px
            }
        }

        .card.is-torrent .card_head {
            align-items: center;
            background: #f3f3f3;
            box-shadow: 0 1px 5px 0 rgba(0, 0, 0, .05);
            height: 34px;
            padding: 5px 10px;
            position: relative;
            border-top-right-radius: 5px;
            border-top-left-radius: 5px;
        }

        .card.is-torrent .body_poster img {
            height: 231px
        }

        .card.is-torrent .poster_placeholder img {
            height: 80px;
            opacity: .6
        }

        .card.is-torrent .card_body {
            display: flex;
            height: 231px
        }

        .card.is-torrent .poster_placeholder {
            align-items: center;
            background: #e4e4e4;
            display: flex;
            height: 231px;
            justify-content: center;
            width: 154px
        }

        .card.is-torrent .card_footer {
            align-items: center;
            background: #f3f3f3;
            box-shadow: 0 -1px 5px 0 rgba(0, 0, 0, .05);
            color: #70727c;
            font-size: 1.3rem;
            height: 34px;
            padding: 6px
        }

        .card.is-torrent .card_footer a {
            color: #70727c
        }

        .card.is-torrent .card_footer a:hover {
            color: #3492ad
        }

        .card.is-torrent .card_footer img {
            height: 12px;
            margin: 0 5px 0 15px
        }

        .card.is-torrent .body_description {
            height: 231px;
            overflow: hidden;
            padding: 14px 17px
        }

        .card.is-torrent .body_description:hover {
            overflow-y: auto
        }

        .card.is-torrent .footer_network:before {
            content: "-";
            padding: 0 5px
        }

        .card.is-torrent .description_title {
            font-size: 2rem;
            font-weight: 800;
            margin: 0;
            text-transform: uppercase
        }

        .card.is-torrent .description_plot {
            color: #70727c;
            font-size: 1.2rem;
            margin-top: 10px
        }

        .card.is-torrent .genre-label {
            box-shadow: 0 1px 10px 0 rgba(211, 215, 221, .7);
            color: #9d9ea1;
            display: inline-block;
            font-size: .9rem;
            font-weight: 800;
            margin-top: .5rem;
            padding: 3px;
            text-transform: uppercase
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block">
            <div class="row">
                @foreach($torrents as $k => $t)
                    @php $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb')); @endphp
                    @if ($t->category_id == 2)
                        @if ($t->tmdb || $t->tmdb != 0)
                            @php $movie = $client->scrape('tv', null, $t->tmdb); @endphp
                        @else
                            @php $movie = $client->scrape('tv', 'tt'. $t->imdb); @endphp
                        @endif
                    @else
                        @if ($t->tmdb || $t->tmdb != 0)
                            @php $movie = $client->scrape('movie', null, $t->tmdb); @endphp
                        @else
                            @php $movie = $client->scrape('movie', 'tt'. $t->imdb); @endphp
                        @endif
                    @endif
                <div class="col-md-4">
                    <div class="card is-torrent">
                        <div class="card_head">
                            <span class="badge-user text-bold" style="float:right;">
                                <i class="fa fa-fw fa-arrow-up text-green"></i> {{ $t->seeders }} /
                                <i class="fa fa-fw fa-arrow-down text-red"></i> {{ $t->leechers }} /
                                <i class="fa fa-fw fa-check text-orange"></i>{{ $t->times_completed }}
                            </span>&nbsp;
                            <span class="badge-user text-bold text-blue" style="float:right;">{{ $t->getSize() }}</span>&nbsp;
                            <span class="badge-user text-bold text-blue" style="float:right;">{{ $t->type }}</span>&nbsp;
                            <span class="badge-user text-bold text-blue" style="float:right;">{{ $t->category->name }}</span>&nbsp;
                        </div>

                        <div class="card_body">
                            <div class="body_poster">
                                <img src="{{ $movie->poster }}">
                            </div>
                            <div class="body_description">
                                        <h3 class="description_title">
                                            <a href="{{ route('torrent', ['slug' => $t->slug, 'id' => $t->id]) }}">{{ $t->name }}
                                                <span class="text-bold text-pink"> {{ $movie->releaseYear }}</span>
                                            </a>
                                        </h3>
                                    @if($movie->genres)
                                        @foreach($movie->genres as $genre)
                                            <span class="genre-label">{{ $genre }}</span>
                                        @endforeach
                                    @endif
                                <p class="description_plot">
                                   {{ $movie->plot }}
                                </p>
                            </div>
                        </div>

                        <div class="card_footer">
                            <div style="float: left;">
                                @if($t->anon == 1)
                                    <span class="badge-user text-orange text-bold">{{ strtoupper(trans('common.anonymous')) }} @if(auth()->user()->id == $t->user->id || auth()->user()->group->is_modo)
                                            <a href="{{ route('profile', ['username' => $t->user->username, 'id' => $t->user->id]) }}">({{ $t->user->username }}
                                                )</a>@endif</span>
                                @else
                                    <a href="{{ route('profile', ['username' => $t->user->username, 'id' => $t->user->id]) }}">
                                        <span class="badge-user text-bold" style="color:{{ $t->user->group->color }}; background-image:{{ $t->user->group->effect }};">
                                            <i class="{{ $t->user->group->icon }}" data-toggle="tooltip" title=""
                                                    data-original-title="{{ $t->user->group->name }}"></i> {{ $t->user->username }}
                                        </span>
                                    </a>
                                @endif
                            </div>

                            <span class="badge-user text-bold" style="float: right;">
                                <i class="fa fa-star text-gold"></i>
                                    @if($user->ratings == 1)
                                        {{ $movie->imdbRating }}/10 ({{ $movie->imdbVotes }} {{ trans('torrent.votes') }})
                                    @else
                                        {{ $movie->tmdbRating }}/10 ({{ $movie->tmdbVotes }} {{ trans('torrent.votes') }})
                                    @endif
                            </span>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>
            <div class="align-center">{{ $torrents->links() }}</div>
        </div>
    </div>
@endsection
