<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="UTF-8">
    <title>{{ __('Two Factor Authentication') }} - {{ config('other.title') }}</title>
    <link rel="shortcut icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" crossorigin="anonymous">
</head>

<body>
<main>
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">
                {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
            </h2>
        </header>
        <div class="panel__body">
            <div x-data="{ recovery: false }">
                <div class="mb-4 text-sm text-gray-600 dark:text-gray-400" x-show="! recovery">
                    {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
                </div>

                <div class="mb-4 text-sm text-gray-600 dark:text-gray-400" x-cloak x-show="recovery">
                    {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
                </div>


                <form method="POST" action="{{ route('two-factor.login') }}">
                    @csrf

                    <div x-show="! recovery">
                        <label for="code" value="{{ __('Code') }}"></label>
                        <input id="code" class="form__text" type="text" inputmode="numeric" name="code" autofocus
                               x-ref="code" autocomplete="one-time-code"/>
                    </div>

                    <div x-cloak x-show="recovery">
                        <label for="recovery_code" value="{{ __('Recovery Code') }}"></label>
                        <input id="recovery_code" class="form__text" type="text" name="recovery_code"
                               x-ref="recovery_code" autocomplete="one-time-code"/>
                    </div>

                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <span class="text-danger">{{ $error }}</span>
                        @endforeach
                    @endif

                    <div>
                        <button type="button" class="form__button form__button--outlined"
                                x-show="! recovery"
                                x-on:click="
                                        recovery = true;
                                        $nextTick(() => { $refs.recovery_code.focus() })
                                    ">
                            {{ __('Use a recovery code') }}
                        </button>

                        <button type="button" class="form__button form__button--outlined"
                                x-cloak
                                x-show="recovery"
                                x-on:click="
                                        recovery = false;
                                        $nextTick(() => { $refs.code.focus() })
                                    ">
                            {{ __('Use an authentication code') }}
                        </button>

                        <button class="form__button form__button--filled">
                            {{ __('Log in') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
<script src="{{ mix('js/alpine.js') }}" crossorigin="anonymous"></script>
</body>

</html>