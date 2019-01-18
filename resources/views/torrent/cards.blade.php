@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('torrents') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.torrents')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('cards') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.cards-view')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block">
            <div class="mb-20">
                <div style="float:left;">
                    <a href="{{ route('categories') }}" class="btn btn-sm btn-primary">
                        <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('torrent.categories')
                    </a>
                    <a href="{{ route('catalogs') }}" class="btn btn-sm btn-primary">
                        <i class="{{ config('other.font-awesome') }} fa-book"></i> @lang('torrent.catalogs')
                    </a>
                    <a href="{{ route('rss.index') }}" class="btn btn-sm btn-warning">
                        <i class="{{ config('other.font-awesome') }} fa-rss"></i> @lang('rss.rss') @lang('rss.feeds')
                    </a>
                    <a href="{{ route('torrents') }}" class="btn btn-sm btn-primary">
                        <i class="{{ config('other.font-awesome') }} fa-list"></i> @lang('torrent.list')
                    </a>
                    <a href="{{ route('cards') }}" class="btn btn-sm btn-primary">
                        <i class="{{ config('other.font-awesome') }} fa-image"></i> @lang('torrent.cards')
                    </a>
                    <a href="{{ route('groupings') }}" class="btn btn-sm btn-primary">
                        <i class="{{ config('other.font-awesome') }} fa-clone"></i> @lang('torrent.groupings')
                    </a>
                </div>
                <div style="float:right;">
                    <a href="#/" class="btn btn-sm btn-danger" id="facetedFiltersToggle">
                        <i class="{{ config('other.font-awesome') }} fa-sliders-h"></i> @lang('torrent.filters')
                    </a>
                </div>
            </div>
            <br>
            <div class="header gradient blue">
                <div class="inner_content">
                    <h1>
                        @lang('torrent.torrents')
                    </h1>
                </div>
            </div>
            <div id="facetedDefault" style="{{ ($user->torrent_filters ? 'display: none;' : '') }}">
                <div class="box">
                    <div class="container mt-5">
                        <div class="mx-0 mt-5 form-group">
                            <div>
                                <input type="text" class="form-control facetedSearch" trigger="keyup" id="query" placeholder="@lang('torrent.search')">
                            </div>
                        </div>
                    </div>
                    <hr style="padding: 5px 0; margin: 0;">
                </div>
            </div>
            <div id="facetedFilters" style="{{ ($user->torrent_filters ? '' : 'display: none;') }}">
                <div class="box">
                    <div class="container search mt-5">
                <form role="form" method="GET" action="TorrentController@torrents" class="form-horizontal form-condensed form-torrent-search form-bordered">
                    @csrf
                    <div class="mx-0 mt-5 form-group">
                        <label for="name" class="mt-5 col-sm-1 label label-default">@lang('torrent.name')</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control facetedSearch" trigger="keyup" id="search" placeholder="@lang('torrent.name')">
                        </div>
                    </div>

                    <div class="mx-0 mt-5 form-group">
                        <label for="name" class="mt-5 col-sm-1 label label-default">@lang('torrent.description')</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control facetedSearch" trigger="keyup" id="description" placeholder="@lang('torrent.description')">
                        </div>
                    </div>

                    <div class="mx-0 mt-5 form-group">
                        <label for="uploader" class="mt-5 col-sm-1 label label-default">@lang('torrent.uploader')</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control facetedSearch" trigger="keyup" id="uploader" placeholder="@lang('torrent.uploader')">
                        </div>
                    </div>

                    <div class="mx-0 mt-5 form-group">
                        <label for="imdb" class="mt-5 col-sm-1 label label-default">ID</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control facetedSearch" trigger="keyup" id="imdb" placeholder="IMDB #">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control facetedSearch" trigger="keyup" id="tvdb" placeholder="TVDB #">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control facetedSearch" trigger="keyup" id="tmdb" placeholder="TMDB #">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control facetedSearch" trigger="keyup" id="mal" placeholder="MAL #">
                        </div>
                    </div>

                    <div class="mx-0 mt-5 form-group">
                        <label for="category" class="mt-5 col-sm-1 label label-default">@lang('torrent.category')</label>
                        <div class="col-sm-10">
                            @foreach ($repository->categories() as $id => $category)
                                <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" trigger="click" id="{{ $category }}" value="{{ $id }}" class="category facetedSearch"> {{ $category }}
                                </label>
                            </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="mx-0 mt-5 form-group">
                        <label for="type" class="mt-5 col-sm-1 label label-default">@lang('torrent.type')</label>
                        <div class="col-sm-10">
                            @foreach ($repository->types() as $id => $type)
                                <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" trigger="click" id="{{ $type }}" value="{{ $type }}" class="type facetedSearch"> {{ $type }}
                                </label>
                            </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="mx-0 mt-5 form-group">
                        <label for="genre" class="mt-5 col-sm-1 label label-default">Genre</label>
                        <div class="col-sm-10">
                            @foreach ($repository->tags() as $id => $genre)
                                <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" trigger="click" id="{{ $genre }}" value="{{ $genre}}" class="genre facetedSearch"> {{ $genre }}
                                </label>
                            </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="mx-0 mt-5 form-group">
                        <label for="type" class="mt-5 col-sm-1 label label-default">@lang('torrent.discounts')</label>
                        <div class="col-sm-10">
                        <span class="badge-user">
                            <label class="inline">
                                <input type="checkbox" id="freeleech" trigger="click" value="1" class="facetedSearch"> <span class="{{ config('other.font-awesome') }} fa-star text-gold"></span> @lang('torrent.freeleech')
                            </label>
                        </span>
                            <span class="badge-user">
                            <label class="inline">
                                <input type="checkbox" id="doubleupload" trigger="click" value="1" class="facetedSearch"> <span class="{{ config('other.font-awesome') }} fa-gem text-green"></span> @lang('torrent.double-upload')
                            </label>
                        </span>
                            <span class="badge-user">
                            <label class="inline">
                                <input type="checkbox" id="featured" trigger="click" value="1" class="facetedSearch"> <span class="{{ config('other.font-awesome') }} fa-certificate text-pink"></span> @lang('torrent.featured')
                            </label>
                        </span>
                        </div>
                    </div>

                    <div class="mx-0 mt-5 form-group">
                        <label for="type" class="mt-5 col-sm-1 label label-default">@lang('torrent.special')</label>
                        <div class="col-sm-10">
                        <span class="badge-user">
                            <label class="inline">
                                <input type="checkbox" id="stream" trigger="click" value="1" class="facetedSearch"> <span class="{{ config('other.font-awesome') }} fa-play text-red"></span> @lang('torrent.stream-optimized')
                            </label>
                        </span>
                            <span class="badge-user">
                            <label class="inline">
                                <input type="checkbox" id="highspeed" trigger="click" value="1" class="facetedSearch"> <span class="{{ config('other.font-awesome') }} fa-tachometer text-red"></span> @lang('common.high-speeds')
                            </label>
                        </span>
                            <span class="badge-user">
                            <label class="inline">
                                <input type="checkbox" id="sd" trigger="click" value="1" class="facetedSearch"> <span class="{{ config('other.font-awesome') }} fa-ticket text-orange"></span> @lang('torrent.sd-content')
                            </label>
                        </span>
                            <span class="badge-user">
                            <label class="inline">
                                <input type="checkbox" id="internal" trigger="click" value="1" class="facetedSearch"> <span class="{{ config('other.font-awesome') }} fa-magic" style="color: #BAAF92"></span> @lang('torrent.internal')
                            </label>
                        </span>
                        </div>
                    </div>

                    <div class="mx-0 mt-5 form-group">
                        <label for="type" class="mt-5 col-sm-1 label label-default">@lang('torrent.health')</label>
                        <div class="col-sm-10">
                        <span class="badge-user">
                            <label class="inline">
                                <input type="checkbox" id="alive" trigger="click" value="1" class="facetedSearch"> <span class="{{ config('other.font-awesome') }} fa-smile text-green"></span> @lang('torrent.alive')
                            </label>
                        </span>
                            <span class="badge-user">
                            <label class="inline">
                                <input type="checkbox" id="dying" trigger="click" value="1" class="facetedSearch"> <span class="{{ config('other.font-awesome') }} fa-meh text-orange"></span> @lang('torrent.dying-torrent')
                            </label>
                        </span>
                            <span class="badge-user">
                            <label class="inline">
                                <input type="checkbox" id="dead" trigger="click" value="0" class="facetedSearch"> <span class="{{ config('other.font-awesome') }} fa-frown text-red"></span> @lang('torrent.dead-torrent')
                            </label>
                        </span>
                        </div>
                    </div>
                    <div class="mx-0 mt-5 form-group">
                        <label for="sort" class="mt-5 col-sm-1 label label-default">@lang('common.sort')</label>
                        <div class="col-sm-2">
                            <select id="sorting" trigger="change" name="sorting" class="form-control facetedSearch">
                                @foreach ($repository->sorting() as $value => $sort)
                                    <option value="{{ $value }}">{{ $sort }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mx-0 mt-5 form-group">
                        <label for="sort" class="mt-5 col-sm-1 label label-default">@lang('common.direction')</label>
                        <div class="col-sm-2">
                            <select id="direction" trigger="change" name="direction" class="form-control facetedSearch">
                                @foreach ($repository->direction() as $value => $dir)
                                    <option value="{{ $value }}">{{ $dir }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
                </div>
            </div>
            <span id="facetedHeader"></span>
            <div id="facetedSearch" type="card" font-awesome="{{ config('other.font-awesome') }}">
                <div style="width: 100% !important; display: table !important;">
                    <div class="mb-5" style="width: 100% !important; display: table-cell !important;">
                        @foreach ($torrents as $k => $t)
                            <div class="col-md-4">
                                <div class="card is-torrent">
                                    <div class="card_head">
                                <span class="badge-user text-bold" style="float:right;">
                                    <i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-up text-green"></i> {{ $t->seeders }} /
                                    <i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-down text-red"></i> {{ $t->leechers }} /
                                    <i class="{{ config('other.font-awesome') }} fa-fw fa-check text-orange"></i>{{ $t->times_completed }}
                                </span>&nbsp;
                                        <span class="badge-user text-bold text-blue" style="float:right;">{{ $t->getSize() }}</span>&nbsp;
                                        <span class="badge-user text-bold text-blue" style="float:right;">{{ $t->type }}</span>&nbsp;
                                        <span class="badge-user text-bold text-blue" style="float:right;">{{ $t->category->name }}</span>&nbsp;
                                    </div>
                                    <div class="card_body">
                                        <div class="body_poster">
                                            @if($t->movie && $t->movie->poster)
                                                <img src="{{ $t->movie->poster }}">
                                            @endif
                                        </div>
                                        <div class="body_description">
                                            <h3 class="description_title">
                                                <a href="{{ route('torrent', ['slug' => $t->slug, 'id' => $t->id]) }}">{{ $t->name }}
                                                    @if($t->movie && $t->movie->releaseYear)
                                                        <span class="text-bold text-pink"> {{ $t->movie->releaseYear }}</span>
                                                    @endif
                                                </a>
                                            </h3>
                                            @if ($t->movie && $t->movie->genres)
                                                @foreach ($t->movie->genres as $genre)
                                                    <span class="genre-label">{{ $genre }}</span>
                                                @endforeach
                                            @endif
                                            <p class="description_plot">
                                                @if($t->movie && $t->movie->plot)
                                                    {{ $t->movie->plot }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="card_footer">
                                        <div style="float: left;">
                                            @if ($t->anon == 1)
                                                <span class="badge-user text-orange text-bold">{{ strtoupper(trans('common.anonymous')) }} @if (auth()->user()->id == $t->user->id || auth()->user()->group->is_modo)
                                                        <a href="{{ route('profile', ['username' => $t->user->username, 'id' => $t->user->id]) }}">({{ $t->user->username }}
                                                    )</a>@endif</span>
                                            @else
                                                <a href="{{ route('profile', ['username' => $t->user->username, 'id' => $t->user->id]) }}">
                                            <span class="badge-user text-bold" style="color:{{ $t->user->group->color }}; background-image:{{ $t->user->group->effect }};">
                                                <i class="{{ $t->user->group->icon }}" data-toggle="tooltip" title=""
                                                   data-original-title="{{ $t->user->group->name }}"></i> {{ $t->user->username }}
                                            </span>
                                                </a>
                                            @endif
                                        </div>
                                        <span class="badge-user text-bold" style="float: right;">
                                            <i class="{{ config('other.font-awesome') }} fa-star text-gold"></i>
                                            @if($t->movie && ($t->movie->imdbRating || $t->movie->tmdbVotes))
                                                @if ($user->ratings == 1)
                                                    {{ $t->movie->imdbRating }}/10 ({{ $t->movie->imdbVotes }} @lang('torrent.votes'))
                                                @else
                                                    {{ $t->movie->tmdbRating }}/10 ({{ $t->movie->tmdbVotes }} @lang('torrent.votes'))
                                                @endif
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div style="width: 100% !important; display: table !important;">
                    <div class="align-center" style="width: 100% !important; display: table-cell !important;">{{ $torrents->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
