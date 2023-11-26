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
    @switch(auth()->user()->style)
        @case(1)
            @vite('resources/css/themes/galactic.css')
            @break
        @case(2)
            @vite('resources/css/themes/galactic.css')
            @vite('resources/css/themes/dark-blue.css')
            @break
        @case(3)
            @vite('resources/css/themes/galactic.css')
            @vite('resources/css/themes/dark-green.css')
            @break
        @case(4)
            @vite('resources/css/themes/galactic.css')
            @vite('resources/css/themes/dark-pink.css')
            @break
        @case(5)
            @vite('resources/css/themes/galactic.css')
            @vite('resources/css/themes/dark-purple.css')
            @break
        @case(6)
            @vite('resources/css/themes/galactic.css')
            @vite('resources/css/themes/dark-red.css')
            @break
        @case(7)
            @vite('resources/css/themes/galactic.css')
            @vite('resources/css/themes/dark-teal.css')
            @break
        @case(8)
            @vite('resources/css/themes/galactic.css')
            @vite('resources/css/themes/dark-yellow.css')
            @break
        @case(9)
            @vite('resources/css/themes/galactic.css')
            @vite('resources/css/themes/cosmic-void.css')
            @break
        @case(10)
            @vite('resources/css/themes/nord.css')
            @break
        @case(11)
            @vite('resources/css/themes/revel.css')
            @break
    @endswitch

    @if (isset(auth()->user()->custom_css))
        <link rel="stylesheet" href="{{ auth()->user()->custom_css }}">
    @endif

@else
    <link rel="stylesheet" href="{{ auth()->user()->standalone_css }}">
@endif

@livewireStyles

@yield('stylesheets')
