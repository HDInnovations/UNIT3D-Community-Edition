<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="UTF-8">
    <title>@lang('auth.signup') - {{ config('other.title') }}</title>
    @section('meta')
        <meta name="description"
              content="@lang('auth.login-now-on') {{ config('other.title') }} . @lang('auth.not-a-member')">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta property="og:title" content="@lang('auth.login')">
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
    <link rel="stylesheet" href="{{ mix('css/main/login.css') }}" integrity="{{ Sri::hash('css/main/login.css') }}" crossorigin="anonymous">
</head>

<body>
<div class="wrapper fadeInDown">
    @if (config('other.invite-only') == true && !$code)
        <div class="alert alert-info">
            @lang('auth.need-invite')
        </div>
    @endif
    <svg viewBox="0 0 800 100" class="sitebanner">
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
        <a href="{{ route('login') }}"><h2 class="inactive underlineHover">@lang('auth.login') </h2></a>
        <a href="{{ route('registrationForm', ['code' => $code]) }}"><h2 class="active">@lang('auth.signup') </h2></a>

        <div class="fadeIn first">
            <img src="{{ url('/img/icon.svg') }}" id="icon" alt="@lang('auth.user-icon')"/>
        </div>

        <form role="form" method="POST" action="{{ route('register', ['code' => $code]) }}">
            @csrf
            <label for="username"></label><input type="text" id="username" class="fadeIn second" name="username"
                                                 placeholder="@lang('auth.username')" required autofocus>
            <label for="email"></label><input type="email" id="email" class="fadeIn third" name="email" placeholder="@lang('auth.email')"
                                              required>
            <label for="password"></label><input type="password" id="password" class="fadeIn third" name="password"
                                                 placeholder="@lang('auth.password')" required>
            @if (config('captcha.enabled') == true)
                <div class="text-center">
                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <div class="g-recaptcha" data-sitekey="{{ config('captcha.sitekey') }}"></div>
                            @if ($errors->has('g-recaptcha-response'))
                                <span class="invalid-feedback" style="display: block;">
                                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            <button type="submit" class="fadeIn fourth">@lang('auth.signup')</button>
        </form>

        <div id="formFooter">
            <a href="{{ route('password.request') }}"><h2
                        class="inactive underlineHover">@lang('auth.lost-password') </h2></a>
            <a href="{{ route('username.request') }}"><h2
                        class="inactive underlineHover">@lang('auth.lost-username') </h2></a>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ mix('js/app.js') }}" integrity="{{ Sri::hash('js/app.js') }}" crossorigin="anonymous"></script>
@if (config('captcha.enabled') == true)
    <script type="text/javascript" src="https://www.google.com/recaptcha/api.js"></script>
@endif
@foreach (['warning', 'success', 'info'] as $key)
    @if (Session::has($key))
        <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
          const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
          });

          Toast.fire({
            type: '{{ $key }}',
            title: '{{ Session::get($key) }}'
          })
        </script>
    @endif
@endforeach

@if (Session::has('errors'))
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
      Swal.fire({
        title: '<strong>Validation Error</strong>',
        type: 'error',
        html: '{{ Session::get('errors') }}',
        showCloseButton: true,
      })
    </script>
@endif

</body>
</html>
