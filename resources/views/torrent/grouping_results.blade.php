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
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.grouping-categories') }}</span>
        </a>
    </li>
    <li>
        <a href="#" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $category->name }} {{ trans('torrent.grouping') }}</span>
        </a>
    </li>
    <li>
        <a href="#" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $category->name }} {{ trans('torrent.grouping-results') }}</span>
        </a>
    </li>
@endsection

@section('content')
    @php $client = new \App\Services\MovieScrapper(config('api-keys.tmdb') , config('api-keys.tvdb') , config('api-keys.omdb')) @endphp
    @if ($category->id == 2)
        @php $movie = $client->scrape('tv', 'tt'.$imdb); @endphp
    @else
        @php $movie = $client->scrape('movie', 'tt'.$imdb); @endphp
    @endif
    <div class="container-fluid">
        <div class="block">
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
                             alt="{{ $movie->title }} {{ trans('torrent.poster') }}">
                    </a>
                </div>
                <h2 class="movie-title text-bold">
                    {{ $movie->title }} ({{ $movie->releaseYear }})
                    <span class="badge-user text-bold text-gold">{{ trans('torrent.rating') }}:
          <span class="movie-rating-stars">
            <i class="{{ config('other.font-awesome') }} fa-star"></i>
          </span>
                        @if ($user->ratings == 1)
                            {{ $movie->imdbRating }}/10 ({{ $movie->imdbVotes }} {{ trans('torrent.votes') }})
                        @else
                            {{ $movie->tmdbRating }}/10 ({{ $movie->tmdbVotes }} {{ trans('torrent.votes') }})
                        @endif
       </span>
                </h2>
                <div class="movie-details">
                    <p class="movie-plot">{{ $movie->plot }}</p>
                    <strong>ID:</strong>
                    <span class="badge-user"><a rel="nofollow"
                                                href="http://www.imdb.com/title/{{ $movie->imdb }}">{{ $movie->imdb }}</a></span>
                    @if ($torrent->category_id == "2" && $torrent->tmdb != 0 && $torrent->tmdb != null)
                    <span class="badge-user"><a rel="nofollow"
                                                href="https://anon.to?https://www.themoviedb.org/tv/{{ $movie->tmdb }}">{{ $movie->tmdb }}</a></span>
                    @elseif ($torrent->tmdb != 0 && $torrent->tmdb != null)
                    <span class="badge-user"><a rel="nofollow"
                                                href="https://anon.to?https://www.themoviedb.org/movie/{{ $movie->tmdb }}">{{ $movie->tmdb }}</a></span>
                    @endif
                    <strong>{{ trans('torrent.genre') }}: </strong>
                    @if ($movie->genres)
                        @foreach ($movie->genres as $genre)
                            <span class="badge-user text-bold text-green">{{ $genre }}</span>
                        @endforeach
                    @endif
                </div>
                <br>
                <ul class="list-inline">
                    <li><i class="{{ config('other.font-awesome') }} fa-files"></i> <strong>{{ trans('torrent.torrents') }}: </strong> {{ $torrents->count() }}</li>
                    <li>
                        <a href="{{ route('upload_form', ['title' => $movie->title, 'imdb' => $movie->imdb, 'tmdb' => $movie->tmdb]) }}"
                           class="btn btn-xs btn-danger">
                            {{ trans('common.upload') }} {{ $movie->title }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
            <div class="table-responsive">
                <table class="table table-condensed table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th>{{ trans('torrent.category') }}</th>
                        <th>{{ trans('torrent.name') }}</th>
                        <th><i class="{{ config('other.font-awesome') }} fa-clock"></i></th>
                        <th><i class="{{ config('other.font-awesome') }} fa-file"></i></th>
                        <th><i class="{{ config('other.font-awesome') }} fa-check-square"></i></th>
                        <th><i class="{{ config('other.font-awesome') }} fa-arrow-circle-up"></i></th>
                        <th><i class="{{ config('other.font-awesome') }} fa-arrow-circle-down"></i></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($torrents as $torrent)
                        @if ($torrent->sticky == 1)
                            <tr class="success">
                        @else
                            <tr>
                                @endif
                                <td>
                                    <a href="{{ route('category', ['slug' => $torrent->category->slug, 'id' => $torrent->category->id]) }}">
                                        <div class="text-center">
                                            <i class="{{ $torrent->category->icon }} torrent-icon" data-toggle="tooltip"
                                               data-original-title="{{ $torrent->category->name }} {{ strtolower(trans('torrent.torrent')) }}"
                                               style="padding-bottom: 6px;"></i>
                                        </div>
                                    </a>
                                    <div class="text-center">
                            <span class="label label-success" data-toggle="tooltip" data-original-title="{{ trans('torrent.type') }}">
                                {{ $torrent->type }}
                            </span>
                                    </div>
                                </td>

                                <td>
                                    <a class="view-torrent" href="{{ route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
                                        {{ $torrent->name }}
                                    </a>
                                    @if (config('torrent.download_check_page') == 1)
                                        <a href="{{ route('download_check', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
                                            <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                                    data-original-title="{{ trans('common.download') }}">
                                                <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                            </button>
                                        </a>
                                    @else
                                        <a href="{{ route('download', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
                                            <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                                    data-original-title="{{ trans('common.download') }}">
                                                <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                            </button>
                                        </a>
                                    @endif

                                    {{--<smallbookmark :id="{{ $torrent->id }}" :state="{{ $torrent->bookmarked()  ? 1 : 0}}"></smallbookmark>--}}

                                    @php $history = \App\History::where('user_id', '=', $user->id)->where('info_hash', '=', $torrent->info_hash)->first(); @endphp
                                    @if ($history)
                                        @if ($history->seeder == 1 && $history->active == 1)
                                            <button class="btn btn-success btn-circle" type="button" data-toggle="tooltip"
                                                    data-original-title="{{ trans('torrent.currently-seeding') }}!">
                                                <i class="{{ config('other.font-awesome') }} fa-arrow-up"></i>
                                            </button>
                                        @endif

                                        @if ($history->seeder == 0 && $history->active == 1)
                                            <button class="btn btn-warning btn-circle" type="button" data-toggle="tooltip"
                                                    data-original-title="{{ trans('torrent.currently-leeching') }}!">
                                                <i class="{{ config('other.font-awesome') }} fa-arrow-down"></i>
                                            </button>
                                        @endif

                                        @if ($history->seeder == 0 && $history->active == 0 && $history->completed_at == null)
                                            <button class="btn btn-info btn-circle" type="button" data-toggle="tooltip"
                                                    data-original-title="{{ trans('torrent.not-completed') }}!">
                                                <i class="{{ config('other.font-awesome') }} fa-hand-paper"></i>
                                            </button>
                                        @endif

                                        @if ($history->seeder == 0 && $history->active == 0 && $history->completed_at != null)
                                            <button class="btn btn-danger btn-circle" type="button" data-toggle="tooltip"
                                                    data-original-title="{{ trans('torrent.completed-not-seeding') }}!">
                                                <i class="{{ config('other.font-awesome') }} fa-thumbs-down"></i>
                                            </button>
                                        @endif
                                    @endif

                                    <br>
                                    @if ($torrent->anon == 1)
                                        <span class="badge-extra text-bold">
                                <i class="{{ config('other.font-awesome') }} fa-upload" data-toggle="tooltip" data-original-title="{{ trans('torrent.uploader') }}"></i> {{ trans('common.anonymous') }}
                                            @if ($user->id == $torrent->user->id || $user->group->is_modo)
                                                <a href="{{ route('profile', ['username' => $torrent->user->username, 'id' => $torrent->user->id]) }}">
                                        ({{ $torrent->user->username }})
                                    </a>
                                            @endif
                            </span>
                                    @else
                                        <span class="badge-extra text-bold">
                                <i class="{{ config('other.font-awesome') }} fa-upload" data-toggle="tooltip" data-original-title="{{ trans('torrent.uploader') }}"></i> 
                                    <a href="{{ route('profile', ['username' => $torrent->user->username, 'id' => $torrent->user->id]) }}">
                                        {{ $torrent->user->username }}
                                    </a>
                            </span>
                                    @endif

                                    @if ($torrent->category->meta == 1)
                                        @if ($user->ratings == 1)
                                            <a rel="nofollow" href="https://anon.to?http://www.imdb.com/title/tt{{ $torrent->imdb }}">
                                <span class="badge-extra text-bold">
                                    <span class="text-gold movie-rating-stars">
                                        <i class="{{ config('other.font-awesome') }} fa-star" data-toggle="tooltip"
                                           data-original-title="{{ trans('torrent.view-more') }}"></i>
                                    </span>
                                    {{ $movie->imdbRating }}/10 ({{ $movie->imdbVotes }} {{ trans('torrent.votes') }})
                                </span>
                                            </a>
                                        @else
                                            @if ($torrent->category_id == 2)
                                                <a rel="nofollow" href="https://www.themoviedb.org/tv/{{ $movie->tmdb }}">
                                                    @else
                                                        <a rel="nofollow" href="https://www.themoviedb.org/movie/{{ $movie->tmdb }}">
                                                            @endif
                                                            <span class="badge-extra text-bold">
                                <span class="text-gold movie-rating-stars">
                                    <i class="{{ config('other.font-awesome') }} fa-star" data-toggle="tooltip"
                                       data-original-title="{{ trans('torrent.view-more') }}"></i>
                                </span>
                                                                {{ $movie->tmdbRating }}/10 ({{ $movie->tmdbVotes }} {{ trans('torrent.votes') }})
                            </span>
                                                        </a>
                                                    @endif
                                                    @endif

                                                    <span class="badge-extra text-bold text-pink">
                            <i class="{{ config('other.font-awesome') }} fa-heart" data-toggle="tooltip" data-original-title="{{ trans('torrent.thanks-given') }}"></i>
                                                        {{ $torrent->thanks_count }}
                        </span>

                                                    <span class="badge-extra text-bold text-green">
                            <i class="{{ config('other.font-awesome') }} fa-comment" data-toggle="tooltip" data-original-title="{{ trans('common.comments') }}"></i>
                                                        {{ $torrent->comments_count }}
                        </span>

                                                    @if ($torrent->internal == 1)
                                                        <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-magic' data-toggle='tooltip' title=''
                                   data-original-title='{{ trans('torrent.internal-release') }}' style="color: #BAAF92"></i> {{ trans('torrent.internal') }}
                            </span>
                                                    @endif

                                                    @if ($torrent->stream == 1)
                                                        <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-play text-red' data-toggle='tooltip' title=''
                                   data-original-title='{{ trans('torrent.stream-optimized') }}'></i> {{ trans('torrent.stream-optimized') }}
                            </span>
                                                    @endif

                                                    @if ($torrent->featured == 0)
                                                        @if ($torrent->doubleup == 1)
                                                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-gem text-green' data-toggle='tooltip' title=''
                                   data-original-title='{{ trans('torrent.double-upload') }}'></i> {{ trans('torrent.double-upload') }}
                            </span>
                                                        @endif
                                                        @if ($torrent->free == 1)
                                                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-star text-gold' data-toggle='tooltip' title=''
                                   data-original-title='{{ trans('torrent.freeleech') }}'></i> {{ trans('torrent.freeleech') }}
                            </span>
                                                        @endif
                                                    @endif

                                                    @if ($personal_freeleech)
                                                        <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-id-badge text-orange' data-toggle='tooltip' title=''
                                   data-original-title='{{ trans('torrent.personal-freeleech') }}'></i> {{ trans('torrent.personal-freeleech') }}
                            </span>
                                                    @endif

                                                    @php $freeleech_token = \App\FreeleechToken::where('user_id', '=', $user->id)->where('torrent_id', '=', $torrent->id)->first(); @endphp
                                                    @if ($freeleech_token)
                                                        <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-coins text-bold' data-toggle='tooltip' title=''
                                   data-original-title='{{ trans('torrent.freeleech-token') }}'></i> {{ trans('torrent.freeleech-token') }}
                            </span>
                                                    @endif

                                                    @if ($torrent->featured == 1)
                                                        <span class='badge-extra text-bold' style='background-image:url(https://i.imgur.com/F0UCb7A.gif);'>
                                <i class='{{ config("other.font-awesome") }} fa-certificate text-pink' data-toggle='tooltip' title=''
                                   data-original-title='{{ trans('torrent.featured') }}'></i> {{ trans('torrent.featured') }}
                            </span>
                                                    @endif

                                                    @if ($user->group->is_freeleech == 1)
                                                        <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-trophy text-purple' data-toggle='tooltip' title=''
                                   data-original-title='{{ trans('torrent.special-freelech') }}'></i> {{ trans('torrent.special-freeleech') }}
                            </span>
                                                    @endif

                                                    @if (config('other.freeleech') == 1)
                                                        <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-globe text-blue' data-toggle='tooltip' title=''
                                   data-original-title='{{ trans('torrent.global-freelech') }}'></i> {{ trans('torrent.global-freelech') }}
                            </span>
                                                    @endif

                                                    @if (config('other.doubleup') == 1)
                                                        <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-globe text-green' data-toggle='tooltip' title=''
                                   data-original-title='{{ trans('torrent.double-upload') }}'></i> {{ trans('torrent.double-upload') }}
                            </span>
                                                    @endif

                                                    @if ($torrent->leechers >= 5)
                                                        <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-fire text-orange' data-toggle='tooltip' title=''
                                   data-original-title='{{ trans('common.hot') }}!'></i> {{ trans('common.hot') }}!
                            </span>
                                                    @endif

                                                    @if ($torrent->sticky == 1)
                                                        <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-thumbtack text-black' data-toggle='tooltip' title=''
                                   data-original-title='{{ trans('torrent.sticky') }}!'></i> {{ trans('torrent.sticky') }}
                            </span>
                                                    @endif

                                                    @if ($user->updated_at->getTimestamp() < $torrent->created_at->getTimestamp())
                                                        <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-magic text-black' data-toggle='tooltip' title=''
                                   data-original-title='{{ trans('common.new') }}!'></i> {{ trans('common.new') }}
                            </span>
                                                    @endif

                                                    @if ($torrent->highspeed == 1)
                                                        <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-tachometer text-red' data-toggle='tooltip' title=''
                                   data-original-title='{{ trans('common.high-speeds') }}'></i> {{ trans('common.high-speeds') }}
                            </span>
                                                    @endif

                                                    @if ($torrent->sd == 1)
                                                        <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-ticket text-orange' data-toggle='tooltip' title=''
                                   data-original-title='{{ trans('torrent.sd-content') }}!'></i> {{ trans('torrent.sd-content') }}
                            </span>
                                            @endif
                                </td>

                                <td>
                                    <time>{{ $torrent->created_at->diffForHumans() }}</time>
                                </td>
                                <td>
                                    <span class='badge-extra text-blue text-bold'>{{ $torrent->getSize() }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('history', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
                            <span class='badge-extra text-orange text-bold'>
                                {{ $torrent->times_completed }} {{ trans('common.times') }}
                            </span>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('peers', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
                            <span class='badge-extra text-green text-bold'>
                                {{ $torrent->seeders }}
                            </span>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('peers', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
                            <span class='badge-extra text-red text-bold'>
                                {{ $torrent->leechers }}
                            </span>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
