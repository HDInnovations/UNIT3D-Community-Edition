<div class="movie-wrapper">
    <div class="movie-backdrop"
        style="background-image: url({{ $meta->backdrop ?? 'https://via.placeholder.com/1400x800' }});">
        <div class="tags">
            {{ $torrentRequest->category->name }}
        </div>
    </div>
    <div class="movie-overlay"></div>
    <div class="container movie-container">
        <div class="row movie-row ">

            <div class="col-xs-12 col-sm-8 col-md-8 col-sm-push-4 col-md-push-3 movie-heading-box">
                <h1 class="movie-heading">
                    @if ($meta->title)
                        <span class="text-bold">{{ $meta->title }}</span>
                        <span class="text-bold"><em> {{ $meta->releaseYear }}</em></span>
                    @else
                        <span class="text-bold">@lang('torrent.no-meta')</span>
                    @endif
                </h1>

                <br>

                <span class="movie-overview">
                    {{ Str::limit($meta->plot, $limit = 350, $end = '...') }}
                </span>

                <span class="movie-details">
                    @if ($meta->genres)
                        @foreach ($meta->genres as $genre)
                            <span class="badge-user text-bold text-green">
                                <i class="{{ config('other.font-awesome') }} fa-tag"></i> {{ $genre }}
                            </span>
                        @endforeach
                    @endif
                </span>

                <span class="movie-details">
                    @if ($meta->rated )
                        <span class="badge-user text-bold text-orange">
                            @lang('torrent.rated'): {{ $meta->rated }}
                        </span>
                    @endif

                    @if ($meta->runtime )
                        <span class="badge-user text-bold text-orange">
                            @lang('torrent.runtime'): {{ $meta->runtime }}
                            @lang('common.minute')@lang('common.plural-suffix')
                        </span>
                    @endif

                    @if ($meta->imdbRating || $meta->tmdbRating)
                        <span class="badge-user text-bold text-gold">@lang('torrent.rating'):
                            <span class="movie-rating-stars">
                                <i class="{{ config('other.font-awesome') }} fa-star"></i>
                            </span>
                            @if ($user->ratings == 1)
                                {{ $meta->imdbRating }}/10 ({{ $meta->imdbVotes }} @lang('torrent.votes'))
                            @else
                                {{ $meta->tmdbRating }}/10 ({{ $meta->tmdbVotes }} @lang('torrent.votes'))
                            @endif
                        </span>
                    @endif
                </span>

                <span class="movie-details">
                    @if ($torrentRequest->category->movie_meta || $torrentRequest->category->tv_meta &&
                        $torrentRequest->imdb != 0 && $torrentRequest->imdb != null)
                        <span class="badge-user text-bold text-orange">
                            <a href="https://www.imdb.com/title/tt{{ $torrentRequest->imdb }}" title="IMDB" target="_blank">
                                <i class="{{ config('other.font-awesome') }} fa-film"></i> IMDB: {{ $torrentRequest->imdb }}
                            </a>
                        </span>
                    @endif

                    @if ($torrentRequest->category->tv_meta && $torrentRequest->tmdb != 0 && $torrentRequest->tmdb !=
                        null)
                        <span class="badge-user text-bold text-orange">
                            <a href="https://www.themoviedb.org/tv/{{ $meta->tmdb }}" title="TheMovieDatabase"
                                target="_blank">
                                <i class="{{ config('other.font-awesome') }} fa-film"></i> TMDB: {{ $meta->tmdb }}
                            </a>
                        </span>
                    @endif

                    @if ($torrentRequest->category->movie_meta && $torrentRequest->tmdb != 0 && $torrentRequest->tmdb !=
                        null)
                        <span class="badge-user text-bold text-orange">
                            <a href="https://www.themoviedb.org/movie/{{ $meta->tmdb }}" title="TheMovieDatabase"
                                target="_blank">
                                <i class="{{ config('other.font-awesome') }} fa-film"></i> TMDB: {{ $meta->tmdb }}
                            </a>
                        </span>
                    @endif

                    @if ($torrentRequest->category->movie_meta || $torrentRequest->category->tv_meta &&
                        $torrentRequest->mal != 0 && $torrentRequest->mal != null)
                        <span class="badge-user text-bold text-pink">
                            <a href="https://myanimelist.net/anime/{{ $torrentRequest->mal }}" title="MAL" target="_blank">
                                <i class="{{ config('other.font-awesome') }} fa-film"></i> MAL:
                                {{ $torrentRequest->mal }}</a>
                        </span>
                    @endif

                    @if ($torrentRequest->category->tv_meta && $torrentRequest->tvdb != 0 && $torrentRequest->tvdb !=
                        null)
                        <span class="badge-user text-bold text-pink">
                            <a href="https://www.thetvdb.com/?tab=series&id={{ $torrentRequest->tvdb }}" title="TVDB"
                                target="_blank">
                                <i class="{{ config('other.font-awesome') }} fa-film"></i> TVDB: {{ $torrentRequest->tvdb }}
                            </a>
                        </span>
                    @endif

                    @if ($meta->videoTrailer != '')
                        <span style="cursor: pointer;" class="badge-user text-bold show-trailer">
                            <a class="text-pink" title="@lang('torrent.trailer')">
                                <i class="{{ config('other.font-awesome') }} fa-external-link"></i> @lang('torrent.trailer')
                            </a>
                        </span>
                    @endif

                    <div class="row cast-list">
                        @if ($meta->actors)
                            @php $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'),
                            config('api-keys.omdb')); @endphp
                            @foreach (array_slice($meta->actors, 0,6) as $actor)
                        @php $person = $client->person($actor->tmdb); @endphp
                                <div class="col-xs-4 col-md-2 text-center">
                                    <img class="img-people" src="{{ $person->photo }}" alt="{{ $actor->name }}">
                                    <a href="https://www.themoviedb.org/person/{{ $actor->tmdb }}" title="TheMovieDatabase"
                                        target="_blank">
                                        <span class="badge-user" style="white-space:normal;">
                                            <strong>{{ $actor->name }}</strong>
                                        </span>
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </span>
            </div>

            <div class="col-xs-12 col-sm-4 col-md-3 col-sm-pull-8 col-md-pull-8">
                <img src="{{ $meta->poster ?? 'https://via.placeholder.com/600x900' }}" alt="{{ $meta->title }}"
                    class="movie-poster img-responsive hidden-xs">
            </div>

        </div>
    </div>
</div>
