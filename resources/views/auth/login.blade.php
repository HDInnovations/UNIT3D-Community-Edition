<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="UTF-8">
    <title>{{ __('auth.login') }} - {{ config('other.title') }}</title>
    @section('meta')
        <meta name="description"
              content="{{ __('auth.login-now-on') }} {{ config('other.title') }} . {{ __('auth.not-a-member') }}">
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
<!-- Dont Not Change! For Jackett Support -->
<div class="Jackett" style="display:none;">{{ config('unit3d.powered-by') }}</div>
<!-- Dont Not Change! For Jackett Support -->

@if ($errors->any())
    <div id="ERROR_COPY" style="display: none;">
        @foreach ($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
    </div>
@endif
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
            <h2 class="active">{{ __('auth.login') }} </h2>
        </a>
        <a href="{{ route('registrationForm', ['code' => 'null']) }}">
            <h2 class="inactive underlineHover">{{ __('auth.signup') }} </h2>
        </a>

        <div class="fadeIn first">
            <img src="{{ url('/img/icon.svg') }}" id="icon" alt="{{ __('auth.user-icon') }}"/>
        </div>

        <form role="form" method="POST" action="{{ route('login') }}">
            @csrf
            <div>
                <label for="username" class="col-md-4 control-label">{{ __('auth.username') }}</label>
                <div class="col-md-6">
                    <input id="username" type="text" class="form-control" name="username"
                           value="{{ old('username') }}" required autofocus>
                </div>
            </div>

            <div>
                <label for="password" class="col-md-4 control-label">{{ __('auth.password') }}</label>
                <div class="col-md-6">
                    <input id="password" type="password" class="form-control" name="password" required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            {{ __('auth.remember-me') }}
                        </label>
                    </div>
                </div>
            </div>

            @if (config('captcha.enabled') == true)
                @hiddencaptcha
            @endif

            <button type="submit" class="fadeIn fourth" id="login-button">{{ __('auth.login') }}</button>
        </form>

        <div id="formFooter">
            <a href="{{ route('password.request') }}">
                <h2 class="inactive underlineHover">{{ __('auth.lost-password') }} </h2>
            </a>
            <a href="{{ route('username.request') }}">
                <h2 class="inactive underlineHover">{{ __('auth.lost-username') }} </h2>
            </a>
        </div>
    </div>
</div>

<script src="{{ mix('js/app.js') }}" crossorigin="anonymous"></script>
@foreach (['warning', 'success', 'info'] as $key)
    @if (Session::has($key))
        <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
          const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
          })

          Toast.fire({
            icon: '{{ $key }}',
            title: '{{ Session::get($key) }}'
          })

        </script>
    @endif
@endforeach

@if (Session::has('errors'))
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
      Swal.fire({
        title: '<strong style=" color: rgb(17,17,17);">Error</strong>',
        icon: 'error',
        html: jQuery('#ERROR_COPY').html(),
        showCloseButton: true,
      })

    </script>
@endif

</body>

</html>
