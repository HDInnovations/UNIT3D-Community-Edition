<div class="mobile-hide">
    <div class="col-md-10 col-sm-10 col-md-offset-1">
        <div class="clearfix visible-sm-block"></div>
        <div class="panel panel-chat shoutbox">
            <div class="panel-heading featured-header">
                <h4><i class="{{ config('other.font-awesome') }} fa-star"></i> {{ __('blocks.featured-torrents') }}</h4>
                <div id="dots" class="dots"></div>
            </div>
            <div id="myCarousel" class="keen-slider">
                <div class="keen-slider__slide" style="opacity: 1">
                    <div class="movie-image">
                        <img class="backdrop" src="/img/default_featured.jpg">
                        <div class="fade-img"></div>
                    </div>
                    <div class="movie-card">
                        <div class="fade-card"></div>
                        <div class="movie-content">
                            <div class="movie-header">
                                <h1 class="movie-title">{{ __('blocks.featured-torrents') }}</h1>
                                <h4 class="movie-info">
                                    {{ __('blocks.featured-torrents-intro') }}
                                    <br>
                                    <br>
                                    <span class="badge-user text-bold text-pink"
                                          style="background-image:url(/img/sparkels.gif);">{{ __('torrent.freeleech') }}</span>
                                    <span class="badge-user text-bold text-pink"
                                          style="background-image:url(/img/sparkels.gif);">{{ __('torrent.double-upload') }}</span>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                @php $meta = null @endphp
                @foreach ($featured as $key => $feature)
                    @if ($feature->torrent->category->tv_meta)
                        @if ($feature->torrent->tmdb || $feature->torrent->tmdb != 0)
                            @php $meta = App\Models\Tv::with('genres', 'networks', 'seasons')->where('id', '=', $feature->torrent->tmdb)->first() @endphp
                        @endif
                    @endif
                    @if ($feature->torrent->category->movie_meta)
                        @if ($feature->torrent->tmdb || $feature->torrent->tmdb != 0)
                            @php $meta = App\Models\Movie::with('genres', 'cast', 'companies', 'collection')->where('id', '=', $feature->torrent->tmdb)->first() @endphp
                        @endif
                    @endif
                    @if ($feature->torrent->category->game_meta)
                        @php $meta = MarcReichel\IGDBLaravel\Models\Game::with(['artworks' => ['url', 'image_id'], 'genres' => ['name']])->find((int) $feature->torrent->igdb) @endphp
                    @endif
                    <div class="keen-slider__slide">
                        <div class="movie-image">
                            <img class="backdrop" src=
                            @if ($feature->torrent->category->tv_meta || $feature->torrent->category->movie_meta)
                                    "{{ isset($meta->backdrop) ? tmdb_image('back_small', $meta->backdrop) : 'https://via.placeholder.com/533x300' }}">
                            @elseif ($feature->torrent->category->game_meta && isset($meta) && $meta->artworks)
                                "https://images.igdb.com/igdb/image/upload/t_screenshot_med/{{ $meta->artworks[0]['image_id'] }}
                                .jpg">
                            @elseif ($feature->torrent->category->no_meta && file_exists(public_path().'/files/img/torrent-banner_'.$feature->torrent->id.'.jpg'))
                                "{{'/files/img/torrent-banner_'.$feature->torrent->id.'.jpg'}}">
                            @else
                                "{{'https://via.placeholder.com/533x300'}}">
                            @endif
                            <div class="fade-img"></div>
                        </div>
                        <div class="movie-card">
                            <div class="fade-card"></div>
                            <div class="movie-content">
                                <div class="movie-header">
                                    <a href="{{ route('torrent', ['id' => $feature->torrent->id]) }}">
                                        <h1 class="movie-title">{{ $feature->torrent->name }}</h1>
                                    </a>
                                    <h4 class="movie-info">
                                        @if (isset($meta) && isset($meta->genres))
                                            @foreach ($meta->genres as $genre)
                                                @if ($feature->torrent->category->tv_meta || $feature->torrent->category->movie_meta)
                                                    | {{ $genre->name }}
                                                @endif
                                                @if ($feature->torrent->category->game_meta)
                                                    | {{ $genre['name'] }}
                                                @endif
                                            @endforeach
                                            |
                                        @endif
                                    </h4>
                                </div>
                                <div class="movie-desc">
                                    @if ($feature->torrent->category->tv_meta ||
                                         $feature->torrent->category->movie_meta)
                                        {{ Str::limit(strip_tags($meta->overview ?? ''), 200) }}...
                                    @endif
                                    @if ($feature->torrent->category->game_meta && isset($meta) &&
                                         $meta->summary)
                                        {{ Str::limit(strip_tags($meta->summary ?? ''), 200) }}...
                                    @endif
                                </div>
                                <div class="movie-badges list-inline">
                                    <span class="badge-extra text-blue"><i
                                                class="{{ config('other.font-awesome') }} fa-database"></i>
                                        <strong>{{ __('torrent.size') }}: </strong>
                                        {{ $feature->torrent->getSize() }}
                                    </span>
                                    <span class="badge-extra text-blue"><i
                                                class="{{ config('other.font-awesome') }} fa-fw fa-clock"></i>
                                        <strong>{{ __('torrent.released') }}: </strong>
                                        {{ $feature->torrent->created_at->diffForHumans() }}
                                    </span>
                                    <span class="badge-extra text-green"><i
                                                class="{{ config('other.font-awesome') }} fa-arrow-up"></i>
                                        <strong>{{ __('torrent.seeders') }}: </strong>
                                        {{ $feature->torrent->seeders }}
                                    </span>
                                    <span class="badge-extra text-red"><i
                                                class="{{ config('other.font-awesome') }} fa-arrow-down"></i>
                                        <strong>{{ __('torrent.leechers') }}: </strong>
                                        {{ $feature->torrent->leechers }}
                                    </span>
                                    <span class="badge-extra text-orange"><i
                                                class="{{ config('other.font-awesome') }} fa-check-square"></i>
                                        <strong>{{ __('torrent.completed') }}: </strong>
                                        {{ $feature->torrent->times_completed }}
                                    </span>
                                    <br>
                                    <span class="badge-user text-bold text-pink"
                                          style="background-image:url(/img/sparkels.gif);">
                                        {{ __('blocks.featured-until') }}:
                                        {{ $feature->created_at->addDay(7)->toFormattedDateString() }}
                                        ({{ $feature->created_at->addDay(7)->diffForHumans() }}!)
                                    </span>
                                    <span class="badge-user text-bold text-pink"
                                          style="background-image:url(/img/sparkels.gif);">
                                        {{ __('blocks.featured-by') }}: {{ $feature->user->username }}!
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <a class="left carousel-control">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only">{{ __('common.previous') }}</span>
                </a>
                <a class="right carousel-control">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">{{ __('common.next') }}</span>
                </a>
            </div>
        </div>
    </div>
</div>
