<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="UTF-8">
    <title>{{ __('auth.lost-password') }} - {{ config('other.title') }}</title>
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
            <h2 class="inactive underlineHover">{{ __('auth.login') }} </h2>
        </a>
        <a href="{{ route('registrationForm', ['code' => 'null']) }}">
            <h2 class="inactive underlineHover">{{ __('auth.signup') }} </h2>
        </a>

        <div class="fadeIn first">
            <img src="{{ url('/img/icon.svg') }}" id="icon" alt="{{ __('auth.user-icon') }}"/>
        </div>

        <form class="form-horizontal" role="form" method="POST" action="{{ route('password.request') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="row">
                <div class="form-group">
                    <label for="email"></label><input type="email" id="email" class="fadeIn third" name="email"
                                                      placeholder="{{ __('common.email') }}" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password"></label><input type="password" id="password" name="password"
                                                         class="form-control" placeholder="{{ __('common.password') }}"
                                                         required>
                </div>
                <div class="form-group">
                    <label for="password-confirm"></label><input type="password" id="password-confirm"
                                                                 name="password_confirmation" class="form-control"
                                                                 placeholder="{{ __('common.password') }} confirmation"
                                                                 required>
                </div>
                <div class="col s6">
                    <button type="submit"
                            class="btn waves-effect waves-light blue right">{{ __('common.submit') }}</button>
                </div>
            </div>
        </form>

        <div id="formFooter">
            <a href="{{ route('password.request') }}">
                <h2 class="active">{{ __('auth.lost-password') }} </h2>
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
