<!DOCTYPE html>
<html class="no-js" lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <title>Application - {{ config('other.title') }}</title>

    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Application">
    <meta property="og:title" content="@lang('auth.login')">
    <meta property="og:site_name" content="{{ config('other.title') }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ url('/img/og.png') }}">
    <meta property="og:description" content="{{ config('unit3d.powered-by') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:locale" content="{{ config('app.locale') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ mix('css/app.css') }}" integrity="{{ Sri::hash('css/app.css') }}"
        crossorigin="anonymous">
</head>

<body>
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
                                        Application
                                    </h1>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="alert alert-info text-center">
                                    {{ config('other.title') }} @lang('auth.appl-intro')
                                </div>
                                <hr>
    
                                <form role="form" method="POST" action="{{ route('application.store') }}">
                                    @csrf
    
                                    <label for="type" class="control-label">@lang('auth.are-you')</label>
                                    <br>
                                    <div class="radio-inline">
                                        <label>
                                            <input type="radio" name="type" value="New To The Game" checked>
                                            @lang('auth.newbie')
                                        </label>
                                    </div>
                                    <div class="radio-inline">
                                        <label>
                                            <input type="radio" name="type" value="Experianced With Private Trackers">
                                            @lang('auth.veteran')
                                        </label>
                                    </div>
    
                                    <hr>
    
                                    <div class="form-group">
                                        <label for="email" class="control-label">@lang('auth.email')</label>
                                        <input id="email" type="email" class="form-control" name="email" required>
                                    </div>
    
                                    <hr>
    
                                    <div class="form-group">
                                        <label for="image1">@lang('auth.proof-image') 1: @lang('auth.proof-min')</label>
                                        <label>
                                            <input type="text" name="images[]" class="form-control" value="">
                                        </label>
                                    </div>
    
                                    <div class="form-group">
                                        <label for="image2">@lang('auth.proof-image') 2:</label>
                                        <label>
                                            <input type="text" name="images[]" class="form-control" value="">
                                        </label>
                                    </div>
    
                                    <div class="more-images"></div>
    
                                    <div class="form-group">
                                        <button id="addImg" class="btn btn-primary">@lang('auth.add-image')</button>
                                        <button id="delImg" class="btn btn-primary">@lang('auth.delete-image')</button>
                                    </div>
    
                                    <hr>
    
                                    <div class="form-group">
                                        <label for="link1">@lang('auth.proof-profile') 1: @lang('auth.proof-min')</label>
                                        <label>
                                            <input type="text" name="links[]" class="form-control" value="">
                                        </label>
                                    </div>
    
                                    <div class="form-group">
                                        <label for="link2">@lang('auth.proof-profile') 2:</label>
                                        <label>
                                            <input type="text" name="links[]" class="form-control" value="">
                                        </label>
                                    </div>
    
                                    <div class="more-links"></div>
    
                                    <div class="form-group">
                                        <button id="addLink" class="btn btn-primary">@lang('auth.add-profile')</button>
                                        <button id="delLink" class="btn btn-primary">@lang('auth.delete-profile')</button>
                                    </div>
    
                                    <hr>
    
                                    <div class="form-group">
                                        <label for="referrer">@lang('auth.appl-reason',['sitename' =>
                                            config('other.title')])<span class="badge-extra">BBCode
                                                @lang('common.is-allowed')</span></label>
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
                                                @lang('common.submit')
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
    
        <script type="text/javascript" src="{{ mix('js/app.js') }}" integrity="{{ Sri::hash('js/app.js') }}"
            crossorigin="anonymous"></script>
    
        <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
            var images = 2;
    
            $('#addImg').on('click', function(e) {
                e.preventDefault();
                images = images + 1;
                var imageHTML = '<div class="form-group extra-image"><label for="image' + images +
                    '">Proof Image URL ' + images +
                    ':</label><input type="text" name="images[]" class="form-control" value="" required></div>';
                $('.more-images').append(imageHTML);
            });
    
            $('#delImg').on('click', function(e) {
                e.preventDefault();
                images = (images > 2) ? images - 1 : 2;
                $('.extra-image').last().remove();
            });
    
        </script>
    
        <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
            var links = 2;
    
            $('#addLink').on('click', function(e) {
                e.preventDefault();
                links = links + 1;
                var linkHTML = '<div class="form-group extra-link"><label for="link' + links +
                    '">Profile Link URL ' + links +
                    ':</label><input type="text" name="links[]" class="form-control" value="" required></div>';
                $('.more-links').append(linkHTML);
            });
    
            $('#delLink').on('click', function(e) {
                e.preventDefault();
                links = (links > 2) ? links - 1 : 2;
                $('.extra-link').last().remove();
            });
    
        </script>
    
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
                    html: '{{ Session::get('
                    errors ') }}',
                    showCloseButton: true,
                })
        
            </script>
        @endif
    
    @else
        <div class="container">
            <div class="jumbotron shadowed">
                <div class="container">
                    <h1 class="mt-5 text-center">
                        <i class="{{ config('other.font-awesome') }} fa-times text-danger"></i>@lang('auth.appl-closed')
                    </h1>
                    <div class="separator"></div>
                    <p class="text-center">@lang('auth.check-later')</p>
                </div>
            </div>
        </div>
    @endif
</body>

</html>
