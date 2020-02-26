@extends('layout.default')

@section('title')
    <title>@lang('torrent.torrents') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('torrent.torrents')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('torrents') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.torrents')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('groupings') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.groupings-view')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block">
            @include('torrent.buttons')
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
                        <div class="mx-0 mt-5 form-group fatten-me">
                            <div>
                                <label for="query"></label><input type="text" class="form-control facetedSearch" trigger="keyup" id="query" placeholder="@lang('torrent.search')">
                            </div>
                        </div>
                    </div>
                    <hr style="padding: 5px 0; margin: 0;">
                </div>
            </div>
            <div id="facetedFilters" style="{{ ($user->torrent_filters ? '' : 'display: none;') }}">
                <div class="box">
                    <div class="container well search mt-5">
                <form role="form" method="GET" action="TorrentController@torrents" class="form-horizontal form-condensed form-torrent-search form-bordered">
                    @csrf
                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="name" class="mt-5 col-sm-1 label label-default fatten-me">@lang('torrent.name')</label>
                        <div class="col-sm-9">
                            <label for="search"></label><input type="text" class="form-control facetedSearch" trigger="keyup" id="search" placeholder="@lang('torrent.name')">
                        </div>
                    </div>

                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="name" class="mt-5 col-sm-1 label label-default fatten-me">@lang('torrent.description')</label>
                        <div class="col-sm-9">
                            <label for="description"></label><input type="text" class="form-control facetedSearch" trigger="keyup" id="description" placeholder="@lang('torrent.description')">
                        </div>
                    </div>

                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="uploader" class="mt-5 col-sm-1 label label-default fatten-me">@lang('torrent.uploader')</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control facetedSearch" trigger="keyup" id="uploader" placeholder="@lang('torrent.uploader')">
                        </div>
                    </div>

                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="imdb" class="mt-5 col-sm-1 label label-default fatten-me">ID</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control facetedSearch" trigger="keyup" id="imdb" placeholder="IMDB #">
                        </div>
                        <div class="col-sm-2">
                            <label for="tvdb"></label><input type="text" class="form-control facetedSearch" trigger="keyup" id="tvdb" placeholder="TVDB #">
                        </div>
                        <div class="col-sm-2">
                            <label for="tmdb"></label><input type="text" class="form-control facetedSearch" trigger="keyup" id="tmdb" placeholder="TMDB #">
                        </div>
                        <div class="col-sm-2">
                            <label for="mal"></label><input type="text" class="form-control facetedSearch" trigger="keyup" id="mal" placeholder="MAL #">
                        </div>
                        <div class="col-sm-2">
                            <label for="igdb"></label><input type="text" class="form-control facetedSearch" trigger="keyup" id="igdb" placeholder="IGDB #">
                        </div>
                    </div>

                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="release_year" class="mt-5 col-sm-1 label label-default fatten-me">Year Range</label>
                        <div class="col-sm-2">
                            <label for="start_year"></label><input type="text" class="form-control facetedSearch" trigger="keyup" id="start_year" placeholder="Start Year">
                        </div>
                        <div class="col-sm-2">
                            <label for="end_year"></label><input type="text" class="form-control facetedSearch" trigger="keyup" id="end_year" placeholder="End Year">
                        </div>
                    </div>

                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="category" class="mt-5 col-sm-1 label label-default fatten-me">@lang('torrent.category')</label>
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

                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="type" class="mt-5 col-sm-1 label label-default fatten-me">@lang('torrent.type')</label>
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

                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="genre" class="mt-5 col-sm-1 label label-default fatten-me">@lang('torrent.genre')</label>
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

                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="type" class="mt-5 col-sm-1 label label-default fatten-me">@lang('torrent.discounts')</label>
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

                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="type" class="mt-5 col-sm-1 label label-default fatten-me">@lang('torrent.special')</label>
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
                                <input type="checkbox" id="internal" trigger="click" value="1" class="facetedSearch"> <span class="{{ config('other.font-awesome') }} fa-magic" style="color: #baaf92;"></span> @lang('torrent.internal')
                            </label>
                        </span>
                        </div>
                    </div>

                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="type" class="mt-5 col-sm-1 label label-default fatten-me">@lang('torrent.health')</label>
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
                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="sort" class="mt-5 col-sm-1 label label-default fatten-me">@lang('common.sort')</label>
                        <div class="col-sm-2">
                            <label for="sorting"></label><select id="sorting" trigger="change" name="sorting" class="form-control facetedSearch">
                                @foreach ($repository->sorting() as $value => $sort)
                                    <option value="{{ $value }}">{{ $sort }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="sort" class="mt-5 col-sm-1 label label-default fatten-me">@lang('common.direction')</label>
                        <div class="col-sm-2">
                            <label for="direction"></label><select id="direction" trigger="change" name="direction" class="form-control facetedSearch">
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
            <div id="facetedSearch" type="group" font-awesome="{{ config('other.font-awesome') }}">
                <div style="width: 100% !important; display: table !important;">
                    <div class="align-center" style="width: 100% !important; display: table-cell !important;">
                        @if($links)
                            {{ $links->links() }}
                        @endif
                    </div>
                </div>
                <div style="width: 100% !important; display: table !important;">
                    <div class="mb-5" style="width: 100% !important; display: table-cell !important;">
                        @if($torrents && is_array($torrents))
                            @foreach ($torrents as $k => $c)
                                @php
                                    $parent = reset($c);
                                    $t = $parent['chunk'];
                                @endphp
                                <div class="box">
                                    <div class="card is-torrent is-group">
                                        <div class="card_head">
                                            @if($attributes && is_array($attributes))
                                                <span class="badge-user text-bold" style="float:right;">
                                    <i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-up text-green"></i> {{ $attributes[$k]['seeders'] }} /
                                    <i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-down text-red"></i> {{ $attributes[$k]['leechers'] }} /
                                    <i class="{{ config('other.font-awesome') }} fa-fw fa-check text-orange"></i>{{ $attributes[$k]['times_completed'] }}
                                </span>&nbsp;
                                            @endif
                                                <span class="badge-user text-bold" style="float: right;">
                                                <i class="{{ config('other.font-awesome') }} fa-thumbs-up text-gold"></i>
                                                @if($t->meta && ($t->meta->imdbRating || $t->meta->tmdbVotes))
                                                        @if ($user->ratings == 1)
                                                            {{ $t->meta->imdbRating }}/10 ({{ $t->meta->imdbVotes }} @lang('torrent.votes'))
                                                        @else
                                                            {{ $t->meta->tmdbRating }}/10 ({{ $t->meta->tmdbVotes }} @lang('torrent.votes'))
                                                        @endif
                                                    @endif
                                            </span>
                                        </div>
                                        <div class="card_alt">
                                            <div class="body_poster">
                                                @if($t->meta && $t->meta->poster)
                                                    <img src="{{ $t->meta->poster }}" class="show-poster" alt="@lang('torrent.poster')"
                                                         data-image='<img src="{{ $t->meta->poster }}" alt="@lang('torrent.poster')"
                                                         style="height: 1000px;">'>
                                                @else
                                                    <img src="https://via.placeholder.com/600x900" alt="@lang('torrent.poster')" />
                                                @endif
                                            </div>
                                            <div class="body_grouping" style="width: 100%;">
                                                <h3 class="description_title">
                                                    {{ ($t->meta ? $t->meta->title : $t->name) }}
                                                    @if($t->meta && $t->meta->releaseYear)
                                                        <span class="text-bold text-pink"> {{ $t->meta->releaseYear }}</span>
                                                    @endif
                                                </h3>
                                                @if ($t->meta && $t->meta->genres)
                                                    @foreach ($t->meta->genres as $genre)
                                                        <span class="genre-label">{{ $genre }}</span>
                                                    @endforeach
                                                @endif
                                                <p class="description_plot" style="width: 100%;">
                                                    @if($t->meta && $t->meta->plot)
                                                        {{ $t->meta->plot }}
                                                    @endif
                                                </p>
                                                <div class="card_holder" style="width: 100%;" ;>
                                                    <hr style="padding: 0 !important; margin: 5px 0 !important; width: 100%;">
                                                    <div style="padding: 10px 0; margin: auto;">
                                                        <div class="table-responsive">
                                                            <table class="table table-condensed table-bordered table-striped table-hover" style="margin-bottom: 5px !important;">
                                                                <thead>
                                                                <tr>
                                                                    <th>@lang('torrent.category')</th>
                                                                    <th>@lang('torrent.name')</th>
                                                                    <th><i class="{{ config('other.font-awesome') }} fa-clock"></i></th>
                                                                    <th><i class="{{ config('other.font-awesome') }} fa-file"></i></th>
                                                                    <th><i class="{{ config('other.font-awesome') }} fa-arrow-circle-up"></i></th>
                                                                    <th><i class="{{ config('other.font-awesome') }} fa-arrow-circle-down"></i></th>
                                                                    <th><i class="{{ config('other.font-awesome') }} fa-check-square"></i></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @php
                                                                    $class = "";
                                                                    $hidden = "";
                                                                    $attr = "";
                                                                @endphp
                                                                @for($x = 0; $x<count($c); $x++)
                                                                    @php
                                                                        if(array_key_exists('torrent'.$x,$c)) {
                                                                            $current = $c['torrent'.$x]['chunk'];
                                                                        } else {
                                                                            continue;
                                                                        }
                                                                        if($x > 5) {
                                                                            $class=" facetedLoading";
                                                                            $hidden="display: none;";
                                                                            $attr = $k;
                                                                        }
                                                                    @endphp
                                                                    @if ($current->sticky == 1)
                                                                        <tr class="success{{ $class }}" style="{{ $hidden }}" torrent="{{ $attr }}">
                                                                    @else
                                                                        <tr class="{{ $class }}" style="{{ $hidden }}" torrent="{{ $attr }}">
                                                                            @endif
                                                                            <td>
                                                                                @if ($current->category->image != null)
                                                                                    <a href="{{ route('categories.show', ['id' => $current->category->id]) }}">
                                                                                        <div class="text-center">
                                                                                            <img src="{{ url('files/img/' . $current->category->image) }}" alt="{{ $current->category->name }}"
                                                                                                 data-toggle="tooltip"
                                                                                                 data-original-title="{{ $current->category->name }} {{ strtolower(trans('torrent.torrent')) }}"
                                                                                                 style="padding-bottom: 6px;">
                                                                                        </div>
                                                                                    </a>
                                                                                @else
                                                                                    <a href="{{ route('categories.show', ['id' => $current->category->id]) }}">
                                                                                        <div class="text-center">
                                                                                            <i class="{{ $current->category->icon }} torrent-icon" data-toggle="tooltip"
                                                                                               data-original-title="{{ $current->category->name }} {{ strtolower(trans('torrent.torrent')) }}"
                                                                                               style="padding-bottom: 6px;"></i>
                                                                                        </div>
                                                                                    </a>
                                                                                @endif
                                                                                <div class="text-center">
                                                            <span class="label label-success" data-toggle="tooltip" data-original-title="@lang('torrent.type')">
                                                                {{ $current->type }}
                                                            </span>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <a class="view-torrent" href="{{ route('torrent', ['id' => $current->id]) }}">
                                                                                    {{ $current->name }}
                                                                                </a>
                                                                                @if (config('torrent.download_check_page') == 1)
                                                                                    <a href="{{ route('download_check', ['id' => $current->id]) }}">
                                                                                        <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                                                                                data-original-title="@lang('common.download')">
                                                                                            <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                                                                        </button>
                                                                                    </a>
                                                                                @else
                                                                                    <a href="{{ route('download', ['id' => $current->id]) }}">
                                                                                        <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                                                                                data-original-title="@lang('common.download')">
                                                                                            <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                                                                        </button>
                                                                                    </a>
                                                                                @endif

                                                                                <span id="torrentBookmark{{ $current->id }}" torrent="{{ $current->id }}" state="{{ $bookmarks->where('torrent_id', $current->id)->first() ? 1 : 0}}" class="torrentBookmark"></span>

                                                                                <br>
                                                                                @if ($current->anon == 1)
                                                                                    <span class="badge-extra text-bold">
                                                                <i class="{{ config('other.font-awesome') }} fa-upload" data-toggle="tooltip" data-original-title="@lang('torrent.uploader')"></i> @lang('common.anonymous')
                                                                                        @if ($user->id == $current->user->id || $user->group->is_modo)
                                                                                            <a href="{{ route('users.show', ['username' => $current->user->username]) }}">
                                                                        ({{ $current->user->username }})
                                                                    </a>
                                                                                        @endif
                                                            </span>
                                                                                @else
                                                                                    <span class="badge-extra text-bold">
                                                                <i class="{{ config('other.font-awesome') }} fa-upload" data-toggle="tooltip" data-original-title="@lang('torrent.uploader')"></i>
                                                                    <a href="{{ route('users.show', ['username' => $current->user->username]) }}">
                                                                        {{ $current->user->username }}
                                                                    </a>
                                                            </span>
                                                                                @endif
                                                                                <span class="badge-extra text-bold text-pink">
                            <i class="{{ config('other.font-awesome') }} fa-heart" data-toggle="tooltip" data-original-title="@lang('torrent.thanks-given')"></i>
                                                        {{ $current->thanks_count }}
                        </span>

                                                                                <span class="badge-extra text-bold text-green">
                            <i class="{{ config('other.font-awesome') }} fa-comment" data-toggle="tooltip" data-original-title="@lang('common.comments')"></i>
                                                        {{ $current->comments_count }}
                        </span>

                                                                                @if ($current->internal == 1)
                                                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-magic' data-toggle='tooltip' title=''
                                   data-original-title='@lang('torrent.internal-release')' style="color: #baaf92;"></i> @lang('torrent.internal')
                            </span>
                                                                                @endif
                                                                                @if ($current->stream == 1)
                                                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-play text-red' data-toggle='tooltip' title=''
                                   data-original-title='@lang('torrent.stream-optimized')'></i> @lang('torrent.stream-optimized')
                            </span>
                                                                                @endif

                                                                                @if ($current->featured == 0)
                                                                                    @if ($current->doubleup == 1)
                                                                                        <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-gem text-green' data-toggle='tooltip' title=''
                                   data-original-title='@lang('torrent.double-upload')'></i> @lang('torrent.double-upload')
                            </span>
                                                                                    @endif
                                                                                    @if ($current->free == 1)
                                                                                        <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-star text-gold' data-toggle='tooltip' title=''
                                   data-original-title='@lang('torrent.freeleech')'></i> @lang('torrent.freeleech')
                            </span>
                                                                                    @endif
                                                                                @endif

                                                                                @if ($personal_freeleech)
                                                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-id-badge text-orange' data-toggle='tooltip' title=''
                                   data-original-title='@lang('torrent.personal-freeleech')'></i> @lang('torrent.personal-freeleech')
                            </span>
                                                                                @endif

                                                                                @if ($user->freeleechTokens->where('torrent_id', $current->id)->first())
                                                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-star text-bold' data-toggle='tooltip' title=''
                                   data-original-title='@lang('torrent.freeleech-token')'></i> @lang('torrent.freeleech-token')
                            </span>
                                                                                @endif

                                                                                @if ($current->featured == 1)
                                                                                    <span class='badge-extra text-bold' style='background-image:url(/img/sparkels.gif);'>
                                <i class='{{ config("other.font-awesome") }} fa-certificate text-pink' data-toggle='tooltip' title=''
                                   data-original-title='@lang('torrent.featured')'></i> @lang('torrent.featured')
                            </span>
                                                                                @endif

                                                                                @if ($user->group->is_freeleech == 1)
                                                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-trophy text-purple' data-toggle='tooltip' title=''
                                   data-original-title='@lang('torrent.special-freeleech')'></i> @lang('torrent.special-freeleech')
                            </span>
                                                                                @endif

                                                                                @if (config('other.freeleech') == 1)
                                                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-globe text-blue' data-toggle='tooltip' title=''
                                   data-original-title='@lang('torrent.global-freeleech')'></i> @lang('torrent.global-freeleech')
                            </span>
                                                                                @endif

                                                                                @if (config('other.doubleup') == 1)
                                                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-globe text-green' data-toggle='tooltip' title=''
                                   data-original-title='@lang('torrent.double-upload')'></i> @lang('torrent.double-upload')
                            </span>
                                                                                @endif

                                                                                @if ($current->leechers >= 5)
                                                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-fire text-orange' data-toggle='tooltip' title=''
                                   data-original-title='@lang('common.hot')!'></i> @lang('common.hot')!
                            </span>
                                                                                @endif

                                                                                @if ($current->sticky == 1)
                                                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-thumbtack text-black' data-toggle='tooltip' title=''
                                   data-original-title='@lang('torrent.sticky')!'></i> @lang('torrent.sticky')
                            </span>
                                                                                @endif

                                                                                @if ($user->updated_at->getTimestamp() < $current->created_at->getTimestamp())
                                                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-magic text-black' data-toggle='tooltip' title=''
                                   data-original-title='@lang('common.new')!'></i> @lang('common.new')
                            </span>
                                                                                @endif

                                                                                @if ($current->highspeed == 1)
                                                                                    <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-tachometer text-red' data-toggle='tooltip' title=''
                                   data-original-title='@lang('common.high-speeds')'></i> @lang('common.high-speeds')
                            </span>
                                                                                @endif

                                                                                @if ($current->sd == 1)
                                                                                    <span class='badge-extra text-bold'>
                                                                            <i class='{{ config("other.font-awesome") }} fa-ticket text-orange' data-toggle='tooltip' title=''
                                                                               data-original-title='@lang('torrent.sd-content')!'></i> @lang('torrent.sd-content')
                                                                            </span>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                <time>{{ $current->created_at->diffForHumans() }}</time>
                                                                            </td>
                                                                            <td>
                                                                                <span class='badge-extra text-blue text-bold'>{{ $current->getSize() }}</span>
                                                                            </td>
                                                                            <td>
                                                                                <a href="{{ route('peers', ['id' => $current->id]) }}">
                            <span class='badge-extra text-green text-bold'>
                                {{ $current->seeders }}
                            </span>
                                                                                </a>
                                                                            </td>
                                                                            <td>
                                                                                <a href="{{ route('peers', ['id' => $current->id]) }}">
                            <span class='badge-extra text-red text-bold'>
                                {{ $current->leechers }}
                            </span>
                                                                                </a>
                                                                            </td>
                                                                            <td>
                                                                                <a href="{{ route('history', ['id' => $current->id]) }}">
                            <span class='badge-extra text-orange text-bold'>
                                {{ $current->times_completed }} @lang('common.times')
                            </span>
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                        @endfor
                                                                        @if($x >= 5)
                                                                            <tr id="facetedCell{{ $k }}">
                                                                                <td colspan="7" class="text-center">
                                                                                    <a href="#/" class="facetedLoader" torrent="{{ $k }}">
                            <span class='btn btn-sm btn-warning'>
                                Load All {{ $x }} Torrents
                            </span>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card_footer">
                                            <div style="float: right;">
                                                @if($attributes && is_array($attributes))
                                                @php $cats = $repository->categories()->toArray(); @endphp
                                                @foreach($attributes[$k]['types'] as $a => $attr)
                                                    <span class="badge-user text-bold text-blue" style="float:right;">{{ $attr }}</span>&nbsp;
                                                @endforeach
                                                @foreach($attributes[$k]['categories'] as $a => $attr)
                                                    @if(array_key_exists($attr,$cats))
                                                        <span class="badge-user text-bold text-blue" style="float:right;">{{ $cats[$attr] }}</span>
                                                    @endif
                                                @endforeach
                                                    @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div style="width: 100% !important; display: table !important;">
                    <div class="align-center" style="width: 100% !important; display: table-cell !important;">
                        @if($links)
                            {{ $links->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
