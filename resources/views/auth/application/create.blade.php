<!DOCTYPE html>
<html class="no-js" lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <title>Application - {{ config('other.title') }}</title>

    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Application">
    <meta property="og:title" content="{{ __('auth.login') }}">
    <meta property="og:site_name" content="{{ config('other.title') }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ url('/img/og.png') }}">
    <meta property="og:description" content="{{ config('unit3d.powered-by') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:locale" content="{{ config('app.locale') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ mix('css/app.css') }}" crossorigin="anonymous">
</head>

<body>
@if ($errors->any())
    <div id="ERROR_COPY" style="display: none;">
        @foreach ($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
    </div>
@endif
<br>
@if(config('other.application_signups') == true)
    <div class="container">
        <div class="block">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-chat shoutbox">
                        <div class="header gradient blue">
                            <div class="inner_content">
                                <h1>
                                    {{ __('auth.application') }}
                                </h1>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="alert alert-info text-center">
                                {{ config('other.title') }} {{ __('auth.appl-intro') }}
                            </div>
                            <hr>

                            <form role="form" method="POST" action="{{ route('application.store') }}">
                                @csrf

                                <label for="type" class="control-label">{{ __('auth.are-you') }}</label>
                                <br>
                                <div class="radio-inline">
                                    <label>
                                        <input type="radio" name="type" value="New To The Game" checked>
                                        {{ __('auth.newbie') }}
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                        <input type="radio" name="type" value="Experienced With Private Trackers">
                                        {{ __('auth.veteran') }}
                                    </label>
                                </div>

                                <hr>

                                <div class="form-group">
                                    <label for="email" class="control-label">{{ __('auth.email') }}</label>
                                    <input id="email" type="email" class="form-control" name="email" required>
                                    @if (config('email-white-blacklist.enabled') == 'block')
                                        <br>
                                        <a target="_blank" rel="noopener noreferrer" href="{{ route('public.email') }}">
                                            {{ __('common.email-blacklist') }}
                                        </a>
                                    @elseif (config('email-white-blacklist.enabled') == 'allow')
                                        <br>
                                        <a target="_blank" rel="noopener noreferrer" href="{{ route('public.email') }}">
                                            {{ __('common.email-whitelist') }}
                                        </a>
                                    @endif
                                </div>

                                <hr>

                                <div class="form-group">
                                    <strong>{{ __('auth.proof-image-title') }}</strong><br>
                                    {{ __('auth.proof-min') }}<br>
                                    <label for="image1">{{ __('auth.proof-image') }} 1:</label>
                                    <label>
                                        <input type="text" name="images[]" class="form-control" value="">
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="image2">{{ __('auth.proof-image') }} 2:</label>
                                    <label>
                                        <input type="text" name="images[]" class="form-control" value="">
                                    </label>
                                </div>

                                <div class="more-images"></div>

                                <div class="form-group">
                                    <button id="addImg" class="btn btn-primary">{{ __('auth.add-image') }}</button>
                                    <button id="delImg" class="btn btn-primary">{{ __('auth.delete-image') }}</button>
                                </div>

                                <hr>

                                <div class="form-group">
                                    <strong>{{ __('auth.proof-profile-title') }}</strong><br>
                                    {{ __('auth.proof-min') }}<br>
                                    <label for="link1">{{ __('auth.proof-profile') }} 1:</label>
                                    <label>
                                        <input type="text" name="links[]" class="form-control" value="">
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="link2">{{ __('auth.proof-profile') }} 2:</label>
                                    <label>
                                        <input type="text" name="links[]" class="form-control" value="">
                                    </label>
                                </div>

                                <div class="more-links"></div>

                                <div class="form-group">
                                    <button id="addLink" class="btn btn-primary">{{ __('auth.add-profile') }}</button>
                                    <button id="delLink" class="btn btn-primary">{{ __('auth.delete-profile') }}</button>
                                </div>

                                <hr>

                                <div class="form-group">
                                    <label for="referrer">{{ __('auth.appl-reason',['sitename' =>
                                            config('other.title')]) }}<span class="badge-extra">BBCode
                                                {{ __('common.is-allowed') }}</span></label>
                                    <label>
                                        <textarea name="referrer" cols="30" rows="10" maxlength="500"
                                                  class="form-control"></textarea>
                                    </label>
                                </div>

                                @if (config('captcha.enabled') == true)
                                    @hiddencaptcha
                                @endif

                                <div class="form-group">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('common.submit') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>

    <script src="{{ mix('js/app.js') }}" crossorigin="anonymous"></script>

    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
      let images = 2

      $('#addImg').on('click', function (e) {
        e.preventDefault()
        images += 1
        const imageHTML = '<div class="form-group extra-image"><label for="image' + images +
          '">Proof Image URL ' + images +
          ':</label><input type="text" name="images[]" class="form-control" value="" required></div>'
        $('.more-images').append(imageHTML)
      })

      $('#delImg').on('click', function (e) {
        e.preventDefault()
        images = (images > 2) ? images - 1 : 2
        $('.extra-image').last().remove()
      })

    </script>

    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
      let links = 2

      $('#addLink').on('click', function (e) {
        e.preventDefault()
        links += 1
        const linkHTML = '<div class="form-group extra-link"><label for="link' + links +
          '">Profile Link URL ' + links +
          ':</label><input type="text" name="links[]" class="form-control" value="" required></div>'
        $('.more-links').append(linkHTML)
      })

      $('#delLink').on('click', function (e) {
        e.preventDefault()
        links = (links > 2) ? links - 1 : 2
        $('.extra-link').last().remove()
      })

    </script>

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
            html: jQuery('#ERROR_COPY').html(),
            showCloseButton: true,
          })

        </script>
    @endif

@else
    <div class="container">
        <div class="jumbotron shadowed">
            <div class="container">
                <h1 class="mt-5 text-center">
                    <i class="{{ config('other.font-awesome') }} fa-times text-danger"></i>{{ __('auth.appl-closed') }}
                </h1>
                <div class="separator"></div>
                <p class="text-center">{{ __('auth.check-later') }}</p>
            </div>
        </div>
    </div>
@endif
</body>

</html>
