<!DOCTYPE html>
<html class="no-js" lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <title>@yield('title') - {{ config('other.title') }}</title>

    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Error">
    <meta property="og:title" content="{{ config('other.title') }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ url('/img/og.png') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ url('css/app.css') }}"/>
</head>

<body>
<section class="container content" id="content-area" style="min-height: 344px;">
    <div class="jumbotron shadowed">
        <div class="container">
            @yield('container')

            <p class="text-center">
                <a href="{{ url('/') }}" role="button" class="btn btn-labeled btn-primary">
                    <i class="{{ config('other.font-awesome') }} fa-home"></i> Go Home
                </a>
            </p>
        </div>
    </div>
</section>
</body>

</html>
