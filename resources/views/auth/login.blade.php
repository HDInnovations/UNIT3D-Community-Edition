<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
  <meta charset="UTF-8">
  <title>{{ trans('common.login') }} - {{ Config::get('other.title') }}</title>
  <!-- Meta -->
    <meta name="description" content="Login now on {{ Config::get('other.title') }}. Not yet member ? Signup in less than 30s.">
    <meta property="og:title" content="{{ Config::get('other.title') }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ url('/img/rlm.png') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- /Meta -->

  <link rel="shortcut icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
  <link rel="icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
  <link rel="stylesheet" href="{{ url('css/main/login.css') }}">
  <link rel="stylesheet" href="{{ url('css/vendor/vendor.min.css') }}" />
</head>

<body>
  <div class="wrapper fadeInDown">
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
    <a href="{{ route('login') }}"><h2 class="active">Sign In </h2></a>
    <a href="{{ route('register') }}"><h2 class="inactive underlineHover">Sign Up </h2></a>

    <!-- Icon -->
    <div class="fadeIn first">
      <img src="{{ url('/img/icon.svg') }}" id="icon" alt="User Icon" />
    </div>

    <!-- Login Form -->
    {{ Form::open(array('route' => 'login')) }}
    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
        <label for="username" class="col-md-4 control-label">Username</label>

        <div class="col-md-6">
            <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required>

            @if ($errors->has('username'))
            <br>
                <span class="help-block text-red">
                    <strong>{{ $errors->first('username') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <label for="password" class="col-md-4 control-label">Password</label>

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
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                </label>
            </div>
        </div>
    </div>
    <button type="submit" class="fadeIn fourth" id="login-button">Log In</button>
    {{ Form::close() }}

    <!-- Remind Passowrd -->
    <div id="formFooter">
      <a class="underlineHover" href="{{ route('password.request') }}">Forgot Password?</a>
    </div>
  </div>
</div>
<script type="text/javascript" src="{{ url('js/vendor/app.js') }}"></script>
{!! Toastr::message() !!}
</body>
</html>
