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
<div id="facetedFilters" style="display: none;">
    <div class="box">
        <div class="container well search mt-5">
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
        <hr style="padding: 5px 0 !important; margin: 5px 0 !important;">
    </div>
</div>