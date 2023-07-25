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
    <main>
        <article>
            @if(config('other.application_signups') == true)
                <section class="panelV2">
                    <h1 class="panel__heading">{{ __('auth.application') }}</h1>
                    <div class="panel__body">
                        <div class="alert alert-info text-center">
                            {{ config('other.title') }} {{ __('auth.appl-intro') }}
                        </div>
                        <form class="form" method="POST" action="{{ route('application.store') }}" x-data="{ links: 2 }">
                            @csrf
                            <p class="form__group">
                                <select class="form__select" name="type" required>
                                    <option class="form__option" value="New To The Game" selected>
                                            {{ __('auth.newbie') }}
                                    </option>
                                    <option class="form__option" value="Experienced With Private Trackers">
                                        {{ __('auth.veteran') }}
                                    </option>
                                </select>
                                <label for="type" class="form__label form__label--floating">{{ __('auth.are-you') }}</label>
                            </p>
                            <p class="form__group">
                                <input id="email" type="email" class="form__text" name="email" required>
                                <label for="email" class="form__label form__label--floating">{{ __('auth.email') }}</label>
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
                            </p>
                            {{ __('auth.proof-image') }} {{ __('auth.proof-min') }}
                            <template x-for="image in links">
                                <p class="form__group">
                                    <input
                                        x-bind:id="'image' + image"
                                        class="form__text"
                                        name="images[]"
                                        type="url"
                                        placeholder=" "
                                        required
                                    >
                                    <label
                                        class="form__label form__label--floating"
                                        x-bind:for="'image' + image"
                                        x-text="'{{ __('auth.proof-image') }} ' + image"
                                    >
                                        {{ __('poll.option') }}
                                    </label>
                                </p>
                            </template>
                            {{ __('auth.proof-profile-title') }} {{ __('auth.proof-min') }}
                            <template x-for="profile in links">
                                <p class="form__group">
                                    <input
                                        x-bind:id="'profile' + profile"
                                        class="form__text"
                                        name="links[]"
                                        type="url"
                                        placeholder=" "
                                    >
                                    <label
                                        class="form__label form__label--floating"
                                        x-bind:for="'profile' + profile"
                                        x-text="'{{ __('auth.proof-profile') }} ' + profile"
                                    >
                                        {{ __('poll.option') }}
                                    </label>
                                </p>
                            </template>
                            <p class="form__group">
                                <button
                                    x-on:click.prevent="links++"
                                    class="form__button form__button--outlined"
                                >
                                    {{ __('poll.add-option') }}
                                </button>
                                <button
                                    class="form__button form__button--outlined"
                                    x-on:click.prevent="links = Math.max(2, links - 1)"
                                >
                                    {{ __('poll.delete-option') }}
                                </button>
                            </p>
                            @livewire('bbcode-input', [
                                'name' => 'referrer',
                                'label' => __('auth.appl-reason',['sitename' => config('other.title')]),
                                'required' => true
                            ])
                            @if (config('captcha.enabled') == true)
                                @hiddencaptcha
                            @endif
                            <p class="form__group">
                                <button class="form__button form__button--filled">
                                    {{ __('common.submit') }}
                                </button>
                            </p>
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
            @else
                <section class="panelV2">
                    <h1 class="panelV2">
                        <i class="{{ config('other.font-awesome') }} fa-times text-danger"></i>
                        {{ __('auth.appl-closed') }}
                    </h1>
                    <div class="panel__body">{{ __('auth.check-later') }}</div>
                </section>
            @endif
        </article>
    </main>
</body>
</html>
