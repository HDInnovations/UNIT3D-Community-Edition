<meta charset="UTF-8" />
@section('title')
<title>{{ config('other.title') }} - {{ config('other.subTitle') }}</title>
@show

<meta name="description" content="{{ config('other.meta_description') }}" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="_base_url" content="{{ route('home.index') }}" />
<meta name="csrf-token" content="{{ csrf_token() }}" />

@yield('meta')

<link rel="shortcut icon" href="{{ url('/favicon.ico') }}" type="image/x-icon" />
<link rel="icon" href="{{ url('/favicon.ico') }}" type="image/x-icon" />

@if (auth()->user()->standalone_css === null)
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" crossorigin="anonymous" />

    @switch(auth()->user()->style)
        @case(0)
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/light.css') }}"
                crossorigin="anonymous"
            />

            @break
        @case(1)
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/galactic.css') }}"
                crossorigin="anonymous"
            />

            @break
        @case(2)
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/galactic.css') }}"
                crossorigin="anonymous"
            />
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/dark-blue.css') }}"
                crossorigin="anonymous"
            />

            @break
        @case(3)
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/galactic.css') }}"
                crossorigin="anonymous"
            />
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/dark-green.css') }}"
                crossorigin="anonymous"
            />

            @break
        @case(4)
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/galactic.css') }}"
                crossorigin="anonymous"
            />
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/dark-pink.css') }}"
                crossorigin="anonymous"
            />

            @break
        @case(5)
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/galactic.css') }}"
                crossorigin="anonymous"
            />
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/dark-purple.css') }}"
                crossorigin="anonymous"
            />

            @break
        @case(6)
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/galactic.css') }}"
                crossorigin="anonymous"
            />
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/dark-red.css') }}"
                crossorigin="anonymous"
            />

            @break
        @case(7)
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/galactic.css') }}"
                crossorigin="anonymous"
            />
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/dark-teal.css') }}"
                crossorigin="anonymous"
            />

            @break
        @case(8)
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/galactic.css') }}"
                crossorigin="anonymous"
            />
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/dark-yellow.css') }}"
                crossorigin="anonymous"
            />

            @break
        @case(9)
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/galactic.css') }}"
                crossorigin="anonymous"
            />
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/cosmic-void.css') }}"
                crossorigin="anonymous"
            />

            @break
        @case(10)
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/nord.css') }}"
                crossorigin="anonymous"
            />

            @break
        @case(11)
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/revel.css') }}"
                crossorigin="anonymous"
            />

            @break
        @case(12)
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/material-design-v3-light.css') }}"
                crossorigin="anonymous"
            />

            @break
        @case(13)
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/material-design-v3-dark.css') }}"
                crossorigin="anonymous"
            />

            @break
        @case(14)
            <link
                rel="stylesheet"
                href="{{ mix('css/themes/material-design-v3-amoled.css') }}"
                crossorigin="anonymous"
            />

            @break
    @endswitch

    @if (isset(auth()->user()->custom_css))
        <link rel="stylesheet" href="{{ auth()->user()->custom_css }}" />
    @endif
@else
    <link rel="stylesheet" href="{{ auth()->user()->standalone_css }}" />
@endif

@livewireStyles

@yield('stylesheets')
