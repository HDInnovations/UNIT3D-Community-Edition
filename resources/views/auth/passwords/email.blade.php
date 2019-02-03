<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="UTF-8">
    <title>@lang('auth.lost-password') - {{ config('other.title') }}</title>
    @section('meta')
        <meta name="description"
              content="@lang('auth.login-now-on') {{ config('other.title') }} . @lang('auth.not-a-member')">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta property="og:title" content="{{ config('other.title') }}">
        <meta property="og:type" content="website">
        <meta property="og:image" content="{{ url('/img/rlm.png') }}">
        <meta property="og:url" content="{{ url('/') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @show
    <link rel="shortcut icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ url('css/main/login.css?v=02') }}">
</head>

<body>
<div class="wrapper fadeInDown">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <svg viewBox="0 0 1320 100">
        <symbol id="s-text">
            <text text-anchor="middle"
                  x="50%" y="50%" dy=".35em">
                {{ config('other.title') }}
            </text>
        </symbol>
        <use xlink:href="#s-text" class="text"
        ></use>
        <use xlink:href="#s-text" class="text"
        ></use>
        <use xlink:href="#s-text" class="text"
        ></use>
        <use xlink:href="#s-text" class="text"
        ></use>
        <use xlink:href="#s-text" class="text"
        ></use>
    </svg>

    <div id="formContent">
        <a href="{{ route('login') }}"><h2 class="inactive underlineHover">@lang('auth.login')</h2></a>
        <a href="{{ route('registrationForm', ['code' => 'null']) }}"><h2 class="inactive underlineHover">@lang('auth.signup')</h2></a>

        <div class="fadeIn first">
            <img src="{{ url('/img/icon.svg') }}" id="icon" alt="@lang('auth.user-icon')"/>
        </div>

        <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">
            @csrf
            <input type="email" id="email" class="fadeIn third" name="email" placeholder="email" required autofocus>
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
            <button type="submit" class="fadeIn fourth">@lang('common.submit')</button>
        </form>

        <div id="formFooter">
            <a href="{{ route('password.request') }}"><h2 class="active">@lang('auth.lost-password') </h2></a>
            <a href="{{ route('username.request') }}"><h2
                        class="inactive underlineHover">@lang('auth.lost-username') </h2></a>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ url('js/vendor/app.js?v=04') }}"></script>
{!! Toastr::message() !!}

</body>
</html>
