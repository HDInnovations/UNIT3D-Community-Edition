<div class="movie-wrapper">
    <div class="movie-backdrop"
         style="background-image: url({{ $meta->backdrop ?? 'https://via.placeholder.com/1400x800' }});">
        <div class="tags">
            {{ $torrent->category->name }}
        </div>
    </div>
    <div class="movie-overlay"></div>
    <div class="container movie-container">
        <div class="row movie-row ">

            <div class="col-xs-12 col-sm-8 col-md-8 col-sm-push-4 col-md-push-3 movie-heading-box">
                <h1 class="movie-heading">
                    <span class="text-bold">{{ $meta->title ?? trans('torrent.no-meta') }}</span>
                    <span class="text-bold"><em> {{ $meta->releaseYear ?? '' }}</em></span>
                </h1>

                <br>

                <span class="movie-overview">
                    {{ Str::limit($meta->plot ?? 'No Plot Found', $limit = 350, $end = '...') }}
                </span>

                <span class="movie-details">
                    @if (isset($meta->genres))
                        @foreach ($meta->genres as $genre)
                            <span class="badge-user text-bold text-green">
                                <i class="{{ config('other.font-awesome') }} fa-tag"></i> {{ $genre }}
                            </span>
                        @endforeach
                    @endif
                </span>

                <span class="movie-details">
                    <span class="badge-user text-bold text-orange">
                        @lang('torrent.rated'): {{ $meta->rated ?? 'Unknown' }}
                    </span>

                    <span class="badge-user text-bold text-orange">
                        @lang('torrent.runtime'): {{ $meta->runtime ?? 'Unknown' }}
                        @lang('common.minute')@lang('common.plural-suffix')
                    </span>

                    <span class="badge-user text-bold text-gold">@lang('torrent.rating'):
                        <span class="movie-rating-stars">
                            <i class="{{ config('other.font-awesome') }} fa-star"></i>
                        </span>
                        @if ($user->ratings == 1)
                            {{ $meta->imdbRating ?? '0' }}/10 ({{ $meta->imdbVotes ?? '0' }} @lang('torrent.votes'))
                        @else
                            {{ $meta->tmdbRating ?? '0' }}/10 ({{ $meta->tmdbVotes ?? '0' }} @lang('torrent.votes'))
                        @endif
                    </span>
                </span>

                <span class="movie-details">
                    @if ($torrent->category->movie_meta || $torrent->category->tv_meta && $torrent->imdb != 0 &&
                        $torrent->imdb != null)
                        <span class="badge-user text-bold text-orange">
                            <a href="https://www.imdb.com/title/tt{{ $torrent->imdb }}" title="IMDB" target="_blank">
                                <i class="{{ config('other.font-awesome') }} fa-film"></i> IMDB: {{ $torrent->imdb }}
                            </a>
                        </span>
                    @endif

                    @if ($torrent->category->tv_meta && $torrent->tmdb != 0 && $torrent->tmdb != null)
                        <span class="badge-user text-bold text-orange">
                            <a href="https://www.themoviedb.org/tv/{{ $torrent->tmdb }}" title="TheMovieDatabase"
                               target="_blank">
                                <i class="{{ config('other.font-awesome') }} fa-film"></i> TMDB: {{ $torrent->tmdb }}
                            </a>
                        </span>
                    @endif

                    @if ($torrent->category->movie_meta && $torrent->tmdb != 0 && $torrent->tmdb != null)
                        <span class="badge-user text-bold text-orange">
                            <a href="https://www.themoviedb.org/movie/{{ $torrent->tmdb }}" title="TheMovieDatabase"
                               target="_blank">
                                <i class="{{ config('other.font-awesome') }} fa-film"></i> TMDB: {{ $torrent->tmdb }}
                            </a>
                        </span>
                    @endif

                    @if (($torrent->category->movie_meta || $torrent->category->tv_meta) && $torrent->mal != 0 &&
                        $torrent->mal != null)
                        <span class="badge-user text-bold text-pink">
                            <a href="https://myanimelist.net/anime/{{ $torrent->mal }}" title="MAL" target="_blank">
                                <i class="{{ config('other.font-awesome') }} fa-film"></i> MAL: {{ $torrent->mal }}</a>
                        </span>
                    @endif

                    @if ($torrent->category->tv_meta && $torrent->tvdb != 0 && $torrent->tvdb != null)
                        <span class="badge-user text-bold text-pink">
                            <a href="https://www.thetvdb.com/?tab=series&id={{ $torrent->tvdb }}" title="TVDB"
                               target="_blank">
                                <i class="{{ config('other.font-awesome') }} fa-film"></i> TVDB: {{ $torrent->tvdb }}
                            </a>
                        </span>
                    @endif

                    @if (isset($meta->videoTrailer) && $meta->videoTrailer != '')
                        <span style="cursor: pointer;" class="badge-user text-bold show-trailer">
                            <a class="text-pink" title="@lang('torrent.trailer')">
                                <i class="{{ config('other.font-awesome') }} fa-external-link"></i> @lang('torrent.trailer')
                            </a>
                        </span>
                    @endif

                    <div class="row cast-list">
                        @if (isset($meta->actors))
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
                <img src="{{ $meta->poster ?? 'https://via.placeholder.com/600x900' }}" alt="{{ $meta->title  ?? ''}}"
                     class="movie-poster img-responsive hidden-xs">
            </div>

        </div>
    </div>
</div>
