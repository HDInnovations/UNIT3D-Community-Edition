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
    <div id="formContent">
        <div class="fadeIn first">
            <svg viewBox="0 0 400 140" class="sitebanner" style="width: 100%">
                <symbol id="s-text">
                    <text text-anchor="middle" x="50%" y="28%" dy=".6em">
                        {{ config('other.title') }}
                    </text>
                </symbol>
                <symbol id="s-text-sm">
                    <text text-anchor="middle" x="50%" y="50%" dy="1.6em">
                        {{ __('auth.reset-password') }}
                    </text>
                </symbol>
                <use xlink:href="#s-text" class="text"></use>
                <use xlink:href="#s-text" class="text"></use>
                <use xlink:href="#s-text" class="text"></use>
                <use xlink:href="#s-text" class="text"></use>
                <use xlink:href="#s-text" class="text"></use>
                <use xlink:href="#s-text-sm" class="text-sm"></use>
                <use xlink:href="#s-text-sm" class="text-sm"></use>
                <use xlink:href="#s-text-sm" class="text-sm"></use>
                <use xlink:href="#s-text-sm" class="text-sm"></use>
                <use xlink:href="#s-text-sm" class="text-sm"></use>
            </svg>
        </div>
        <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">
            @csrf
            <div>
                <label for="email" class="col-md-4 control-label">{{ __('auth.email') }}</label>
                <div class="col-md-6">
                    <input id="email" type="text" class="form-control" name="email"
                           value="{{ old('email') }}" required autofocus>
                </div>
            </div>
            @if (config('captcha.enabled') == true)
                @hiddencaptcha
            @endif

            @if (session('status'))
                <div class="form__hint">
                    {{ session('status') }}
                </div>
            @endif

            <button type="submit" class="fadeIn fourth">{{ __('common.submit') }}</button>
        </form>
    </div>
</div>

<script src="{{ mix('js/app.js') }}" crossorigin="anonymous"></script>

@foreach (['warning', 'success', 'info'] as $key)
    @if (Session::has($key))
        <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
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
    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
      Swal.fire({
        title: '<strong style=" color: rgb(17,17,17);">Error</strong>',
        icon: 'error',
        html: document.getElementById('ERROR_COPY').innerHTML,
        showCloseButton: true,
      })

    </script>
@endif

</body>

</html>
