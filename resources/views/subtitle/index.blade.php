@extends('layout.default')

@section('title')
    <title>@lang('common.subtitles') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('subtitles.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('common.subtitles')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="text-center">
            <h3 class="filter-title">@lang('common.subtitle') @lang('torrent.filters')</h3>
        </div>
            <form role="form" method="GET" action="SubtitleController@index">
                @csrf
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">@lang('common.name')</label>
                    <div class="col-sm-10">
                        <input class="form-control" placeholder="Search by Torrent Name" name="name" type="text" id="name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="language_id" class="col-sm-2 control-label">@lang('common.language')</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="language_id" name="language_id">
                            <option value="">--@lang('common.select') @lang('common.language')--</option>
                            @foreach ($media_languages as $media_language)
                                <option value="{{ $media_language->id }}">{{ $media_language->name }} ({{ $media_language->code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="category_id" class="col-sm-2 control-label">@lang('common.category')</label>
                    <div class="col-sm-10">
                        @foreach ($categories as $category)
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="{{ $category->id }}" value="{{ $category->id }}"
                                           class="category facetedSearch" trigger="click"> {{ $category->name }}
                                </label>
                            </span>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>

    <div class="container-fluid">
        <div class="block">
            <div id="result">
                @include('subtitle.results')
            </div>
        </div>
    </div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
        var xhr = new XMLHttpRequest();

        function faceted(page) {
            var csrf = "{{ csrf_token() }}";
            var name = $("#name").val();
            var categories = [];
            var language_id = $("#language_id").val();
            $(".category:checked").each(function() {
                categories.push($(this).val());
            });

            if (xhr !== 'undefined') {
                xhr.abort();
            }
            xhr = $.ajax({
                url: '/subtitles/filter',
                data: {
                    _token: csrf,
                    name: name,
                    categories: categories,
                    language_id: language_id,
                    page: page
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

        $(window).on("load", faceted())

        $("#name").keyup(function() {
            faceted();
        })

        $(".category").on("click", function() {
            faceted();
        });

        $("#language_id").on('change', function() {
            faceted();
        });

        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var page = url.split('page=')[1];
            window.history.pushState("", "", url);
            faceted(page);
        })

        $(document).ajaxComplete(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });

    </script>
@endsection