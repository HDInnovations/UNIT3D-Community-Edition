@extends('layout.default')

@section('title')
    <title>{{ trans('torrent.torrents') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ trans('torrent.torrents') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('torrents') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.torrents') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('grouping_categories') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Grouping Categories</span>
        </a>
    </li>
    <li>
        <a href="#" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $category->name }} Grouping</span>
        </a>
    </li>
    <li>
        <a href="#" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $category->name }} Grouping Results</span>
        </a>
    </li>
@endsection

@section('content')
    @php $client = new \App\Services\MovieScrapper(config('api-keys.tmdb') , config('api-keys.tvdb') , config('api-keys.omdb')) @endphp
    @if($category->id == 2)
        @php $movie = $client->scrape('tv', 'tt'.$imdb); @endphp
    @else
        @php $movie = $client->scrape('movie', 'tt'.$imdb); @endphp
    @endif
    <div class="container box">
        <div class="header gradient light_blue">
            <div class="inner_content">
                <h1>{{ $movie->title }} ({{ $movie->releaseYear }})</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 movie-list">
                <div class="pull-left">
                    <a href="#">
                        <img src="{{ $movie->poster }}" style="height:200px; margin-right:10px;"
                             alt="{{ $movie->title }} Poster">
                    </a>
                </div>
                <h2 class="movie-title text-bold">
                    {{ $movie->title }} ({{ $movie->releaseYear }})
                    <span class="badge-user text-bold text-gold">Rating:
          <span class="movie-rating-stars">
            <i class="fa fa-star"></i>
          </span>
                        @if($user->ratings == 1)
                            {{ $movie->imdbRating }}/10 ({{ $movie->imdbVotes }} votes)
                        @else
                            {{ $movie->tmdbRating }}/10 ({{ $movie->tmdbVotes }} votes)
                        @endif
       </span>
                </h2>
                <div class="movie-details">
                    <p class="movie-plot">{{ $movie->plot }}</p>
                    <strong>ID:</strong>
                    <span class="badge-user"><a rel="nofollow"
                                                href="http://www.imdb.com/title/{{ $movie->imdb }}">{{ $movie->imdb }}</a></span>
                    <span class="badge-user"><a rel="nofollow"
                                                href="https://www.themoviedb.org/{{ strtolower($category->name) }}/{{ $movie->tmdb }}">{{ $movie->tmdb }}</a></span>
                    <strong>Genre: </strong>
                    @if($movie->genres)
                        @foreach($movie->genres as $genre)
                            <span class="badge-user text-bold text-green">{{ $genre }}</span>
                        @endforeach
                    @endif
                </div>
                <br>
                <ul class="list-inline">
                    <li><i class="fa fa-files-o"></i> <strong>Torrents: </strong> {{ $torrents->count() }}</li>
                    <li>
                        <a href="{{ route('upload_form', ['title' => $movie->title, 'imdb' => $movie->imdb, 'tmdb' => $movie->tmdb]) }}"
                           class="btn btn-xs btn-danger">
                            Upload {{ $movie->title }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="torrents col-md-12">
            <div class="table-responsive">
                <table class="table table-condensed table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Poster</th>
                        <th>Category</th>
                        <th>Name</th>
                        <th><i class="fa fa-clock-o"></i></th>
                        <th><i class="fa fa-file"></i></th>
                        <th><i class="fa fa-check-square-o"></i></th>
                        <th><i class="fa fa-arrow-circle-up"></i></th>
                        <th><i class="fa fa-arrow-circle-down"></i></th>
                    </tr>
                    </thead>
                    <tbody id="result">
                    @foreach($torrents as $k => $t)
                        <tr>
                            <td>
                                <div class="torrent-poster pull-left"><img src="{{ $movie->poster }}"
                                                                           data-poster-mid="{{ $movie->poster }}"
                                                                           class="img-tor-poster torrent-poster-img-small"
                                                                           alt="Poster"></div>
                            </td>
                            <td>
                                <div class="text-center">
                                    <i class="{{ $t->category->icon }}" data-toggle="tooltip" title=""
                                       data-original-title="{{ $t->category->name }} Torrent"></i>
                                    <br>
                                    <br>
                                    <span class="label label-success">{{ $t->type }}</span>
                                </div>
                            </td>
                            <td>
                                <a class="view-torrent" data-id="{{ $t->id }}" data-slug="{{ $t->slug }}"
                                   href="{{ route('torrent', ['slug' => $t->slug, 'id' => $t->id]) }}"
                                   data-toggle="tooltip" title=""
                                   data-original-title="{{ $t->name }}">{{ $t->name }}</a>
                                <a href="{{ route('download', ['slug' => $t->slug, 'id' => $t->id]) }}">&nbsp;&nbsp;
                                    <button class="btn btn-primary btn-circle" type="button"
                                            data-toggle="tooltip" title="" data-original-title="DOWNLOAD!"><i
                                                class="livicon" data-name="download" data-size="18"
                                                data-color="white" data-hc="white" data-l="true"></i></button>
                                </a>
                                <br>
                                <strong>
                                    @if ($t->anon == 1)
                                        <span class="badge-extra text-bold">
                <i class="fa fa-upload"></i> By ANONYMOUS USER
                                            @if ($user->id == $t->user->id || $user->group->is_modo)
                                                <a href="{{ route('profile', ['username' => $t->user->username, 'id' => $t->user->id]) }}">({{ $t->user->username }}
                                                    )</a>
                </span>
                                    @endif
                                    @else
                                        <span class="badge-extra text-bold">
                <i class="fa fa-upload"></i> By
                <a href="{{ route('profile', ['username' => $t->user->username, 'id' => $t->user->id]) }}">{{ $t->user->username }}</a>
                </span>
                                    @endif

                                    @if ($user->ratings == 1)
                                        <a rel="nofollow" href="http://www.imdb.com/title/tt{{ $t->imdb }}">
                <span class="badge-extra text-bold">
                  <span class="text-gold movie-rating-stars">
                    <i class="fa fa-star" data-toggle="tooltip" title="" data-original-title="View More"></i>
                  </span>
                    {{ $movie->imdbRating }}/10 ({{ $movie->imdbVotes }} votes)
                </span>
                                        </a>
                                    @else
                                        @if ($t->category_id == '2')
                                            <a rel="nofollow" href="https://www.themoviedb.org/tv/34307">
                <span class="badge-extra text-bold">
                  <span class="text-gold movie-rating-stars">
                    <i class="fa fa-star" data-toggle="tooltip" title="" data-original-title="View More"></i>
                  </span>
                    {{ $movie->tmdbRating }}/10 ({{ $movie->tmdbVotes }} votes)
                </span>
                                            </a>
                                        @else
                                            <a rel="nofollow" href="https://www.themoviedb.org/movie/34307">
                <span class="badge-extra text-bold">
                  <span class="text-gold movie-rating-stars">
                    <i class="fa fa-star" data-toggle="tooltip" title="" data-original-title="View More"></i>
                  </span>
                    {{ $movie->tmdbRating }}/10 ({{ $movie->tmdbVotes }} votes)
                </span>
                                            </a>
                                        @endif
                                    @endif
                                    <span class="badge-extra text-bold text-pink"><i class="fa fa-heart"
                                                                                     data-toggle="tooltip"
                                                                                     title=""
                                                                                     data-original-title="Thanks Given"></i> {{ $t->thanks()->count() }}</span>
                                    @if($t->stream == "1")<span class="badge-extra text-bold"><i
                                                class="fa fa-play text-red" data-toggle="tooltip" title=""
                                                data-original-title="Stream Optimized"></i> Stream Optimized</span> @endif
                                    @if($t->doubleup == "1")<span class="badge-extra text-bold"><i
                                                class="fa fa-diamond text-green" data-toggle="tooltip" title=""
                                                data-original-title="Double upload"></i> Double Upload</span> @endif
                                    @if($t->free == "1")<span class="badge-extra text-bold"><i
                                                class="fa fa-star text-gold" data-toggle="tooltip" title=""
                                                data-original-title="100% Free"></i> 100% Free</span> @endif
                                    @if(config('other.freeleech') == true)<span class="badge-extra text-bold"><i
                                                class="fa fa-globe text-blue" data-toggle="tooltip" title=""
                                                data-original-title="Global FreeLeech"></i> Global FreeLeech</span> @endif
                                    @if(config('other.doubleup') == true)<span class="badge-extra text-bold"><i
                                                class="fa fa-globe text-green" data-toggle="tooltip" title=""
                                                data-original-title="Double Upload"></i> Global Double Upload</span> @endif
                                    @if($t->leechers >= "5") <span class="badge-extra text-bold"><i
                                                class="fa fa-fire text-orange" data-toggle="tooltip" title=""
                                                data-original-title="Hot!"></i> Hot!</span> @endif
                                    @if($t->sticky == 1) <span class="badge-extra text-bold"><i
                                                class="fa fa-thumb-tack text-black" data-toggle="tooltip"
                                                title=""
                                                data-original-title="Sticky!"></i> Sticky!</span> @endif
                                    @if($user->updated_at->getTimestamp() < $t->created_at->getTimestamp())
                                        <span class="badge-extra text-bold"><i class="fa fa-magic text-black"
                                                                               data-toggle="tooltip" title=""
                                                                               data-original-title="NEW!"></i> NEW!</span> @endif
                                    @if($t->highspeed == 1)<span class="badge-extra text-bold"><i
                                                class="fa fa-tachometer text-red" data-toggle="tooltip" title=""
                                                data-original-title="High Speeds!"></i> High Speeds!</span> @endif
                                </strong>
                            </td>

                            <td>
                                <time datetime="{{ date('Y-m-d H:m:s', strtotime($t->created_at)) }}">{{$t->created_at->diffForHumans()}}</time>
                            </td>
                            <td><span class="badge-extra text-blue text-bold">{{ $t->getSize() }}</span></td>
                            <td>
                                <span class="badge-extra text-orange text-bold">{{ $t->times_completed }} {{ trans('common.times') }}</span>
                            </td>
                            <td><span class="badge-extra text-green text-bold">{{ $t->seeders }}</span></td>
                            <td><span class="badge-extra text-red text-bold">{{ $t->leechers }}</span></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
