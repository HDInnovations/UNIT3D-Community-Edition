@extends('layout.default')

@section('title')
    <title>@lang('graveyard.graveyard') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('graveyard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('graveyard.graveyard')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="text-center">
            <h3 class="filter-title">@lang('torrent.filters')</h3>
        </div>
        <form role="form" method="GET" action="GraveyardController@index"
            class="form-horizontal form-condensed form-torrent-search form-bordered">
            @csrf
            <div class="form-group">
                <label for="name" class="col-sm-1 label label-default">@lang('torrent.name')</label>
                <div class="col-sm-9">
                    <label for="search"></label><input type="text" class="form-control" id="search"
                        placeholder="@lang('torrent.name')">
                </div>
            </div>
    
            <div class="form-group">
                <label for="imdb" class="col-sm-1 label label-default">ID</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="imdb" placeholder="IMDB #">
                </div>
                <div class="col-sm-2">
                    <label for="tvdb"></label><input type="text" class="form-control" id="tvdb" placeholder="TVDB #">
                </div>
                <div class="col-sm-2">
                    <label for="tmdb"></label><input type="text" class="form-control" id="tmdb" placeholder="TMDB #">
                </div>
                <div class="col-sm-2">
                    <label for="mal"></label><input type="text" class="form-control" id="mal" placeholder="MAL #">
                </div>
            </div>
    
            <div class="form-group">
                <label for="category" class="col-sm-1 label label-default">@lang('torrent.category')</label>
                <div class="col-sm-10">
                    @foreach ($repository->categories() as $id => $category)
                        <span class="badge-user">
                            <label class="inline">
                                <input type="checkbox" id="{{ $category }}" value="{{ $id }}" class="category"> {{ $category }}
                            </label>
                        </span>
                    @endforeach
                </div>
            </div>
    
            <div class="form-group">
                <label for="type" class="col-sm-1 label label-default">@lang('torrent.type')</label>
                <div class="col-sm-10">
                    @foreach ($repository->types() as $id => $type)
                        <span class="badge-user">
                            <label class="inline">
                                <input type="checkbox" id="{{ $type }}" value="{{ $id }}" class="type"> {{ $type }}
                            </label>
                        </span>
                    @endforeach
                </div>
            </div>
        </form>
    
        <hr>
        <div class="form-horizontal">
            <div class="form-group">
                <label class="control-label col-sm-2" for="sorting">@lang('common.sort')</label>
                <div class="col-sm-2">
                    <select id="sorting" name="sorting" class="form-control">
                        @foreach ($repository->sorting() as $value => $sort)
                            <option value="{{ $value }}">{{ $sort }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="direction"></label><select id="direction" name="direction" class="form-control">
                        @foreach ($repository->direction() as $value => $dir)
                            <option value="{{ $value }}">{{ $dir }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="control-label col-sm-2" for="qty">@lang('common.quantity')</label>
                <div class="col-sm-2">
                    <select id="qty" name="qty" class="form-control">
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="block">
            <div class="header gradient silver">
                <div class="inner_content">
                    <h1>@lang('graveyard.graveyard')
                        <span class="text-red">({{ $deadcount }} @lang('graveyard.dead')!)</span>
                    </h1>
                </div>
            </div>
            <div id="result">
                @include('graveyard.results')
            </div>
        </div>
    </div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
        var xhr = new XMLHttpRequest();
    
        function faceted(page) {
            var csrf = "{{ csrf_token() }}";
            var search = $("#search").val();
            var imdb = $("#imdb").val();
            var tvdb = $("#tvdb").val();
            var tmdb = $("#tmdb").val();
            var mal = $("#mal").val();
            var categories = [];
            var types = [];
            var sorting = $("#sorting").val();
            var direction = $("#direction").val();
            var qty = $("#qty").val();
            $(".category:checked").each(function() {
                categories.push($(this).val());
            });
            $(".type:checked").each(function() {
                types.push($(this).val());
            });
    
            if (xhr !== 'undefined') {
                xhr.abort();
            }
            xhr = $.ajax({
                url: '/graveyard/filter',
                data: {
                    _token: csrf,
                    search: search,
                    imdb: imdb,
                    tvdb: tvdb,
                    tmdb: tmdb,
                    mal: mal,
                    categories: categories,
                    types: types,
                    sorting: sorting,
                    direction: direction,
                    page: page,
                    qty: qty
                },
                type: 'get',
                beforeSend: function() {
                    $("#result").html('<i class="{{ config('other.font-awesome') }} fa-spinner fa-spin fa-3x fa-fw"></i>')
                }
            }).done(function(e) {
                $data = $(e);
                $("#result").html($data);
            });
        }
    
    </script>
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
        $(window).on("load", faceted())
    
    </script>
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
        $("#search").keyup(function() {
            faceted();
        })
    
    </script>
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
        $("#imdb").keyup(function() {
            faceted();
        })
    
    </script>
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
        $("#tvdb").keyup(function() {
            faceted();
        })
    
    </script>
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
        $("#tmdb").keyup(function() {
            faceted();
        })
    
    </script>
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
        $("#mal").keyup(function() {
            faceted();
        })
    
    </script>
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
        $(".category,.type").on("click", function() {
            faceted();
        });
    
    </script>
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
        $("#sorting,#direction,#qty").on('change', function() {
            faceted();
        });
    
    </script>
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var page = url.split('page=')[1];
            window.history.pushState("", "", url);
            faceted(page);
        })
    
    </script>
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
        $(document).ajaxComplete(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    
    </script>
@endsection
