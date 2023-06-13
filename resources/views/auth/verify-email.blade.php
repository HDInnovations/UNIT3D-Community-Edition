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
    <svg viewBox="0 0 800 100" class="sitebanner">
        <symbol id="s-text">
            <text text-anchor="middle" x="50%" y="50%" dy=".35em">
                {{ config('other.title') }}
            </text>
        </symbol>
        <use xlink:href="#s-text" class="text"></use>
        <use xlink:href="#s-text" class="text"></use>
        <use xlink:href="#s-text" class="text"></use>
        <use xlink:href="#s-text" class="text"></use>
        <use xlink:href="#s-text" class="text"></use>
    </svg>
    <div id="formContent">
        <a href="{{ route('login') }}">
            <h2 class="inactive underlineHover">{{ __('auth.login') }} </h2>
        </a>
        <a href="{{ route('register', ['code' => request()->query('code')]) }}">
            <h2 class="active">{{ __('auth.signup') }} </h2>
        </a>
        <div class="fadeIn first">
            <img src="{{ url('/img/icon.svg') }}" id="icon" alt="{{ __('auth.user-icon') }}"/>
        </div>
        <p>
            Almost done...
            <br>
            We'll send you an email shortly. Open it up to activate your account.
        </p>
        <div id="formFooter">
            <a href="{{ route('verification.send') }}">
                <h2 class="active">Resend confirmation email</h2>
            </a>
        </div>
    </div>
</div>
</body>
</html>
