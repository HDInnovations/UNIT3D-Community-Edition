@extends('layout.default')

@section('title')
    <title>@lang('torrent.torrents') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Catalog">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('catalogs') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.catalogs')</span>
        </a>
    </li>
    <li>
        <a href="#" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.catalog')</span>
        </a>
    </li>
    <li class="active">
        <a href="#" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.torrents')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="header gradient yellow">
            <div class="inner_content">
                <h1>@lang('common.results')</h1>
            </div>
        </div>
        @if (count($torrents) == 0)
            <p>@lang('common.no-result')</p>
        @else
            <div class="torrents col-md-12">
                <div class="table-responsive">
                    <table class="table table-condensed table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Poster</th>
                            <th>Category</th>
                            <th>Name</th>
                            <th><i class="{{ config('other.font-awesome') }} fa-clock"></i></th>
                            <th><i class="{{ config('other.font-awesome') }} fa-file"></i></th>
                            <th><i class="{{ config('other.font-awesome') }} fa-arrow-circle-up"></i></th>
                            <th><i class="{{ config('other.font-awesome') }} fa-arrow-circle-down"></i></th>
                            <th><i class="{{ config('other.font-awesome') }} fa-check-square"></i></th>
                        </tr>
                        </thead>
                        <tbody id="result">
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
                            <tr>
                                <td>
                                    <div class="torrent-poster pull-left"><img src="{{ $movie->poster }}"
                                                                               data-poster-mid="{{ $movie->poster }}"
                                                                               class="img-tor-poster torrent-poster-img-small"
                                                                               alt="Poster"></div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        @if ($t->category_id == "1")
                                            <i class="{{ config('other.font-awesome') }} fa-film torrent-icon" data-toggle="tooltip"
                                               data-original-title="Movie Torrent"></i>
                                        @elseif ($t->category_id == "2")
                                            <i class="{{ config('other.font-awesome') }} fa-tv torrent-icon" data-toggle="tooltip"
                                               data-original-title="TV-Show Torrent"></i>
                                        @else
                                            <i class="{{ config('other.font-awesome') }} fa-film torrent-icon" data-toggle="tooltip"
                                               data-original-title="Movie Torrent"></i>
                                        @endif
                                        <br>
                                        <br>
                                        <span class="label label-success">{{ $t->type }}</span>
                                    </div>
                                </td>
                                <td>
                                    <a class="view-torrent" href="{{ route('torrent', ['slug' => $t->slug, 'id' => $t->id]) }}">
                                        {{ $t->name }}
                                    </a>
                                    <a href="{{ route('download', ['slug' => $t->slug, 'id' => $t->id]) }}">&nbsp;&nbsp;
                                        <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="DOWNLOAD!">
                                            <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                        </button>
                                    </a>
                                    <br>
                                    <strong>
                                        @if ($t->anon == 1)
                                            <span class="badge-extra text-bold">
              <i class="{{ config('other.font-awesome') }} fa-upload"></i> By ANONYMOUS USER
                                                @if ($user->id == $t->user->id || $user->group->is_modo)
                                                    <a href="{{ route('profile', ['username' => $t->user->username, 'id' => $t->user->id]) }}">({{ $t->user->username }}
                                                        )</a>
              </span>
                                        @endif
                                        @else
                                            <span class="badge-extra text-bold">
              <i class="{{ config('other.font-awesome') }} fa-upload"></i> By
              <a href="{{ route('profile', ['username' => $t->user->username, 'id' => $t->user->id]) }}">{{ $t->user->username }}</a>
              </span>
                                        @endif

                                        @if ($user->ratings == 1)
                                            <a href="https://www.imdb.com/title/tt{{ $t->imdb }}">
              <span class="badge-extra text-bold">
                <span class="text-gold movie-rating-stars">
                  <i class="{{ config('other.font-awesome') }} fa-star" data-toggle="tooltip" data-original-title="View More"></i>
                </span>
                  {{ $movie->imdbRating }}/10 ({{ $movie->imdbVotes }} votes)
              </span>
                                            </a>
                                        @else
                                            @if ($t->category_id == '2')
                                                <a href="https://www.themoviedb.org/tv/34307">
              <span class="badge-extra text-bold">
                <span class="text-gold movie-rating-stars">
                  <i class="{{ config('other.font-awesome') }} fa-star" data-toggle="tooltip" data-original-title="View More"></i>
                </span>
                  {{ $movie->tmdbRating }}/10 ({{ $movie->tmdbVotes }} votes)
              </span>
                                                </a>
                                            @else
                                                <a href="https://www.themoviedb.org/movie/34307">
              <span class="badge-extra text-bold">
                <span class="text-gold movie-rating-stars">
                  <i class="{{ config('other.font-awesome') }} fa-star" data-toggle="tooltip" data-original-title="View More"></i>
                </span>
                  {{ $movie->tmdbRating }}/10 ({{ $movie->tmdbVotes }} votes)
              </span>
                                                </a>
                                            @endif
                                        @endif
                                        <span class="badge-extra text-bold text-pink"><i class="{{ config('other.font-awesome') }} fa-heart"
                                                                                         data-toggle="tooltip"
                                                                                         data-original-title="Thanks Given"></i> {{ $t->thanks()->count() }}</span>
                                        @if ($t->stream == "1")<span class="badge-extra text-bold"><i
                                                    class="{{ config('other.font-awesome') }} fa-play text-red" data-toggle="tooltip"
                                                    data-original-title="Stream Optimized"></i> Stream Optimized</span> @endif
                                        @if ($t->doubleup == "1")<span class="badge-extra text-bold"><i
                                                    class="{{ config('other.font-awesome') }} fa-gem text-green" data-toggle="tooltip"
                                                    data-original-title="Double upload"></i> Double Upload</span> @endif
                                        @if ($t->free == "1")<span class="badge-extra text-bold"><i
                                                    class="{{ config('other.font-awesome') }} fa-star text-gold" data-toggle="tooltip"
                                                    data-original-title="100% Free"></i> 100% Free</span> @endif
                                        @if (config('other.freeleech') == true)<span class="badge-extra text-bold"><i
                                                    class="{{ config('other.font-awesome') }} fa-globe text-blue" data-toggle="tooltip"
                                                    data-original-title="Global FreeLeech"></i> Global FreeLeech</span> @endif
                                        @if (config('other.doubleup') == true)<span class="badge-extra text-bold"><i
                                                    class="{{ config('other.font-awesome') }} fa-globe text-green" data-toggle="tooltip"
                                                    data-original-title="Double Upload"></i> Global Double Upload</span> @endif
                                        @if ($t->leechers >= "5") <span class="badge-extra text-bold"><i
                                                    class="{{ config('other.font-awesome') }} fa-fire text-orange" data-toggle="tooltip"
                                                    data-original-title="Hot!"></i> Hot!</span> @endif
                                        @if ($t->sticky == 1) <span class="badge-extra text-bold"><i
                                                    class="{{ config('other.font-awesome') }} fa-thumbtack text-black" data-toggle="tooltip"
                                                    data-original-title="Sticky!"></i> Sticky!</span> @endif
                                        @if ($user->updated_at->getTimestamp() < $t->created_at->getTimestamp()) <span
                                                class="badge-extra text-bold"><i class="{{ config('other.font-awesome') }} fa-magic text-black"
                                                                                 data-toggle="tooltip"
                                                                                 data-original-title="NEW!"></i> NEW!</span> @endif
                                        @if ($t->highspeed == 1)<span class="badge-extra text-bold"><i
                                                    class="{{ config('other.font-awesome') }} fa-tachometer text-red" data-toggle="tooltip"
                                                    data-original-title="High Speeds!"></i> High Speeds!</span> @endif
                                    </strong>
                                </td>

                                <td>
                                    <time datetime="{{ date('Y-m-d H:m:s', strtotime($t->created_at)) }}">{{$t->created_at->diffForHumans()}}</time>
                                </td>
                                <td><span class="badge-extra text-blue text-bold">{{ $t->getSize() }}</span></td>
                                <td><span class="badge-extra text-green text-bold">{{ $t->seeders }}</span></td>
                                <td><span class="badge-extra text-red text-bold">{{ $t->leechers }}</span></td>
                                <td>
                                    <span class="badge-extra text-orange text-bold">{{ $t->times_completed }} @lang('common.times')</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection
