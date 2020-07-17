@extends('layout.default')

@section('title')
    <title>Requests - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('requests') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('request.requests')</span>
        </a>
    </li>
@endsection

@section('content')
    @if ($user->can_request == 0)
        <div class="container">
            <div class="jumbotron shadowed">
                <div class="container">
                    <h1 class="mt-5 text-center">
                        <i class="{{ config('other.font-awesome') }} fa-times text-danger"></i> @lang('request.no-privileges')
                    </h1>
                    <div class="separator"></div>
                    <p class="text-center">@lang('request.no-privileges-desc')!</p>
                </div>
            </div>
        </div>
    @else
        <div class="container box">
            <div class="well">
                <p class="lead text-orange text-center">{!! trans('request.no-refunds') !!}</p>
            </div>
            <div class="text-center">
                <h3 class="filter-title">@lang('torrent.filters')</h3>
            </div>
            <form role="form" method="GET" action="RequestController@requests"
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
                    <div class="col-sm-2">
                        <label for="igdb"></label><input type="text" class="form-control" id="igdb" placeholder="IGDB #">
                    </div>
                </div>
        
                <div class="form-group">
                    <label for="category" class="col-sm-1 label label-default">@lang('torrent.category')</label>
                    <div class="col-sm-10">
                        @foreach ($repository->categories() as $id => $category)
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" value="{{ $id }}" class="category"> {{ $category }}
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
                                    <input type="checkbox" value="{{ $id }}" class="type"> {{ $type }}
                                </label>
                            </span>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label for="resolution" class="col-sm-1 label label-default">@lang('torrent.resolution')</label>
                    <div class="col-sm-10">
                        @foreach ($repository->resolutions() as $id => $resolution)
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" value="{{ $id }}" class="resolution"> {{ $resolution }}
                                </label>
                            </span>
                        @endforeach
                    </div>
                </div>
        
                <div class="form-group">
                    <label for="type" class="col-sm-1 label label-default">@lang('common.extra')</label>
                    <div class="col-sm-10">
                        <span class="badge-user">
                            <label class="inline">
                                <input type="checkbox" id="unfilled" value="1">
                                <span class="{{ config('other.font-awesome') }} fa-times-circle text-blue"></span>
                                @lang('request.unfilled')
                            </label>
                        </span>
                        <span class="badge-user">
                            <label class="inline">
                                <input type="checkbox" id="claimed" value="1">
                                <span class="{{ config('other.font-awesome') }} fa-hand-paper text-blue"></span>
                                @lang('request.claimed')
                            </label>
                        </span>
                        <span class="badge-user">
                            <label class="inline">
                                <input type="checkbox" id="pending" value="1">
                                <span class="{{ config('other.font-awesome') }} fa-question-circle text-blue"></span>
                                @lang('request.pending')
                            </label>
                        </span>
                        <span class="badge-user">
                            <label class="inline">
                                <input type="checkbox" id="filled" value="1">
                                <span class="{{ config('other.font-awesome') }} fa-check-circle text-blue"></span>
                                @lang('request.filled')
                            </label>
                        </span>
                    </div>
        
                    <div class="col-sm-10">
                        <span class="badge-user">
                            <label class="inline">
                                <input type="checkbox" id="myrequests" value="{{ $user->id }}">
                                <span class="{{ config('other.font-awesome') }} fa-user text-blue"></span>
                                @lang('request.my-requests')
                            </label>
                        </span>
                        <span class="badge-user">
                            <label class="inline">
                                <input type="checkbox" id="myclaims" value="1">
                                <span class="{{ config('other.font-awesome') }} fa-user text-blue"></span> My claims
                            </label>
                        </span>
                        <span class="badge-user">
                            <label class="inline">
                                <input type="checkbox" id="myvoted" value="1">
                                <span class="{{ config('other.font-awesome') }} fa-user text-blue"></span> My voted
                            </label>
                        </span>
                        <span class="badge-user">
                            <label class="inline">
                                <input type="checkbox" id="myfiled" value="1">
                                <span class="{{ config('other.font-awesome') }} fa-user text-blue"></span> My filled
                            </label>
                        </span>
                    </div>
                </div>
            </form>
        
            <br>
            <br>
        
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
                <span class="badge-user" style="float: right;">
                    <strong>@lang('request.requests'):</strong> {{ $requests->total }} |
                    <strong>@lang('request.filled'):</strong> {{ $requests->filled }} |
                    <strong>@lang('request.unfilled'):</strong> {{ $requests->unfilled }} |
                    <strong>@lang('request.total-bounty'):</strong> {{ $bounties->total }} @lang('bon.bon') |
                    <strong>@lang('request.bounty-claimed'):</strong> {{ $bounties->claimed }} @lang('bon.bon') |
                    <strong>@lang('request.bounty-unclaimed'):</strong> {{ $bounties->unclaimed }} @lang('bon.bon')
                </span>
                <a href="{{ route('add_request') }}" role="button" data-toggle="tooltip"
                    data-original-title="@lang('request.add-request')!" class="btn btn btn-success">
                    @lang('request.add-request')
                </a>
                <div class="header gradient green">
                    <div class="inner_content">
                        <h1>
                            @lang('request.requests')
                        </h1>
                    </div>
                </div>
                <div id="result">
                    @include('requests.results')
                </div>
            </div>
        </div>
    @endif
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
            var igdb = $("#igdb").val();
            var categories = [];
            var types = [];
            var resolutions = [];
            var sorting = $("#sorting").val();
            var direction = $("#direction").val();
            var qty = $("#qty").val();
            var unfilled = (function() {
                if ($("#unfilled").is(":checked")) {
                    return $("#unfilled").val();
                }
            })();
            var claimed = (function() {
                if ($("#claimed").is(":checked")) {
                    return $("#claimed").val();
                }
            })();
            var pending = (function() {
                if ($("#pending").is(":checked")) {
                    return $("#pending").val();
                }
            })();
            var filled = (function() {
                if ($("#filled").is(":checked")) {
                    return $("#filled").val();
                }
            })();
            var myrequests = (function() {
                if ($("#myrequests").is(":checked")) {
                    return $("#myrequests").val();
                }
            })();
            var myclaims = (function() {
                if ($("#myclaims").is(":checked")) {
                    return $("#myclaims").val();
                }
            })();
            var myvoted = (function() {
                if ($("#myvoted").is(":checked")) {
                    return $("#myvoted").val();
                }
            })();
            var myfiled = (function() {
                if ($("#myfiled").is(":checked")) {
                    return $("#myfiled").val();
                }
            })();
            $(".category:checked").each(function() {
                categories.push($(this).val());
            });
            $(".type:checked").each(function() {
                types.push($(this).val());
            });
            $(".resolution:checked").each(function() {
                resolutions.push($(this).val());
            });
    
            if (xhr !== 'undefined') {
                xhr.abort();
            }
    
            xhr = $.ajax({
                url: '/requests/filter',
                data: {
                    _token: csrf,
                    search: search,
                    imdb: imdb,
                    tvdb: tvdb,
                    tmdb: tmdb,
                    mal: mal,
                    igdb: igdb,
                    categories: categories,
                    types: types,
                    resolutions: resolutions,
                    myrequests: myrequests,
                    myclaims: myclaims,
                    myvoted: myvoted,
                    myfiled: myfiled,
                    unfilled: unfilled,
                    claimed: claimed,
                    pending: pending,
                    filled: filled,
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
        $("#igdb").keyup(function() {
            faceted();
        })
    
    </script>
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
        $(".category,.type,.resolution").on("click", function() {
            faceted();
        });
    
    </script>
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
        $("#myrequests,#myclaims,#myvoted,#myfiled,#unfilled,#claimed,#pending,#filled").on("click", function() {
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
