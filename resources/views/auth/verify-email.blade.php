<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('auth.verify-email') }} - {{ config('other.title') }}</title>
    @section('meta')
        <meta name="description" content="{{ __('auth.login-now-on') }} {{ config('other.title') }} . {{ __('auth.not-a-member') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta property="og:title" content="{{ __('auth.login') }}">
        <meta property="og:site_name" content="{{ config('other.title') }}">
        <meta property="og:type" content="website">
        <meta property="og:image" content="{{ url('/img/og.png') }}">
        <meta property="og:description" content="{{ config('unit3d.powered-by') }}">
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:locale" content="{{ config('app.locale') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @show
    <link rel="shortcut icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ mix('css/main/login.css') }}" crossorigin="anonymous">
</head>
<body>
<div class="wrapper fadeInDown">
    <div id="formContent">
        <div class="fadeIn first">
            <svg viewBox="0 0 400 140" class="sitebanner" style="width: 100%">
                <symbol id="s-text">
                    <text text-anchor="middle" x="50%" y="28%" dy=".6em">
                        {{ config('other.title') }}
                    </text>
                </symbol>
                <symbol id="s-text-sm">
                    <text text-anchor="middle" x="50%" y="50%" dy="1.6em">
                        {{ __('auth.verify-email') }}
                    </text>
                </symbol>
                <use xlink:href="#s-text" class="text"></use>
                <use xlink:href="#s-text" class="text"></use>
                <use xlink:href="#s-text" class="text"></use>
                <use xlink:href="#s-text" class="text"></use>
                <use xlink:href="#s-text" class="text"></use>
                <use xlink:href="#s-text-sm" class="text-sm"></use>
                <use xlink:href="#s-text-sm" class="text-sm"></use>
                <use xlink:href="#s-text-sm" class="text-sm"></use>
                <use xlink:href="#s-text-sm" class="text-sm"></use>
                <use xlink:href="#s-text-sm" class="text-sm"></use>
            </svg>
        </div>
        <p>
            Almost done...
            <br>
            We'll send you an email shortly. Open it up to activate your account.
        </p>
        <form  method="post" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="fadeIn fourth">{{ __('auth.verify-email-button') }}</button>
        </form>
    </div>
</div>
</body>
</html>
