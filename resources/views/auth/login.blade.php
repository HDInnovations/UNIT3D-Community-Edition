<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="UTF-8" />
    <title>{{ __('auth.login') }} - {{ config('other.title') }}</title>
    @section('meta')
        <meta name="description"
              content="{{ __('auth.login-now-on') }} {{ config('other.title') }} . {{ __('auth.not-a-member') }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta property="og:title" content="{{ __('auth.login') }}" />
        <meta property="og:site_name" content="{{ config('other.title') }}" />
        <meta property="og:type" content="website" />
        <meta property="og:image" content="{{ url('/img/og.png') }}" />
        <meta property="og:description" content="{{ config('unit3d.powered-by') }}" />
        <meta property="og:url" content="{{ url('/') }}" />
        <meta property="og:locale" content="{{ config('app.locale') }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
    @show
    <link rel="shortcut icon" href="{{ url('/favicon.ico') }}" type="image/x-icon" />
    <link rel="icon" href="{{ url('/favicon.ico') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ mix('css/main/login.css') }}" crossorigin="anonymous" />
    <style>
        body {
            font-family: 'Helvetica Neue LT Pro', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            overflow: auto;
        }

        main {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 60px 80px;
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
        }

        .auth-form__form {
            display: flex;
            flex-direction: column;
        }

        .auth-form__text-input,
        .auth-form__checkbox-input,
        button {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #f4faf5;
        }

        .auth-form__primary-button {
            background-color: #2e7d32;
            color: #f4faf5;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        .auth-form__primary-button:hover {
            background-color: #2e7d32;
        }

        .footer {
            background-color: #2e7d32;
            color: #f4faf5;
            text-align: center;
            position: relative;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 10px 0;
            margin-top: 20px;
            border-radius: 8px;
        }

        .footer a,
        .auth-form__footer-item {
            color: #4e342e;
            text-decoration: none;
        }


        .footer a:hover,
        .footer a:focus,
        .auth-form__footer-item:hover {
            color: #f4faf5;
            text-decoration: underline;
        }

        .spoiler-toggle {
            display: none;
        }

        .spoiler-label {
            display: block;
            cursor: pointer;
            background-color: #f4faf5;
            color: #2e7d32;
            padding: 10px;
            margin: 20px auto;
        / text-align: center;
            border: 1px solid #2e7d32;
            width: auto;
            max-width: 800px;
        }

        .spoiler-content {
            display: none;
            margin-top: 5px;
            padding: 10px;
            background-color: #f4faf5;
            border-left: 3px solid #2e7d32;
            color: #4e342e;
            text-align: left;
            width: auto;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .spoiler-toggle:checked + .spoiler-label + .spoiler-content {
            display: block;
        }

        @media (max-width: 768px) {
            main {
                width: 95%;
                padding: 20px;
                margin: 10px;
                max-width: 95%;
            }
        }

        main > section:first-of-type {
            margin-top: 40px;
        }
    </style>


</head>

<body>
<!-- Do NOT Change! For Jackett Support -->
<div class="Jackett" style="display: none">{{ config('unit3d.powered-by') }}</div>
<!-- Do NOT Change! For Jackett Support -->
<main>
    <section style="text-align: center;">
        <h1>Welcome to Cinematik 2.0</h1>
        <p>Following our recent software upgrade, please take a moment to:</p>
        <p><strong>Reset your password</strong> and <strong>verify your email address</strong> as part of our enhanced
            security measures.</p>
    </section>

    <section style="margin: 20px; padding: 15px; background-color: #f4faf5; border-radius: 5px; text-align: center;">
        <h2>Troubleshooting Tips</h2>
        <p>Encountering issues? Here are some quick fixes:</p>
        <ul style="text-align: left; display: inline-block; list-style-position: inside;">
            <li>Choose a unique and strong new password.</li>
            <li>Check your spam folder for our emails.</li>
            <li>Remove any Cinematik passwords saved in your browser.</li>
            <li>For ongoing problems, try a different browser without using autofill.</li>
            <li>Use your username, not your email, in the username field.</li>
        </ul>
    </section>


    <section class="auth-form">
        <form class="auth-form__form" method="POST" action="{{ route('login') }}">
            @csrf
            <a class="auth-form__branding" href="{{ route('home.index') }}">
                <i class="fal fa-tv-retro"></i>
                <span class="auth-form__site-logo">{{ \config('other.title') }}</span>
            </a>
            @if (Session::has('warning') || Session::has('success') || Session::has('info'))
                <ul class="auth-form__important-infos">
                    @if (Session::has('warning'))
                        <li class="auth-form__important-info">
                            Warning: {{ Session::get('warning') }}
                        </li>
                    @endif

                    @if (Session::has('info'))
                        <li class="auth-form__important-info">
                            Info: {{ Session::get('info') }}
                        </li>
                    @endif

                    @if (Session::has('success'))
                        <li class="auth-form__important-info">
                            Success: {{ Session::get('success') }}
                        </li>
                    @endif
                </ul>
            @endif
            <p class="auth-form__text-input-group">
                <label class="auth-form__label" for="username">
                    {{ __('auth.username') }}
                </label>
                <input id="username" class="auth-form__text-input" autocomplete="username" autofocus name="username"
                       required type="text" value="{{ old('username') }}" />
            </p>
            <p class="auth-form__text-input-group">
                <label class="auth-form__label" for="password">
                    {{ __('auth.password') }}
                </label>
                <input id="password" class="auth-form__text-input" autocomplete="current-password" name="password"
                       required type="password" />
            </p>
            <p class="auth-form__checkbox-input-group">
                <input id="remember" class="auth-form__checkbox-input" name="remember"
                       {{ old('remember') ? 'checked' : '' }} type="checkbox" />
                <label class="auth-form__label" for="remember">
                    {{ __('auth.remember-me') }}
                </label>
            </p>
            @if (config('captcha.enabled'))
                @hiddencaptcha
            @endif

            <button class="auth-form__primary-button">{{ __('auth.login') }}</button>
            @if (Session::has('errors'))
                <ul class="auth-form__errors">
                    @foreach ($errors->all() as $error)
                        <li class="auth-form__error">{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </form>
        <footer class="auth-form__footer">
            @if (! config('other.invite-only'))
                <a class="auth-form__footer-item" href="{{ route('register') }}">
                    {{ __('auth.signup') }}
                </a>
            @elseif (config('other.application_signups'))
                <a class="auth-form__footer-item" href="{{ route('application.create') }}">
                    {{ __('auth.apply') }}
                </a>
            @endif

            <a class="auth-form__footer-item" href="{{ route('password.request') }}">
                {{ __('auth.lost-password') }}
            </a>
        </footer>
    </section>
    <div class="footer">
        <div class="spoiler">
            <input type="checkbox" id="spoiler-1" class="spoiler-toggle">
            <label for="spoiler-1" class="spoiler-label">IRC Channel Instructions</label>
            <div class="spoiler-content">
                <p>To participate in our IRC channel, please follow these steps:</p>
                <strong>1. Connect to IRC Server with SSL</strong>
                <pre>/server irc.p2p-network.net 6697</pre>
                <p>For a secure SSL connection, use port 6697 or 7000.</p>

                <strong>2. Register Your Nick</strong>
                <pre>/msg NickServ REGISTER [YourNickPassword] [YourEmail]</pre>
                <p>Please wait for 180 seconds before attempting registration and make sure to use a unique password,
                    distinct from your website password.</p>

                <strong>3. Identify with NickServ</strong>
                <pre>/msg NickServ IDENTIFY [YourNickPassword]</pre>
                <p>This step is necessary for all future logins after your initial registration.</p>

                <strong>4. Join the Support Channel</strong>
                <pre>/join #tik-support</pre>
                <p>Once your account has been verified, you will receive an invitation to #cinematik and be granted the
                    'Voice' status.</p>

                <p><em>Note: To complete TIK IRC verification, register your IRC nick, join #tik-support, and follow the
                        instructions provided by the moderators.</em></p>
            </div>
        </div>

    </div>
</main>
</body>

</html>
