<meta charset="UTF-8">
@section('title')
    <title>{{ config('other.title') }} - {{ config('other.subTitle') }}</title>
@show

<meta name="description" content="{{ config('other.meta_description') }}">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="_base_url" content="{{ route('home.index') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

@yield('meta')

<link rel="shortcut icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
<link rel="icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">

@if (auth()->user()->standalone_css === null)
    @vite('resources/css/app.css')
    @if (auth()->user()->style == 1)
        @vite('resources/css/themes/galactic.css')
    @elseif (auth()->user()->style == 2)
        @vite('resources/css/themes/galactic.css')
        @vite('resources/css/themes/dark-blue.css')
    @elseif (auth()->user()->style == 3)
        @vite('resources/css/themes/galactic.css')
        @vite('resources/css/themes/dark-green.css')
    @elseif (auth()->user()->style == 4)
        @vite('resources/css/themes/galactic.css')
        @vite('resources/css/themes/dark-pink.css')
    @elseif (auth()->user()->style == 5)
        @vite('resources/css/themes/galactic.css')
        @vite('resources/css/themes/dark-purple.css')
    @elseif (auth()->user()->style == 6)
        @vite('resources/css/themes/galactic.css')
        @vite('resources/css/themes/dark-red.css')
    @elseif (auth()->user()->style == 7)
        @vite('resources/css/themes/galactic.css')
        @vite('resources/css/themes/dark-teal.css')
    @elseif (auth()->user()->style == 8)
        @vite('resources/css/themes/galactic.css')
        @vite('resources/css/themes/dark-yellow.css')
    @elseif (auth()->user()->style == 9)
        @vite('resources/css/themes/galactic.css')
        @vite('resources/css/themes/cosmic-void.css')
    @endif

    @if (isset(auth()->user()->custom_css))
        <link rel="stylesheet" href="{{ auth()->user()->custom_css }}">
    @endif

@else
    <link rel="stylesheet" href="{{ auth()->user()->standalone_css }}">
@endif

@livewireStyles

@yield('stylesheets')
