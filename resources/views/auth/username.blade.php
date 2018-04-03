<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>{{ trans('auth.lost-username') }} - {{ config('other.title') }}</title>
  <!-- Meta -->
  @section('meta')
    <meta name="description" content="{{ trans('auth.login-now-on') }} {{ config('other.title') }} . {{ trans('auth.not-a-member') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="{{ config('other.title') }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ url('/img/rlm.png') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
  @show
  <!-- /Meta -->

  <link rel="shortcut icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
  <link rel="icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
  <link rel="stylesheet" href="{{ url('css/vendor/toastr.min.css?v=01') }}" />
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

      <!-- Symbol -->
      <symbol id="s-text">
        <text text-anchor="middle"
              x="50%" y="50%" dy=".35em">
          {{ config('other.title') }}
        </text>
      </symbol>

      <!-- Duplicate symbols -->
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
    <!-- Tabs Titles -->
    <a href="{{ route('login') }}"><h2 class="inactive underlineHover">{{ trans('auth.login') }}</h2></a>
    <a href="{{ route('register') }}"><h2 class="inactive underlineHover">{{ trans('auth.signup') }}</h2></a>

    <!-- Icon -->
    <div class="fadeIn first">
      <img src="{{ url('/img/icon.svg') }}" id="icon" alt="{{ trans('auth.user-icon') }}" />
    </div>

    <!-- SignUp Form -->
    <form class="form-horizontal" role="form" method="POST" action="{{ route('username.email') }}">
      {{ csrf_field() }}
      <input type="email" id="email" class="fadeIn third" name="email" placeholder="{{ trans('auth.email') }}" required autofocus>
      @if ($errors->has('email'))
        <span class="help-block">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
      @endif
      <button type="submit" class="fadeIn fourth">{{ trans('common.submit') }}</button>
    </form>

    <!-- Remind Passowrd -->
    <div id="formFooter">
        <a href="{{ route('password.request') }}"><h2 class="inactive underlineHover">{{ trans('auth.lost-password') }} </h2></a>
        <a href="{{ route('username.request') }}"><h2 class="active">{{ trans('auth.lost-username') }} </h2></a>
    </div>

  </div>
</div>
<script type="text/javascript" src="{{ url('js/vendor/app.js?v=04') }}"></script>
{!! Toastr::message() !!}
</body>
</html>
