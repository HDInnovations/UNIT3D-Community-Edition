<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="UTF-8">
    <title>{{ __('auth.signup') }} - {{ config('other.title') }}</title>
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
    @if (config('other.invite-only') == true && ! request()->has('code'))
        <div class="alert alert-info">
            {{ __('auth.need-invite') }}
        </div>
    @endif
    <div id="formContent">
        <a href="{{ route('login') }}">
            <h2 class="inactive underlineHover">{{ __('auth.login') }} </h2>
        </a>
        <a href="{{ route('register', ['code' => request()->query('code')]) }}">
            <h2 class="active">{{ __('auth.signup') }} </h2>
        </a>

        <div class="fadeIn first reg-logo">
            <svg viewBox="0 0 500 140" class="sitebanner">
                <symbol id="s-text">
                    <text text-anchor="middle" x="50%" y="18%" dy=".6em">
                        {{ config('other.title') }}
                    </text>
                    <text text-anchor="middle" x="50%" y="18%" dy="1.6em">
                        {{ __('auth.registration') }}
                    </text>
                </symbol>
                <use xlink:href="#s-text" class="text"></use>
                <use xlink:href="#s-text" class="text"></use>
                <use xlink:href="#s-text" class="text"></use>
                <use xlink:href="#s-text" class="text"></use>
                <use xlink:href="#s-text" class="text"></use>
            </svg>
        </div>

        <form role="form" method="POST" action="{{ route('register', ['code' => request()->query('code')]) }}">
            @csrf
            <div>
                <label for="username" class="col-md-4 control-label">{{ __('auth.username') }}</label>
                <div class="col-md-6">
                    <input id="username" type="text" class="form-control" name="username"
                           value="{{ old('username') }}" required autofocus>
                </div>
            </div>

            <div>
                <label for="email" class="col-md-4 control-label">{{ __('auth.email') }}</label>
                <div class="col-md-6">
                    <input id="email" type="text" class="form-control" name="email"
                           value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <div>
                <label for="password" class="col-md-4 control-label">{{ __('auth.password') }}</label>
                <div class="col-md-6">
                    <input id="password" type="password" class="form-control" name="password" required>
                </div>
            </div>

            <div>
                <label for="password" class="col-md-4 control-label">{{ __('auth.password-confirmation') }}</label>
                <div class="col-md-6">
                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                </div>
            </div>

            @if (config('captcha.enabled') == true)
                @hiddencaptcha
            @endif
            <button type="submit" class="fadeIn fourth">{{ __('auth.signup') }}</button>
        </form>

        <div id="formFooter">
            <a href="{{ route('password.request') }}">
                <h2 class="inactive underlineHover">{{ __('auth.lost-password') }} </h2>
            </a>
            @if (config('email-white-blacklist.enabled') == 'block')
                <br>
                <a href="{{ route('public.email') }}">
                    <h2 class="inactive underlineHover">{{ __('common.email-blacklist') }} </h2>
                </a>
            @elseif (config('email-white-blacklist.enabled') == 'allow')
                <br>
                <a href="{{ route('public.email') }}">
                    <h2 class="inactive underlineHover">{{ __('common.email-whitelist') }} </h2>
                </a>
            @endif
        </div>
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
