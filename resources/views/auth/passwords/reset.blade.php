<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="UTF-8">
    <title>@lang('auth.lost-password') - {{ config('other.title') }}</title>
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
        <a href="{{ route('registrationForm', ['code' => 'null']) }}"><h2 class="inactive underlineHover">@lang('auth.signup') </h2></a>

        <div class="fadeIn first">
            <img src="{{ url('/img/icon.svg') }}" id="icon" alt="@lang('auth.user-icon')"/>
        </div>

        <form class="form-horizontal" role="form" method="POST" action="{{ route('password.request') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="row">
                <div class="form-group">
                    <label for="email"></label><input type="email" id="email" class="fadeIn third" name="email"
                                                      placeholder="@lang('common.email')" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password"></label><input type="password" id="password" name="password" class="form-control"
                                                         placeholder="@lang('common.password')" required>
                </div>
                <div class="form-group">
                    <label for="password-confirm"></label><input type="password" id="password-confirm" name="password_confirmation" class="form-control"
                                                                 placeholder="@lang('common.password') confirmation" required>
                </div>
                <div class="col s6">
                    <button type="submit"
                            class="btn waves-effect waves-light blue right">@lang('common.submit')</button>
                </div>
            </div>
        </form>

        <div id="formFooter">
            <a href="{{ route('password.request') }}"><h2 class="active">@lang('auth.lost-password') </h2></a>
            <a href="{{ route('username.request') }}"><h2 class="inactive underlineHover">@lang('auth.lost-username') </h2></a>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ mix('js/app.js') }}" integrity="{{ Sri::hash('js/app.js') }}" crossorigin="anonymous"></script>
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
