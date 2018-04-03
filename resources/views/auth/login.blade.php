<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
  <meta charset="UTF-8">
  <title>{{ trans('auth.login') }} - {{ config('other.title') }}</title>
  <!-- Meta -->
    <meta name="description" content="{{ trans('auth.login-now-on') }} {{ config('other.title') }} . {{ trans('auth.not-a-member') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="{{ config('other.title') }}">
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
    <a href="{{ route('login') }}"><h2 class="active">{{ trans('auth.login') }} </h2></a>
    <a href="{{ route('register') }}"><h2 class="inactive underlineHover">{{ trans('auth.signup') }} </h2></a>

    <!-- Icon -->
    <div class="fadeIn first">
      <img src="{{ url('/img/icon.svg') }}" id="icon" alt="{{ trans('auth.user-icon') }}" />
    </div>

    <!-- Login Form -->
    <form role="form" method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}
    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
        <label for="username" class="col-md-4 control-label">{{ trans('auth.username') }}</label>

        <div class="col-md-6">
            <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>
            @if ($errors->has('username'))
            <br>
                <span class="help-block text-red">
                    <strong>{{ $errors->first('username') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <label for="password" class="col-md-4 control-label">{{ trans('auth.password') }}</label>

        <div class="col-md-6">
            <input id="password" type="password" class="form-control" name="password" required>
            @if ($errors->has('password'))
            <br>
                <span class="help-block text-red">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ trans('auth.remember-me') }}
                </label>
            </div>
        </div>
    </div>
    <button type="submit" class="fadeIn fourth" id="login-button">{{ trans('auth.login') }}</button>
    </form>

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
