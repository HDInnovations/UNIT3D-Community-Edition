<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="UTF-8">
    <title>@lang('auth.lost-username') - {{ config('other.title') }}</title>
    <!-- Meta -->
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
    <svg viewBox="0 0 800 100" class="sitebanner">

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
        <a href="{{ route('login') }}"><h2 class="inactive underlineHover">@lang('auth.login')</h2></a>
        <a href="{{ route('registrationForm', ['code' => 'null']) }}"><h2 class="inactive underlineHover">@lang('auth.signup')</h2></a>

        <!-- Icon -->
        <div class="fadeIn first">
            <img src="{{ url('/img/icon.svg') }}" id="icon" alt="@lang('auth.user-icon')"/>
        </div>

        <!-- SignUp Form -->
        <form class="form-horizontal" role="form" method="POST" action="{{ route('username.email') }}">
            @csrf
            <label for="email"></label><input type="email" id="email" class="fadeIn third" name="email" placeholder="@lang('auth.email')"
                                              required autofocus>
            @if (config('captcha.enabled') == true)
                <div class="text-center">
                    <div class="g-recaptcha" data-sitekey="{{ config('captcha.sitekey') }}"></div>
                    @if ($errors->has('g-recaptcha-response'))
                        <span class="invalid-feedback" style="display: block;">
                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                        </span>
                    @endif
                </div>
            @endif	
            <button type="submit" class="fadeIn fourth">@lang('common.submit')</button>
        </form>

        <!-- Remind Passowrd -->
        <div id="formFooter">
            <a href="{{ route('password.request') }}"><h2
                        class="inactive underlineHover">@lang('auth.lost-password') </h2></a>
            <a href="{{ route('username.request') }}"><h2 class="active">@lang('auth.lost-username') </h2></a>
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
            icon: '{{ $key }}',
            title: '{{ Session::get($key) }}'
          })
        </script>
    @endif
@endforeach

@if (Session::has('errors'))
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
      Swal.fire({
        title: '<strong>Validation Error</strong>',
        icon: 'error',
        html: '{{ Session::get('errors') }}',
        showCloseButton: true,
      })
    </script>
@endif
</body>
</html>
