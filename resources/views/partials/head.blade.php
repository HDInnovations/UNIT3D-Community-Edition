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
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" crossorigin="anonymous">
    @if (auth()->user()->style == 1)
        <link rel="stylesheet" href="{{ mix('css/themes/galactic.css') }}" crossorigin="anonymous">
    @elseif (auth()->user()->style == 2)
        <link rel="stylesheet" href="{{ mix('css/themes/galactic.css') }}" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ mix('css/themes/dark-blue.css') }}" crossorigin="anonymous">
    @elseif (auth()->user()->style == 3)
        <link rel="stylesheet" href="{{ mix('css/themes/galactic.css') }}" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ mix('css/themes/dark-green.css') }}" crossorigin="anonymous">
    @elseif (auth()->user()->style == 4)
        <link rel="stylesheet" href="{{ mix('css/themes/galactic.css') }}" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ mix('css/themes/dark-pink.css') }}" crossorigin="anonymous">
    @elseif (auth()->user()->style == 5)
        <link rel="stylesheet" href="{{ mix('css/themes/galactic.css') }}" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ mix('css/themes/dark-purple.css') }}" crossorigin="anonymous">
    @elseif (auth()->user()->style == 6)
        <link rel="stylesheet" href="{{ mix('css/themes/galactic.css') }}" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ mix('css/themes/dark-red.css') }}" crossorigin="anonymous">
    @elseif (auth()->user()->style == 7)
        <link rel="stylesheet" href="{{ mix('css/themes/galactic.css') }}" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ mix('css/themes/dark-teal.css') }}" crossorigin="anonymous">
    @elseif (auth()->user()->style == 8)
        <link rel="stylesheet" href="{{ mix('css/themes/galactic.css') }}" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ mix('css/themes/dark-yellow.css') }}" crossorigin="anonymous">
    @elseif (auth()->user()->style == 9)
        <link rel="stylesheet" href="{{ mix('css/themes/galactic.css') }}" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ mix('css/themes/cosmic-void.css') }}" crossorigin="anonymous">
    @endif

    @if (isset(auth()->user()->custom_css))
        <link rel="stylesheet" href="{{ auth()->user()->custom_css }}">
    @endif

@else
    <link rel="stylesheet" href="{{ auth()->user()->standalone_css }}">
@endif

@livewireStyles

@yield('stylesheets')
