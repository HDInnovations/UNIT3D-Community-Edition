@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('cards') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Torrents Card View</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block">
            <div class="row">
                @foreach ($torrents as $k => $t)
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
                                <i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-up text-green"></i> {{ $t->seeders }} /
                                <i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-down text-red"></i> {{ $t->leechers }} /
                                <i class="{{ config('other.font-awesome') }} fa-fw fa-check text-orange"></i>{{ $t->times_completed }}
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
                                    @if ($movie->genres)
                                        @foreach ($movie->genres as $genre)
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
                                @if ($t->anon == 1)
                                    <span class="badge-user text-orange text-bold">{{ strtoupper(trans('common.anonymous')) }} @if (auth()->user()->id == $t->user->id || auth()->user()->group->is_modo)
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
                                <i class="{{ config('other.font-awesome') }} fa-star text-gold"></i>
                                    @if ($user->ratings == 1)
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
