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

<link rel="stylesheet" href="{{ mix('css/app.css') }}" integrity="{{ Sri::hash('css/app.css') }}" crossorigin="anonymous">

@if (auth()->user()->style == 1)
    <link rel="stylesheet" href="{{ mix('css/themes/galactic.css') }}" integrity="{{ Sri::hash('css/themes/galactic.css') }}" crossorigin="anonymous">
@elseif (auth()->user()->style == 2)
    <link rel="stylesheet" href="{{ mix('css/themes/galactic.css') }}" integrity="{{ Sri::hash('css/themes/galactic.css') }}" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ mix('css/themes/dark-blue.css') }}" integrity="{{ Sri::hash('css/themes/dark-blue.css') }}" crossorigin="anonymous">
@elseif (auth()->user()->style == 3)
    <link rel="stylesheet" href="{{ mix('css/themes/galactic.css') }}" integrity="{{ Sri::hash('css/themes/galactic.css') }}" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ mix('css/themes/dark-green.css') }}" integrity="{{ Sri::hash('css/themes/dark-green.css') }}" crossorigin="anonymous">
@elseif (auth()->user()->style == 4)
    <link rel="stylesheet" href="{{ mix('css/themes/galactic.css') }}" integrity="{{ Sri::hash('css/themes/galactic.css') }}" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ mix('css/themes/dark-pink.css') }}" integrity="{{ Sri::hash('css/themes/dark-pink.css') }}" crossorigin="anonymous">
@elseif (auth()->user()->style == 5)
    <link rel="stylesheet" href="{{ mix('css/themes/galactic.css') }}" integrity="{{ Sri::hash('css/themes/galactic.css') }}" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ mix('css/themes/dark-purple.css') }}" integrity="{{ Sri::hash('css/themes/dark-purple.css') }}" crossorigin="anonymous">
@elseif (auth()->user()->style == 6)
    <link rel="stylesheet" href="{{ mix('css/themes/galactic.css') }}" integrity="{{ Sri::hash('css/themes/galactic.css') }}" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ mix('css/themes/dark-red.css') }}" integrity="{{ Sri::hash('css/themes/dark-red.css') }}" crossorigin="anonymous">
@elseif (auth()->user()->style == 7)
    <link rel="stylesheet" href="{{ mix('css/themes/galactic.css') }}" integrity="{{ Sri::hash('css/themes/galactic.css') }}" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ mix('css/themes/dark-teal.css') }}" integrity="{{ Sri::hash('css/themes/dark-teal.css') }}" crossorigin="anonymous">
@elseif (auth()->user()->style == 8)
    <link rel="stylesheet" href="{{ mix('css/themes/galactic.css') }}" integrity="{{ Sri::hash('css/themes/galactic.css') }}" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ mix('css/themes/dark-yellow.css') }}" integrity="{{ Sri::hash('css/themes/dark-yellow.css') }}" crossorigin="anonymous">
@endif

@if (isset(auth()->user()->custom_css))
    <link rel="stylesheet" href="{{ auth()->user()->custom_css }}">
@endif

@yield('stylesheets')
