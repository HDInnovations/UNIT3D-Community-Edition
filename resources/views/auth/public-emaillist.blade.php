<!DOCTYPE html>
<html class="no-js" lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <title>Email list - {{ config('other.title') }}</title>

    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Email list">
    <meta property="og:title" content="@lang('auth.email')">
    <meta property="og:site_name" content="{{ config('other.title') }}">
    <meta property="og:type" content="website">
    <meta property="og:description" content="{{ config('unit3d.powered-by') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:locale" content="{{ config('app.locale') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ mix('css/app.css') }}" integrity="{{ Sri::hash('css/app.css') }}"
        crossorigin="anonymous">
</head>
<body>
    <div class="container box">
        <div class="col-md-12 page">

            @if (config('email-white-blacklist.enabled') == null)
                <div class="alert alert-info" id="alert1">
                    <div class="text-center">
                        <span>
                            {{ config('other.title') }} @lang('common.email-list-notactive')
                        </span>
                    </div>
                </div>
            @else

                @if (config('email-white-blacklist.enabled') == 'allow')
                    <div class="header gradient green">
                        <div class="inner_content">
                            <div class="page-title">
                                <h1>{{ config('other.title') }} @lang('common.email-whitelist')</h1>
                            </div>
                        </div>
                    </div>
                @endif
                @if (config('email-white-blacklist.enabled') == 'block')
                    <div class="header gradient red">
                        <div class="inner_content">
                            <div class="page-title">
                                <h1>{{ config('other.title') }} @lang('common.email-blacklist')</h1>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="alert alert-info" id="alert1">
                    <div class="text-center">
                        @if (config('email-white-blacklist.enabled') == 'allow')
                            <span>
                                @lang('page.email-whitelist-desc', ['title' => config('other.title')])
                            </span>
                        @endif
                        @if (config('email-white-blacklist.enabled') == 'block')
                            <span>
                                @lang('page.email-blacklist-desc', ['title' => config('other.title')])
                            </span>
                        @endif
                    </div>
                </div>
            @endif

            @if (config('email-white-blacklist.enabled') == 'allow')
                <div class="row black-list">
                    @foreach ($whitelist as $w)
                        <div class="col-xs-6 col-sm-4 col-md-3">
                            <div class="text-center black-item">
                                <span class="text-bold">{{ $w }}</span>
                                <h4>@lang('page.whitelist-emaildomain')</h4>
                                <i class="fal fa-check text-green black-icon"></i>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if (config('email-white-blacklist.enabled') == 'block')
                <div class="row black-list">
                    @foreach ($blacklist as $b)
                        <div class="col-xs-6 col-sm-4 col-md-3">
                            <div class="text-center black-item">
                                <span class="text-bold">{{ $b }}</span>
                                <h4>@lang('page.blacklist-emaildomain')</h4>
                                <i class="fal fa-ban text-red black-icon"></i>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</body>
</html>
