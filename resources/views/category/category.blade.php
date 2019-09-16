@extends('layout.default')

@section('title')
    <title>{{ $category->name }} @lang('torrent.category') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ $category->name }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('categories') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.categories')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('category', ['slug' => $category->slug, 'id' => $category->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $category->name }}</span>
        </a>
    </li>
@endsection

@section('content')
    @php $client = new \App\Services\MovieScrapper(config('api-keys.tmdb') , config('api-keys.tvdb') , config('api-keys.omdb')) @endphp
    <div class="container-fluid">
        <div class="block">
        <div class="header gradient green">
            <div class="inner_content">
                <h1>@lang('torrent.torrents') in {{ $category->name }}</h1>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-condensed table-bordered table-striped table-hover">
                <thead>
                <tr>
                    @if ($user->show_poster == 1)
                        <th>Poster</th>
                    @else
                        <th></th>
                    @endif
                    <th>Category/Type</th>
                    <th>@lang('common.name')</th>
                    <th><i class="{{ config('other.font-awesome') }} fa-clock"></i></th>
                    <th><i class="{{ config('other.font-awesome') }} fa-file"></i></th>
                    <th><i class="{{ config('other.font-awesome') }} fa-arrow-circle-up"></i></th>
                    <th><i class="{{ config('other.font-awesome') }} fa-arrow-circle-down"></i></th>
                    <th><i class="{{ config('other.font-awesome') }} fa-check-square"></i></th>
                </tr>
                </thead>

                <tbody>
                @foreach ($torrents as $torrent)
                    @if ($torrent->category->tv_meta)
                        @if ($torrent->tmdb || $torrent->tmdb != 0)
                            @php $meta = $client->scrape('tv', null, $torrent->tmdb); @endphp
                        @else
                            @php $meta = $client->scrape('tv', 'tt'. $torrent->imdb); @endphp
                        @endif
                    @else
                        @if ($torrent->tmdb || $torrent->tmdb != 0)
                            @php $meta = $client->scrape('movie', null, $torrent->tmdb); @endphp
                        @else
                            @php $meta = $client->scrape('movie', 'tt'. $torrent->imdb); @endphp
                        @endif
                    @endif

                    @if ($torrent->sticky == 1)
                        <tr class="success">
                    @else
                        <tr>
                            @endif
                            <td>
                                @if ($user->show_poster == 1)
                                    <div class="torrent-poster pull-left">
                                        <img src="{{ $meta->poster ?? 'https://via.placeholder.com/600x900'}}"
                                             data-poster-mid="{{ $meta->poster ?? 'https://via.placeholder.com/600x900'}}"
                                             class="img-tor-poster torrent-poster-img-small" alt="Poster">
                                    </div>
                                @else
                                    <div class="torrent-poster pull-left"></div>
                                @endif
                            </td>

                            <td>
                                @if ($torrent->category->image != null)
                                    <a href="{{ route('category', ['slug' => $torrent->category->slug, 'id' => $torrent->category->id]) }}">
                                        <div class="text-center">
                                            <img src="{{ url('files/img/' . $torrent->category->image) }}" alt="{{ $torrent->category->name }}" data-toggle="tooltip"
                                                 data-original-title="{{ $torrent->category->name }} {{ strtolower(trans('torrent.torrent')) }}"
                                                 style="padding-bottom: 6px;">
                                        </div>
                                    </a>
                                @else
                                    <a href="{{ route('category', ['slug' => $torrent->category->slug, 'id' => $torrent->category->id]) }}">
                                        <div class="text-center">
                                            <i class="{{ $torrent->category->icon }} torrent-icon" data-toggle="tooltip"
                                               data-original-title="{{ $torrent->category->name }} {{ strtolower(trans('torrent.torrent')) }}"
                                               style="padding-bottom: 6px;"></i>
                                        </div>
                                    </a>
                                @endif
                                <div class="text-center">
                            <span class="label label-success" data-toggle="tooltip" data-original-title="Type">
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
                                                data-original-title="Download Torrent">
                                            <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                        </button>
                                    </a>
                                @else
                                    <a href="{{ route('download', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
                                        <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="Download Torrent">
                                            <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                        </button>
                                    </a>
                                @endif

                                {{--<smallbookmark :id="{{ $torrent->id }}" :state="{{ $torrent->bookmarked()  ? 1 : 0}}"></smallbookmark>--}}

                                @php $history = \App\Models\History::where('user_id', '=', $user->id)->where('info_hash', '=', $torrent->info_hash)->first(); @endphp
                                @if ($history)
                                    @if ($history->seeder == 1 && $history->active == 1)
                                        <button class="btn btn-success btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="Currently Seeding!">
                                            <i class="{{ config('other.font-awesome') }} fa-arrow-up"></i>
                                        </button>
                                    @endif

                                    @if ($history->seeder == 0 && $history->active == 1)
                                        <button class="btn btn-warning btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="Currently Leeching!">
                                            <i class="{{ config('other.font-awesome') }} fa-arrow-down"></i>
                                        </button>
                                    @endif

                                    @if ($history->seeder == 0 && $history->active == 0 && $history->completed_at == null)
                                        <button class="btn btn-info btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="Started Downloading But Never Completed!">
                                            <i class="{{ config('other.font-awesome') }} fa-hand-paper"></i>
                                        </button>
                                    @endif

                                    @if ($history->seeder == 0 && $history->active == 0 && $history->completed_at != null)
                                        <button class="btn btn-danger btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="You Completed This Download But Are No Longer Seeding It!">
                                            <i class="{{ config('other.font-awesome') }} fa-thumbs-down"></i>
                                        </button>
                                    @endif
                                @endif

                                <br>
                                @if ($torrent->anon == 1)
                                    <span class="badge-extra text-bold">
                                <i class="{{ config('other.font-awesome') }} fa-upload" data-toggle="tooltip" data-original-title="Uploaded By"></i> By ANONYMOUS USER
                                        @if ($user->id == $torrent->user->id || $user->group->is_modo)
                                            <a href="{{ route('profile', ['username' => $torrent->user->username, 'id' => $torrent->user->id]) }}">
                                        ({{ $torrent->user->username }})
                                    </a>
                                        @endif
                            </span>
                                @else
                                    <span class="badge-extra text-bold">
                                <i class="{{ config('other.font-awesome') }} fa-upload" data-toggle="tooltip" data-original-title="Uploaded By"></i> By
                                    <a href="{{ route('profile', ['username' => $torrent->user->username, 'id' => $torrent->user->id]) }}">
                                        {{ $torrent->user->username }}
                                    </a>
                            </span>
                                @endif

                                @if (! $torrent->category->no_meta)
                                    @if ($user->ratings == 1)
                                        <a href="https://www.imdb.com/title/tt{{ $torrent->imdb }}">
                                <span class="badge-extra text-bold">
                                    <span class="text-gold movie-rating-stars">
                                        <i class="{{ config('other.font-awesome') }} fa-thumbs-up" data-toggle="tooltip"
                                           data-original-title="View More"></i>
                                    </span>
                                    {{ $meta->imdbRating }}/10 ({{ $meta->imdbVotes }} votes)
                                </span>
                                        </a>
                                    @else
                                        @if ($torrent->category->tv_meta)
                                            <a href="https://www.themoviedb.org/tv/{{ $meta->tmdb }}">
                                                @elseif ($torrent->category->movie_meta)
                                                    <a href="https://www.themoviedb.org/movie/{{ $meta->tmdb }}">
                                                        @endif
                                                        <span class="badge-extra text-bold">
                                <span class="text-gold movie-rating-stars">
                                    <i class="{{ config('other.font-awesome') }} fa-thumbs-up" data-toggle="tooltip"
                                       data-original-title="View More"></i>
                                </span>
                                                            {{ $meta->tmdbRating }}/10 ({{ $meta->tmdbVotes }} votes)
                            </span>
                                                    </a>
                                                @endif
                                                @endif

                                                <span class="badge-extra text-bold text-pink">
                            <i class="{{ config('other.font-awesome') }} fa-heart" data-toggle="tooltip" data-original-title="Thanks Given"></i>
                                                    {{ $torrent->thanks_count }}
                        </span>

                                                <span class="badge-extra text-bold text-green">
                            <i class="{{ config('other.font-awesome') }} fa-comment" data-toggle="tooltip" data-original-title="Comments Left"></i>
                                                    {{ $torrent->comments_count }}
                        </span>
                                                    @if ($torrent->internal == 1)
                                                        <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-magic' data-toggle='tooltip' title=''
                                   data-original-title='Internal Release' style="color: #baaf92;"></i> Internal
                            </span>
                                                    @endif

                                                @if ($torrent->stream == 1)
                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-play text-red' data-toggle='tooltip' title=''
                                   data-original-title='Stream Optimized'></i> Stream Optimized
                            </span>
                                                @endif

                                                @if ($torrent->featured == 0)
                                                    @if ($torrent->doubleup == 1)
                                                        <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-gem text-green' data-toggle='tooltip' title=''
                                   data-original-title='Double upload'></i> Double Upload
                            </span>
                                                    @endif
                                                    @if ($torrent->free == 1)
                                                        <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-star text-gold' data-toggle='tooltip' title=''
                                   data-original-title='100% Free'></i> 100% Free
                            </span>
                                                    @endif
                                                @endif

                                                @if ($personal_freeleech)
                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-id-badge text-orange' data-toggle='tooltip' title=''
                                   data-original-title='Personal FL'></i> Personal FL
                            </span>
                                                @endif

                                                @php $freeleech_token = \App\Models\FreeleechToken::where('user_id', '=', $user->id)->where('torrent_id', '=', $torrent->id)->first(); @endphp
                                                @if ($freeleech_token)
                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-star text-bold' data-toggle='tooltip' title=''
                                   data-original-title='Freeleech Token'></i> Freeleech Token
                            </span>
                                                @endif

                                                @if ($torrent->featured == 1)
                                                    <span class='badge-extra text-bold' style='background-image:url(/img/sparkels.gif);'>
                                <i class='{{ config("other.font-awesome") }} fa-certificate text-pink' data-toggle='tooltip' title=''
                                   data-original-title='Featured Torrent'></i> Featured
                            </span>
                                                @endif

                                                @if ($user->group->is_freeleech == 1)
                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-trophy text-purple' data-toggle='tooltip' title=''
                                   data-original-title='Special FL'></i> Special FL
                            </span>
                                                @endif

                                                @if (config('other.freeleech') == 1)
                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-globe text-blue' data-toggle='tooltip' title=''
                                   data-original-title='Global FreeLeech'></i> Global FreeLeech
                            </span>
                                                @endif

                                                @if (config('other.doubleup') == 1)
                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-globe text-green' data-toggle='tooltip' title=''
                                   data-original-title='Double Upload'></i> Global Double Upload
                            </span>
                                                @endif

                                                @if ($torrent->leechers >= 5)
                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-fire text-orange' data-toggle='tooltip' title=''
                                   data-original-title='Hot!'></i> Hot
                            </span>
                                                @endif

                                                @if ($torrent->sticky == 1)
                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-thumbtack text-black' data-toggle='tooltip' title=''
                                   data-original-title='Sticky!'></i> Sticky
                            </span>
                                                @endif

                                                @if ($user->updated_at->getTimestamp() < $torrent->created_at->getTimestamp())
                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-magic text-black' data-toggle='tooltip' title=''
                                   data-original-title='NEW!'></i> NEW
                            </span>
                                                @endif

                                                @if ($torrent->highspeed == 1)
                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-tachometer text-red' data-toggle='tooltip' title=''
                                   data-original-title='High Speeds!'></i> High Speeds
                            </span>
                                                @endif

                                                @if ($torrent->sd == 1)
                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-ticket text-orange' data-toggle='tooltip' title=''
                                   data-original-title='SD Content!'></i> SD Content
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
                            <td>
                                <a href="{{ route('history', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
                            <span class='badge-extra text-orange text-bold'>
                                {{ $torrent->times_completed }} @lang('common.times')
                            </span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                </tbody>
            </table>
            <div class="text-center">
                {{ $torrents->links() }}
            </div>
        </div>
    </div>
    </div>
@endsection
