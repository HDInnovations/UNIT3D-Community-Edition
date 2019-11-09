@extends('layout.default')

@section('title')
    <title>@lang('torrent.torrents') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('torrent.torrents') {{ config('other.title') }}">
@endsection

@section('breadcrumb')
    <li class="active">
        <a href="{{ route('torrents') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.torrents')</span>
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
            <div id="facetedDefault" style="{{ $user->torrent_filters ? 'display: none;' : '' }}">
                <div class="box">
                    <div class="container mt-5">
                        <div class="mx-0 mt-5 form-group fatten-me">
                            <div>
                                <label for="query"></label><input type="text" class="form-control facetedSearch"
                                    trigger="keyup" id="query" placeholder="@lang('torrent.search')">
                            </div>
                        </div>
                    </div>
                    <hr style="padding: 5px 0; margin: 0;">
                </div>
            </div>
            <div id="facetedFilters" style="{{ $user->torrent_filters ? '' : 'display: none;' }}">
                <div class="box">
                    <div class="container well search mt-5 fatten-me">
                        <form role="form" method="GET" action="TorrentController@torrents"
                            class="form-horizontal form-condensed form-torrent-search form-bordered">
                            @csrf
    
                            <div class="mx-0 mt-5 form-group fatten-me">
                                <label for="name"
                                    class="mt-5 col-sm-1 label label-default fatten-me">@lang('torrent.name')</label>
                                <div class="col-sm-9 fatten-me">
                                    <label for="search"></label><input type="text" class="form-control facetedSearch"
                                        trigger="keyup" id="search" placeholder="@lang('torrent.name')">
                                </div>
                            </div>
                            <div class="mx-0 mt-5 form-group fatten-me">
                                <label for="name"
                                    class="mt-5 col-sm-1 label label-default fatten-me">@lang('torrent.description')</label>
                                <div class="col-sm-9 fatten-me">
                                    <label for="description"></label><input type="text" class="form-control facetedSearch"
                                        trigger="keyup" id="description" placeholder="@lang('torrent.description')">
                                </div>
                            </div>
                            <div class="mx-0 mt-5 form-group fatten-me">
                                <label for="uploader"
                                    class="mt-5 col-sm-1 label label-default fatten-me">@lang('torrent.uploader')</label>
                                <div class="col-sm-9 fatten-me">
                                    <input type="text" class="form-control facetedSearch" trigger="keyup" id="uploader"
                                        placeholder="@lang('torrent.uploader')">
                                </div>
                            </div>
    
                            <div class="mx-0 mt-5 form-group fatten-me">
                                <label for="imdb" class="mt-5 col-sm-1 label label-default fatten-me">ID</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control facetedSearch" trigger="keyup" id="imdb"
                                        placeholder="IMDB #">
                                </div>
                                <div class="col-sm-2">
                                    <label for="tvdb"></label><input type="text" class="form-control facetedSearch"
                                        trigger="keyup" id="tvdb" placeholder="TVDB #">
                                </div>
                                <div class="col-sm-2">
                                    <label for="tmdb"></label><input type="text" class="form-control facetedSearch"
                                        trigger="keyup" id="tmdb" placeholder="TMDB #">
                                </div>
                                <div class="col-sm-2">
                                    <label for="mal"></label><input type="text" class="form-control facetedSearch"
                                        trigger="keyup" id="mal" placeholder="MAL #">
                                </div>
                                <div class="col-sm-2">
                                    <label for="igdb"></label><input type="text" class="form-control facetedSearch"
                                        trigger="keyup" id="igdb" placeholder="IGDB #">
                                </div>
                            </div>
    
                            <div class="mx-0 mt-5 form-group fatten-me">
                                <label for="release_year" class="mt-5 col-sm-1 label label-default fatten-me">Year
                                    Range</label>
                                <div class="col-sm-2">
                                    <label for="start_year"></label><input type="text" class="form-control facetedSearch"
                                        trigger="keyup" id="start_year" placeholder="Start Year">
                                </div>
                                <div class="col-sm-2">
                                    <label for="end_year"></label><input type="text" class="form-control facetedSearch"
                                        trigger="keyup" id="end_year" placeholder="End Year">
                                </div>
                            </div>
    
                            <div class="mx-0 mt-5 form-group fatten-me">
                                <label for="category"
                                    class="mt-5 col-sm-1 label label-default fatten-me">@lang('torrent.category')</label>
                                <div class="col-sm-10">
                                    @foreach ($repository->categories() as $id => $category)
                                        <span class="badge-user">
                                            <label class="inline">
                                                <input type="checkbox" id="{{ $category }}" value="{{ $id }}"
                                                    class="category facetedSearch" trigger="click"> {{ $category }}
                                            </label>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
    
                            <div class="mx-0 mt-5 form-group fatten-me">
                                <label for="type"
                                    class="mt-5 col-sm-1 label label-default fatten-me">@lang('torrent.type')</label>
                                <div class="col-sm-10">
                                    @foreach ($repository->types() as $id => $type)
                                        <span class="badge-user">
                                            <label class="inline">
                                                <input type="checkbox" id="{{ $type }}" value="{{ $type }}"
                                                    class="type facetedSearch" trigger="click"> {{ $type }}
                                            </label>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
    
                            <div class="mx-0 mt-5 form-group fatten-me">
                                <label for="genre"
                                    class="mt-5 col-sm-1 label label-default fatten-me">@lang('torrent.genre')</label>
                                <div class="col-sm-10">
                                    @foreach ($repository->tags() as $id => $genre)
                                        <span class="badge-user">
                                            <label class="inline">
                                                <input type="checkbox" id="{{ $genre }}" value="{{ $genre }}"
                                                    class="genre facetedSearch" trigger="click"> {{ $genre }}
                                            </label>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
    
                            <div class="mx-0 mt-5 form-group fatten-me">
                                <label for="type"
                                    class="mt-5 col-sm-1 label label-default fatten-me">@lang('torrent.discounts')</label>
                                <div class="col-sm-10">
                                    <span class="badge-user">
                                        <label class="inline">
                                            <input type="checkbox" id="freeleech" value="1" class="facetedSearch"
                                                trigger="click"> <span
                                                class="{{ config('other.font-awesome') }} fa-star text-gold"></span>
                                            @lang('torrent.freeleech')
                                        </label>
                                    </span>
                                    <span class="badge-user">
                                        <label class="inline">
                                            <input type="checkbox" id="doubleupload" value="1" class="facetedSearch"
                                                trigger="click"> <span
                                                class="{{ config('other.font-awesome') }} fa-gem text-green"></span>
                                            @lang('torrent.double-upload')
                                        </label>
                                    </span>
                                    <span class="badge-user">
                                        <label class="inline">
                                            <input type="checkbox" id="featured" value="1" class="facetedSearch"
                                                trigger="click"> <span
                                                class="{{ config('other.font-awesome') }} fa-certificate text-pink"></span>
                                            @lang('torrent.featured')
                                        </label>
                                    </span>
                                </div>
                            </div>
    
                            <div class="mx-0 mt-5 form-group fatten-me">
                                <label for="type"
                                    class="mt-5 col-sm-1 label label-default fatten-me">@lang('torrent.special')</label>
                                <div class="col-sm-10">
                                    <span class="badge-user">
                                        <label class="inline">
                                            <input type="checkbox" id="stream" value="1" class="facetedSearch"
                                                trigger="click"> <span
                                                class="{{ config('other.font-awesome') }} fa-play text-red"></span>
                                            @lang('torrent.stream-optimized')
                                        </label>
                                    </span>
                                    <span class="badge-user">
                                        <label class="inline">
                                            <input type="checkbox" id="highspeed" value="1" class="facetedSearch"
                                                trigger="click"> <span
                                                class="{{ config('other.font-awesome') }} fa-tachometer text-red"></span>
                                            @lang('common.high-speeds')
                                        </label>
                                    </span>
                                    <span class="badge-user">
                                        <label class="inline">
                                            <input type="checkbox" id="sd" value="1" class="facetedSearch" trigger="click">
                                            <span class="{{ config('other.font-awesome') }} fa-ticket text-orange"></span>
                                            @lang('torrent.sd-content')
                                        </label>
                                    </span>
                                    <span class="badge-user">
                                        <label class="inline">
                                            <input type="checkbox" id="internal" value="1" class="facetedSearch"
                                                trigger="click"> <span class="{{ config('other.font-awesome') }} fa-magic"
                                                style="color: #baaf92;"></span> @lang('torrent.internal')
                                        </label>
                                    </span>
                                </div>
                            </div>
    
                            <div class="mx-0 mt-5 form-group fatten-me">
                                <label for="type"
                                    class="mt-5 col-sm-1 label label-default fatten-me">@lang('torrent.health')</label>
                                <div class="col-sm-10">
                                    <span class="badge-user">
                                        <label class="inline">
                                            <input type="checkbox" id="alive" value="1" class="facetedSearch"
                                                trigger="click"> <span
                                                class="{{ config('other.font-awesome') }} fa-smile text-green"></span>
                                            @lang('torrent.alive')
                                        </label>
                                    </span>
                                    <span class="badge-user">
                                        <label class="inline">
                                            <input type="checkbox" id="dying" value="1" class="facetedSearch"
                                                trigger="click"> <span
                                                class="{{ config('other.font-awesome') }} fa-meh text-orange"></span>
                                            @lang('torrent.dying-torrent')
                                        </label>
                                    </span>
                                    <span class="badge-user">
                                        <label class="inline">
                                            <input type="checkbox" id="dead" value="0" class="facetedSearch"
                                                trigger="click"> <span
                                                class="{{ config('other.font-awesome') }} fa-frown text-red"></span>
                                            @lang('torrent.dead-torrent')
                                        </label>
                                    </span>
    
                                </div>
                            </div>
    
                            <div class="mx-0 mt-5 form-group fatten-me">
                                <label for="type"
                                    class="mt-5 col-sm-1 label label-default fatten-me">@lang('torrent.activity')</label>
                                <div class="col-sm-10">
    
                                    <span class="badge-user">
                                        <label class="inline">
                                            <input type="checkbox" id="seeding" value="1" class="facetedSearch"
                                                trigger="click"> <span
                                                class="{{ config('other.font-awesome') }} fa-hdd text-purple"></span>
                                            @lang('torrent.seeding')
                                        </label>
                                    </span>
                                    <span class="badge-user">
                                        <label class="inline">
                                            <input type="checkbox" id="leeching" value="1" class="facetedSearch"
                                                trigger="click"> <span
                                                class="{{ config('other.font-awesome') }} fa-hdd text-purple"></span>
                                            @lang('torrent.leeching')
                                        </label>
                                    </span>
                                    <span class="badge-user">
                                        <label class="inline">
                                            <input type="checkbox" id="downloaded" value="1" class="facetedSearch"
                                                trigger="click"> <span
                                                class="{{ config('other.font-awesome') }} fa-hdd text-purple"></span>
                                            @lang('torrent.have-completed')
                                        </label>
                                    </span>
                                    <span class="badge-user">
                                        <label class="inline">
                                            <input type="checkbox" id="idling" value="1" class="facetedSearch"
                                                trigger="click"> <span
                                                class="{{ config('other.font-awesome') }} fa-hdd text-purple"></span>
                                            @lang('torrent.have-not-completed')
                                        </label>
                                    </span>
                                    <span class="badge-user">
                                        <label class="inline">
                                            <input type="checkbox" id="notdownloaded" value="1" class="facetedSearch"
                                                trigger="click"> <span
                                                class="{{ config('other.font-awesome') }} fa-hdd text-red"></span>
                                            @lang('torrent.have-not-downloaded')
                                        </label>
                                    </span>
    
    
                                </div>
                            </div>
    
                            <div class="mx-0 mt-5 form-group fatten-me">
                                <label for="qty"
                                    class="mt-5 col-sm-1 label label-default fatten-me">@lang('common.quantity')</label>
                                <div class="col-sm-2">
                                    <select id="qty" name="qty" trigger="change" class="form-control facetedSearch">
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <span id="facetedHeader"></span>
            <div id="facetedSearch" type="list" class="mt-10" font-awesome="{{ config('other.font-awesome') }}">
                @include('torrent.results')
            </div>
            <div class="container-fluid well">
                <div class="text-center">
                    <strong>@lang('common.legend'):</strong>
                    <button class='btn btn-success btn-circle' type='button' data-toggle='tooltip' title=''
                        data-original-title='@lang(' torrent.currently-seeding')!'>
                        <i class='{{ config('other.font-awesome') }} fa-arrow-up'></i>
                    </button>
                    <button class='btn btn-warning btn-circle' type='button' data-toggle='tooltip' title=''
                        data-original-title='@lang(' torrent.currently-leeching')!'>
                        <i class='{{ config('other.font-awesome') }} fa-arrow-down'></i>
                    </button>
                    <button class='btn btn-info btn-circle' type='button' data-toggle='tooltip' title=''
                        data-original-title='@lang(' torrent.not-completed')!'>
                        <i class='{{ config('other.font-awesome') }} fa-spinner'></i>
                    </button>
                    <button class='btn btn-danger btn-circle' type='button' data-toggle='tooltip' title=''
                        data-original-title='@lang(' torrent.completed-not-seeding')!'>
                        <i class='{{ config('other.font-awesome') }} fa-thumbs-down'></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
