<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>{{ trans('auth.lost-password') }} - {{ Config::get('other.title') }}</title>
  <!-- Meta -->
  @section('meta')
    <meta name="description" content="Login now on {{ Config::get('other.title') }}. Not yet member ? Signup in less than 30s.">
    <meta property="og:title" content="{{ Config::get('other.title') }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ url('/img/rlm.png') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
  @show
  <!-- /Meta -->

  <link rel="shortcut icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
  <link rel="icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
  <link rel="stylesheet" href="{{ url('css/main/login.css') }}">
  <link rel="stylesheet" href="{{ url('css/vendor/vendor.min.css') }}" />
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
          {{ Config::get('other.title') }}
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
    <a href="{{ route('login') }}"><h2 class="inactive underlineHover">{{ trans('auth.login') }} </h2></a>
    <a href="{{ route('register') }}"><h2 class="inactive underlineHover">{{ trans('auth.login') }} </h2></a>
    <h2 class="active">{{ trans('auth.lost-password') }} </h2>
    <!-- Icon -->
    <div class="fadeIn first">
      <img src="{{ url('/img/icon.svg') }}" id="icon" alt="User Icon" />
    </div>

    <!-- SignUp Form -->
    <form class="form-horizontal" role="form" method="POST" action="{{ route('password.request') }}">
        {{ csrf_field() }}
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="row">
            <div class="form-group">
                <input type="text" id="email" class="fadeIn third" name="email" placeholder="email">
            </div>
            <div class="form-group">
              <input type="password" id="password" name="password" class="form-control" placeholder="{{ trans('common.password') }}">
            </div>
            <div class="form-group">
              <input type="password" id="password-confirm" name="password_confirmation" class="form-control" placeholder="{{ trans('common.password') }} confirmation">
            </div>
            <div class="col s6">
                <button type="submit" class="btn waves-effect waves-light blue right">{{ trans('common.submit') }}</button>
            </div>
          </form>

    <div id="formFooter">

    </div>

  </div>
</div>
<script type="text/javascript" src="{{ url('js/vendor/app.js') }}"></script>
{!! Toastr::message() !!}
</body>
</html>
