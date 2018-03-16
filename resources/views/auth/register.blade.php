<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>{{ trans('auth.signup') }} - {{ Config::get('other.title') }}</title>
  <!-- Meta -->
    <meta name="description" content="{{ trans('auth.login-now-on') }} {{ Config::get('other.title') }} . {{ trans('auth.not-a-member') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="{{ Config::get('other.title') }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ url('/img/rlm.png') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- /Meta -->

  <link rel="shortcut icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
  <link rel="icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
  <link rel="stylesheet" href="{{ url('css/vendor/toastr.min.css?v=01') }}" />
  <link rel="stylesheet" href="{{ url('css/main/login.css?v=02') }}">
</head>

<body>
  <div class="wrapper fadeInDown">
    @if (config('other.invite-only') == true && !$code)
      <div class="alert alert-info">
          {{ trans('auth.need-invite') }}
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
    <a href="{{ route('register') }}"><h2 class="active">{{ trans('auth.signup') }} </h2></a>

    <!-- Icon -->
    <div class="fadeIn first">
      <img src="{{ url('/img/icon.svg') }}" id="icon" alt="{{ trans('auth.user-icon') }}" />
    </div>

    <!-- SignUp Form -->
    {{ Form::open(array('route' => array('register', 'code' => $code))) }}
      <input type="text" id="username" class="fadeIn second" name="username" placeholder="{{ trans('auth.username') }}" required autofocus>
      @if ($errors->has('username'))
      <br>
          <span class="help-block text-red">
              <strong>{{ $errors->first('username') }}</strong>
          </span>
      @endif
      <input type="email" id="email" class="fadeIn third" name="email" placeholder="{{ trans('auth.email') }}" required>
      @if ($errors->has('email'))
      <br>
          <span class="help-block text-red">
              <strong>{{ $errors->first('email') }}</strong>
          </span>
      @endif
      <input type="password" id="password" class="fadeIn third" name="password" placeholder="{{ trans('auth.password') }}" required>
      @if ($errors->has('password'))
      <br>
          <span class="help-block text-red">
              <strong>{{ $errors->first('password') }}</strong>
          </span>
      @endif
      <button type="submit" class="fadeIn fourth">{{ trans('auth.signup') }}</button>
    {{ Form::close() }}

    <!-- Remind Passowrd -->
    <div id="formFooter">
        <a href="{{ route('password.request') }}"><h2 class="inactive underlineHover">{{ trans('auth.lost-password') }} </h2></a>
        <a href="{{ route('username.request') }}"><h2 class="inactive underlineHover">{{ trans('auth.lost-username') }} </h2></a>
    </div>

  </div>
</div>
<script type="text/javascript" src="{{ url('js/vendor/app.js?v=04') }}"></script>
{!! Toastr::message() !!}
</body>
</html>
