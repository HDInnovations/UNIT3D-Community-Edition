<div class="movie-wrapper">
    <div class="movie-backdrop"
         style="background-image: url('{{ $meta->backdrop ?? 'https://via.placeholder.com/1400x800' }}');">
        <div class="tags">
            {{ $torrent->category->name }}
        </div>
    </div>
    <div class="movie-overlay"></div>
    <div class="container movie-container">
        <div class="row movie-row ">

            <div class="col-xs-12 col-sm-8 col-md-8 col-sm-push-4 col-md-push-3 movie-heading-box">
                <h1 class="movie-heading">
                    <span class="text-bold">{{ $meta->title ?? 'No Meta Found' }}</span>
                    @if(isset($meta->release_date))
                    <span class="text-bold"><em> ({{ substr($meta->release_date, 0, 4) }})</em></span>
                    @endif
                </h1>

                <br>

                <span class="movie-overview">
                    {{ Str::limit($meta->overview ?? '', $limit = 350, $end = '...') }}
                </span>

                <span class="movie-details">
                    @if (isset($meta->genres))
                        @foreach ($meta->genres as $genre)
                            <span class="badge-user text-bold text-green">
                                <i class="{{ config('other.font-awesome') }} fa-tag"></i> {{ $genre->name }}
                            </span>
                        @endforeach
                    @endif
                </span>

                <span class="movie-details">
                    <span class="badge-user text-bold text-orange">
                        Status: {{ $meta->status ?? 'Unknown' }}
                    </span>

                    <span class="badge-user text-bold text-orange">
                        @lang('torrent.runtime'): {{ $meta->runtime ?? 0 }}
                        @lang('common.minute')@lang('common.plural-suffix')
                    </span>

                    <span class="badge-user text-bold text-gold">@lang('torrent.rating'):
                        <span class="movie-rating-stars">
                            <i class="{{ config('other.font-awesome') }} fa-star"></i>
                        </span>
                            {{ $meta->vote_average ?? '0' }}/10 ({{ $meta->vote_count ?? '0' }} @lang('torrent.votes'))
                    </span>
                </span>

                <span class="movie-details">
                    @if(isset($meta->crew))
                        @php $director = $meta->crew->where('known_for_department' ,'=', 'Directing')->take(1)->first(); @endphp
                    @if($director)
                        <span class="badge-user text-bold text-orange">
                            <a href="{{ route('mediahub.persons.show', ['id' => $director->id]) }}" target="_blank">
                                <i class="{{ config('other.font-awesome') }} fa-camera-movie"></i> Director: {{ $director->name }}
                            </a>
                        </span>
                    @endif
                    @endif

                    @if ($torrent->imdb != 0 && $torrent->imdb != null)
                        <span class="badge-user text-bold text-orange">
                            <a href="https://www.imdb.com/title/tt{{ $torrent->imdb }}" title="IMDB" target="_blank">
                                <i class="{{ config('other.font-awesome') }} fa-film"></i> IMDB: {{ $torrent->imdb }}
                            </a>
                        </span>
                    @endif

                    @if ($torrent->tmdb != 0 && $torrent->tmdb != null)
                        <span class="badge-user text-bold text-orange">
                            <a href="https://www.themoviedb.org/movie/{{ $torrent->tmdb }}" title="TheMovieDatabase"
                               target="_blank">
                                <i class="{{ config('other.font-awesome') }} fa-film"></i> TMDB: {{ $torrent->tmdb }}
                            </a>
                        </span>
                    @endif

                    @if ($torrent->mal != 0 && $torrent->mal != null)
                        <span class="badge-user text-bold text-pink">
                            <a href="https://myanimelist.net/anime/{{ $torrent->mal }}" title="MAL" target="_blank">
                                <i class="{{ config('other.font-awesome') }} fa-film"></i> MAL: {{ $torrent->mal }}</a>
                        </span>
                    @endif

                    <span class="badge-user text-bold">
                        <a href="{{ route('upload_form', ['category_id' => $torrent->category_id, 'title' => $meta->title ?? 'Unknown', 'imdb' => $torrent->imdb, 'tmdb' => $torrent->tmdb]) }}">
                            @lang('common.upload') {{ $meta->title ?? 'Unknown' }}
                        </a>
                    </span>

                    <div class="row cast-list">
                        @if (isset($meta->cast))
                            @foreach ($meta->cast->sortBy('order')->take(6) as $cast)
                                <div class="col-xs-4 col-md-2 text-center">
                                    <a href="{{ route('mediahub.persons.show', ['id' => $cast->id]) }}">
                                        <img class="img-people" src="{{ $cast->still ?? 'https://via.placeholder.com/95x140' }}"
                                             alt="{{ $cast->name }}">
                                        <span class="badge-user" style="white-space:normal;">
                                            <strong>{{ $cast->name }}</strong>
                                        </span>
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </span>
            </div>

            <div class="col-xs-12 col-sm-4 col-md-3 col-sm-pull-8 col-md-pull-8">
                <img src="{{ $meta->poster ?? 'https://via.placeholder.com/600x900' }}"
                     class="movie-poster img-responsive hidden-xs">
            </div>

        </div>
    </div>
</div>
