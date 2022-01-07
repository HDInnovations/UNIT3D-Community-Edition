<div class="movie-wrapper">
    <div class="movie-overlay"></div>
    <div class="movie-poster">
        <a href="{{ route('torrents.similar', ['category_id' => $torrent->category_id, 'tmdb' => $torrent->tmdb]) }}">
            <img src="{{ ($meta && $meta->poster) ? tmdb_image('poster_big', $meta->poster) : 'https://via.placeholder.com/400x600' }}"
                 class="img-responsive" id="meta-poster">
        </a>
    </div>
    <div class="meta-info">
        <div class="tags">
            {{ $torrent->category->name }}
        </div>

        <div class="movie-right">
            @if(isset($meta->companies) && $meta->companies->isNotEmpty())
                @php $company = $meta->companies->first() @endphp
                <div class="badge-user">
                    <a href="{{ route('mediahub.companies.show', ['id' => $company->id]) }}">
                        @if(isset($company->logo))
                            <img class="img-responsive" src="{{ tmdb_image('logo_small', $company->logo) }}"
                                 title="{{ $company->name }}">
                        @else
                            {{ $company->name }}
                        @endif
                    </a>
                </div>
            @endif
        </div>

        <div class="movie-backdrop"
             style="background-image: url('{{ ($meta && $meta->backdrop) ? tmdb_image('back_big', $meta->backdrop) : 'https://via.placeholder.com/960x540' }}');"></div>

        <div class="movie-top">
            <h1 class="movie-heading" style="margin-bottom: 0;">
                <a href="{{ route('torrents.similar', ['category_id' => $torrent->category_id, 'tmdb' => $torrent->tmdb]) }}">
                    <span class="text-bright text-bold"
                          style="font-size: 28px;">{{ $meta->title ?? 'No Meta Found' }}</span>
                </a>
                @if(isset($meta->release_date))
                    <span style="font-size: 28px;"> ({{ substr($meta->release_date, 0, 4) ?? '' }})</span>
                @endif
            </h1>

            <div class="movie-overview">
                {{ isset($meta->overview) ? Str::limit($meta->overview, $limit = 350, $end = '...') : '' }}
            </div>
        </div>

        <div class="movie-bottom">
            <div class="movie-details">
                @if(isset($meta->crew))
                    @if(!empty($directors = $meta->crew()->where('known_for_department' ,'=', 'Directing')->take(1)->get()))
                        <span class="badge-user text-bold text-purple">
                        <i class="{{ config('other.font-awesome') }} fa-camera-movie"></i> Directors:
                        @foreach($directors as $director)
                                <a href="{{ route('mediahub.persons.show', ['id' => $director->id]) }}"
                                   style="display: inline-block;">
                                {{ $director->name }}
                            </a>
                                @if (! $loop->last)
                                    ,
                                @endif
                            @endforeach
                    </span>
                    @endif
                @endif
                <br>
                @if ($torrent->imdb != 0 && $torrent->imdb != null)
                    <span class="badge-user text-bold">
                    <a href="https://www.imdb.com/title/tt{{ $torrent->imdb }}" title="IMDB" target="_blank">
                        <i class="{{ config('other.font-awesome') }} fa-film"></i> IMDB: {{ $torrent->imdb }}
                    </a>
                </span>
                @endif

                @if ($torrent->tmdb != 0 && $torrent->tmdb != null)
                    <span class="badge-user text-bold">
                    <a href="https://www.themoviedb.org/movie/{{ $torrent->tmdb }}" title="The Movie Database"
                       target="_blank">
                        <i class="{{ config('other.font-awesome') }} fa-film"></i> TMDB: {{ $torrent->tmdb }}
                    </a>
                </span>
                @endif

                @if ($torrent->mal != 0 && $torrent->mal != null)
                    <span class="badge-user text-bold">
                    <a href="https://myanimelist.net/anime/{{ $torrent->mal }}" title="MyAnimeList" target="_blank">
                        <i class="{{ config('other.font-awesome') }} fa-film"></i> MAL: {{ $torrent->mal }}</a>
                </span>
                @endif

                @if (isset($trailer))
                    <span style="cursor: pointer;" class="badge-user text-bold show-trailer">
                        <a class="text-pink" title="{{ __('torrent.trailer') }}">{{ __('torrent.trailer') }}
                            <i class="{{ config('other.font-awesome') }} fa-external-link"></i>
                        </a>
                    </span>
                @endif

                <br>
                @if (isset($meta->genres) && $meta->genres->isNotEmpty())
                    @foreach ($meta->genres as $genre)
                        <span class="badge-user text-bold text-green">
                    <a href="{{ route('mediahub.genres.show', ['id' => $genre->id]) }}">
                        <i class="{{ config('other.font-awesome') }} fa-theater-masks"></i> {{ $genre->name }}
                    </a>
                </span>
                    @endforeach
                @endif

                <br>
                @if ($torrent->keywords)
                    @foreach ($torrent->keywords as $keyword)
                        <span class="badge-user text-bold text-green">
                            <a href="{{ route('torrents') }}?keywords={{ $keyword->name }}">
                                <i class="{{ config('other.font-awesome') }} fa-tag"></i> {{ $keyword->name }}
                            </a>
                        </span>
                    @endforeach
                @endif
            </div>

            <div class="movie-details">
                @if(isset($meta) && !empty(trim($meta->homepage)))
                    <span class="badge-user text-bold">
                    <a href="{{ $meta->homepage }}" title="Homepage" rel="noopener noreferrer" target="_blank">
                        <i class="{{ config('other.font-awesome') }} fa-external-link-alt"></i> Homepage
                    </a>
                </span>
                @endif

                <span class="badge-user text-bold text-orange">
                    Status: {{ $meta->status ?? 'Unknown' }}
                </span>

                @if (isset($meta->runtime))
                    <span class="badge-user text-bold text-orange">
                    {{ __('torrent.runtime') }}: {{ $meta->runtime }}
                        {{ __('common.minute') }}{{ __('common.plural-suffix') }}
                </span>
                @endif

                <span class="badge-user text-bold text-gold">{{ __('torrent.rating') }}:
                    <span class="movie-rating-stars">
                        <i class="{{ config('other.font-awesome') }} fa-star"></i>
                    </span>
                    {{ $meta->vote_average ?? 0 }}/10 ({{ $meta->vote_count ?? 0 }} {{ __('torrent.votes') }})
                </span>
            </div>

            <div class="cast-list">
                @if (isset($meta->cast) && $meta->cast->isNotEmpty())
                    @foreach ($meta->cast->sortBy('order')->take(7) as $cast)
                        <div class="cast-item" style="max-width: 80px;">
                            <a href="{{ route('mediahub.persons.show', ['id' => $cast->id]) }}" class="badge-user">
                                <img class="img-responsive"
                                     src="{{ $cast->still ? tmdb_image('cast_face', $cast->still) : 'https://via.placeholder.com/138x175' }}"
                                     alt="{{ $cast->name }}">
                                <div class="cast-name">{{ $cast->name }}</div>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
