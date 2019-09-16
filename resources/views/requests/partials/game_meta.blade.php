<div class="movie-wrapper">
    <div class="movie-backdrop"
         @if (isset($meta) && $meta->artworks)
            style="background-image: url('https://images.igdb.com/igdb/image/upload/t_original/{{ $meta->artworks[0]['image_id'] }}.jpg')">
         @else
            style="background-image: url('https://via.placeholder.com/1400x800')">
         @endif
        <div class="tags">
            {{ $torrentRequest->category->name }}
        </div>
    </div>
    <div class="movie-overlay"></div>
    <div class="container movie-container">
        <div class="row movie-row ">
            <div class="col-xs-12 col-sm-8 col-md-8 col-sm-push-4 col-md-push-3 movie-heading-box">
                <h1 class="movie-heading">
                    @if (isset($meta) && $meta->name)
                        <span class="text-bold">{{ $meta->name }}</span>
                        <span class="text-bold"><em> ({{ date('Y', strtotime( $meta->first_release_date)) }}) </em></span>
                    @else
                        <span class="text-bold">@lang('torrent.no-meta')</span>
                    @endif
                </h1>

                <br>

                <span class="movie-overview">
                    @if (isset($meta) && $meta->summary)
                        {{ Str::limit($meta->summary, $limit = 350, $end = '...') }}
                    @endif
                </span>

                <span class="movie-details">
                    @if (isset($meta) && $meta->genres)
                        @foreach ($meta->genres as $genre)
                            <span class="badge-user text-bold text-green">
                                <i class="{{ config("other.font-awesome") }} fa-tag"></i> {{ $genre->name }}
                            </span>
                        @endforeach
                    @endif
                </span>

                <span class="movie-details">
                    @if (isset($meta) && $meta->url && $torrentRequest->igdb != 0 && $torrentRequest->igdb != null)
                        <span class="badge-user text-bold text-orange">
                            <a href="{{ $meta->url }}" title="IMDB" target="_blank">
                                <i class="{{ config("other.font-awesome") }} fa-gamepad"></i> IGDB: {{ $torrentRequest->igdb }}
                            </a>
                        </span>
                    @endif

                    @if (isset($meta) && $meta->videos)
                        <span style="cursor: pointer;" class="badge-user text-bold show-trailer">
                            <a class="text-pink" title="@lang('torrent.trailer')">
                                <i class="{{ config('other.font-awesome') }} fa-external-link"></i> @lang('torrent.trailer')
                            </a>
                        </span>
                    @endif

                    @if (isset($meta) && $meta->rating && $meta->rating_count)
                        <span class="badge-user text-bold text-gold">@lang('torrent.rating'):
                            <span class="movie-rating-stars">
                                <i class="{{ config('other.font-awesome') }} fa-star"></i>
                            </span>
                            {{ $meta->rating }}/100 ({{ $meta->rating_count }} @lang('torrent.votes'))
                        </span>
                    @endif

                    <div class="row cast-list">
                        @if (isset($characters))
                            @foreach($characters as $character)
                                <div class="col-xs-4 col-md-2 text-center">
                                    <img class="img-people" src="{{ $character->img_url }}" alt="{{ $character->name }}">
                                    <a href="https://www.themoviedb.org/person/{{ $character->id }}" title="TheMovieDatabase" target="_blank">
                                        <span class="badge-user" style="white-space:normal;">
                                            <strong>{{ $character->name }}</strong>
                                        </span>
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </span>

            </div>

            <div class="col-xs-12 col-sm-4 col-md-3 col-sm-pull-8 col-md-pull-8">
                @if (isset($meta) && $meta->cover)
                    <img src="https://images.igdb.com/igdb/image/upload/t_original/{{ $meta->cover->image_id }}.jpg" alt="{{ $meta->name }}"
                         class="movie-poster img-responsive hidden-xs">
                @else
                    <img src="https://via.placeholder.com/600x900" alt="{{ $meta->name }}"
                         class="movie-poster img-responsive hidden-xs">
                @endif
            </div>
        </div>
    </div>
</div>