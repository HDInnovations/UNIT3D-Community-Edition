@php $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'),
config('api-keys.omdb')); @endphp
<div class="mobile-hide">
    <div class="col-md-10 col-sm-10 col-md-offset-1">
        <div class="clearfix visible-sm-block"></div>
        <div class="panel panel-chat shoutbox">
            <div class="panel-heading">
                <h4><i class="{{ config('other.font-awesome') }} fa-star"></i> @lang('blocks.featured-torrents')</h4>
            </div>
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="item active">
                        <div id="movie-card-list">
                            <div class="movie-card" style="background-image: url(/img/default_featured.jpg);">
                                <div class="color-overlay">
                                    <div class="movie-content">
                                        <div class="movie-header">
                                            <h1 class="movie-title">{{ config('other.title') }}
                                                - @lang('blocks.featured-torrents')</h1>
                                            <h4 class="movie-info">
                                                @lang('blocks.featured-torrents-intro')
                                                <br>
                                                <br>
                                                <span class="badge-user text-bold text-pink"
                                                    style="background-image:url(/img/sparkels.gif);">@lang('torrent.freeleech')</span>
                                                <span class="badge-user text-bold text-pink"
                                                    style="background-image:url(/img/sparkels.gif);">@lang('torrent.double-upload')</span>
                                            </h4>
                                        </div>
                                        <span class="movie-desc">

                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php $meta = null; @endphp
                    @foreach ($featured as $key => $feature)
                        @if ($feature->torrent->category->tv_meta)
                            @if ($feature->torrent->tmdb || $feature->torrent->tmdb != 0)
                                @php $meta = $client->scrape('tv', null, $feature->torrent->tmdb); @endphp
                            @else
                                @php $meta = $client->scrape('tv', 'tt'. $feature->torrent->imdb); @endphp
                            @endif
                        @endif
                        @if ($feature->torrent->category->movie_meta)
                            @if ($feature->torrent->tmdb || $feature->torrent->tmdb != 0)
                                @php $meta = $client->scrape('movie', null, $feature->torrent->tmdb); @endphp
                            @else
                                @php $meta = $client->scrape('movie', 'tt'. $feature->torrent->imdb); @endphp
                            @endif
                        @endif
                        @if ($feature->torrent->category->game_meta)
                            @php $meta = MarcReichel\IGDBLaravel\Models\Game::with(['artworks' => ['url', 'image_id'], 'genres' => ['name']])->find($feature->torrent->igdb); @endphp
                        @endif
                        <div class="item">
                            <div id="movie-card-list">
                                <div class="tags">
                                    {{ ++$key }}
                                </div>
                                <div class="movie-card" @if ($feature->torrent->category->tv_meta ||
                                        $feature->torrent->category->movie_meta)
                                        style="background-image: url({{ $meta->backdrop ?? 'https://via.placeholder.com/1400x800' }});">
                                    @elseif ($feature->torrent->category->game_meta && isset($meta) && $meta->artworks)
                                        style="background-image:
                                        url('https://images.igdb.com/igdb/image/upload/t_original/{{ $meta->artworks[0]['image_id'] }}.jpg');">
                                    @else
                                        style="background-image: url('https://via.placeholder.com/1400x800');">
                                    @endif
                                    <div class="color-overlay">
                                        <div class="movie-content">
                                            <div class="movie-header">
                                                <a href="{{ route('torrent', ['id' => $feature->torrent->id]) }}">
                                                    <h1 class="movie-title">{{ $feature->torrent->name }}</h1>
                                                </a>
                                                <h4 class="movie-info">
                                                    @if (isset($meta) && $meta->genres)
                                                        @foreach ($meta->genres as $genre)
                                                            @if ($feature->torrent->category->tv_meta ||
                                                                $feature->torrent->category->movie_meta)
                                                                | {{ $genre }} |
                                                            @endif
                                                            @if ($feature->torrent->category->game_meta)
                                                                | {{ $genre->name }} |
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </h4>
                                            </div>
                                            <span class="movie-desc">
                                                @if ($feature->torrent->category->tv_meta ||
                                                    $feature->torrent->category->movie_meta)
                                                    {{ Str::limit(strip_tags($meta->plot), 200) }}...
                                                @endif
                                                @if ($feature->torrent->category->game_meta && isset($meta) &&
                                                    $meta->summary)
                                                    {{ Str::limit(strip_tags($meta->summary), 200) }}...
                                                @endif
                                                <br>
                                                <br>
                                                <ul class="list-inline">
                                                    <span class="badge-extra text-blue"><i
                                                            class="{{ config('other.font-awesome') }} fa-database"></i>
                                                        <strong>@lang('torrent.size'): </strong>
                                                        {{ $feature->torrent->getSize() }}
                                                    </span>
                                                    <span class="badge-extra text-blue"><i
                                                            class="{{ config('other.font-awesome') }} fa-fw fa-clock"></i>
                                                        <strong>@lang('torrent.released'): </strong>
                                                        {{ $feature->torrent->created_at->diffForHumans() }}
                                                    </span>
                                                    <span class="badge-extra text-green"><i
                                                            class="{{ config('other.font-awesome') }} fa-arrow-up"></i>
                                                        <strong>@lang('torrent.seeders'): </strong>
                                                        {{ $feature->torrent->seeders }}
                                                    </span>
                                                    <span class="badge-extra text-red"><i
                                                            class="{{ config('other.font-awesome') }} fa-arrow-down"></i>
                                                        <strong>@lang('torrent.leechers'): </strong>
                                                        {{ $feature->torrent->leechers }}
                                                    </span>
                                                    <span class="badge-extra text-orange"><i
                                                            class="{{ config('other.font-awesome') }} fa-check-square"></i>
                                                        <strong>@lang('torrent.completed'): </strong>
                                                        {{ $feature->torrent->times_completed }}
                                                    </span>
                                                    <br>
                                                    <span class="badge-user text-bold text-pink"
                                                        style="background-image:url(/img/sparkels.gif);">
                                                        @lang('blocks.featured-until'):
                                                        {{ $feature->created_at->addDay(7)->toFormattedDateString() }}
                                                        ({{ $feature->created_at->addDay(7)->diffForHumans() }}!)
                                                    </span>
                                                    <span class="badge-user text-bold text-pink"
                                                        style="background-image:url(/img/sparkels.gif);">
                                                        @lang('blocks.featured-by'): {{ $feature->user->username }}!
                                                    </span>
                                                </ul>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only">@lang('common.previous')</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">@lang('common.next')</span>
                </a>
            </div>
        </div>
    </div>
</div>
