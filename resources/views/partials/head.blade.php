<meta charset="UTF-8">
@section('title')
<title>{{ Config::get('other.title') }} - {{ Config::get('other.subTitle') }}</title>
@show

@section('meta')
<meta name="description" content="{{ Config::get('other.meta_description') }}">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta property="og:title" content="{{ Config::get('other.title') }}">
<meta property="og:type" content="website">
<meta property="og:image" content="{{ url('/img/rlm.png') }}">
<meta property="og:url" content="{{ url('/') }}">
<meta name="_base_url" content="{{ route('home') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@show

<link rel="shortcut icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
<link rel="icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">

<link rel="stylesheet" href="{{ url('css/vendor/vendor.min.css?v=05') }}" />
<link rel="stylesheet" href="{{ url('css/nav/hoe.css?v=05') }}">
<link rel="stylesheet" href="{{ url('css/main/custom.css?v=51') }}">
@if(Auth::user()->style == 1)
<link rel="stylesheet" href="{{ url('css/main/dark.css?v=03') }}">
@elseif(Auth::user()->style == 2)
<link rel="stylesheet" href="{{ url('css/main/blur.css?v=02') }}">
@endif
<link rel="stylesheet" href="{{ url('css/main/advbuttons.css?v=03') }}">
@if(isset(Auth::user()->custom_css))
<link rel="stylesheet" href="{{Auth::user()->custom_css}}"/>
@endif
@yield('stylesheets')
