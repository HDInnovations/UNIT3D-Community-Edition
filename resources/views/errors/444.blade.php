<!DOCTYPE html>
<html class="no-js" lang="{{ config('app.locale') }}">

<head>
  <meta charset="utf-8">
  <title>Error 444 - {{ config('other.title') }}</title>

  <!-- Meta -->
  @section('meta')
      <meta http-equiv="x-ua-compatible" content="ie=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="{{ $exception->getMessage() }}">
      <meta property="og:title" content="{{ config('other.title') }}">
      <meta property="og:type" content="website">
      <meta property="og:image" content="{{ url('/img/rlm.png') }}">
      <meta property="og:url" content="{{ url('/') }}">
      <meta name="csrf-token" content="{{ csrf_token() }}">
  @show
  <!-- /Meta -->

  <!--icons -->
  <link rel="shortcut icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
  <link rel="icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
  <!--icons -->

  <!--css -->
  <link rel="stylesheet" href="{{ url('css/main/custom.css') }}">
  <link rel="stylesheet" href="{{ url('css/main/advbuttons.css') }}">
  <link rel="stylesheet" href="{{ url('css/vendor/vendor.min.css') }}" />
  <!--css -->
  @yield('stylesheets')

</head>

<body>
  <section class="container content" id="content-area" style="min-height: 344px;">
   <h2>{{ $exception->getMessage() }}</h2>
    <div class="jumbotron shadowed">
      <div class="container">
        <h1 class="mt-5 text-center">
          <i class="fa fa-exclamation-circle text-warning"></i> Error 444
        </h1>
        <div class="separator"></div>
        <p class="text-center">CONNECTION CLOSED WITHOUT RESPONSE.</p>
        <p class="text-center">
          <a href="{{ url('/') }}" role="button" class="btn btn-labeled btn-primary"><span class="btn-label"><i class="fa fa-home"></i></span>Go Home</a>
        </p>
      </div>
    </div>
  </section>
</body>

</html>
