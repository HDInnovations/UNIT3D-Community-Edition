@extends('layout.default')

@section('title')
<title>Stats - {{ Config::get('other.title') }}</title>
@stop

@section('breadcrumb')
<li>
    <a href="{{ route('stats') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Stats</span>
    </a>
</li>
@stop

@section('content')
<div class="container box">
  <div class="header gradient red">
    <div class="inner_content">
      <h1>Site Stats</h1>
    </div>
  </div>
  <div class="stats">
    <div class="content">
      <div class="inner_content">
        <h1>Nerd Stats</h1>
        <p>We all love stats. Here are a few that we find important.</p>

        <div class="inner_stats">
          <div class="stat">
            <p>{{ $num_movies }}</p>
            <span class="badge-extra">Movie Category</span>
          </div>

          <div class="stat">
            <p>{{ $num_hdtv }}</p>
            <span class="badge-extra">TV Category</span>
          </div>

          <div class="stat">
            <p>{{ $num_fan }}</p>
            <span class="badge-extra">FANRES Category</span>
          </div>

          <div class="stat">
            <p>{{ $num_sd }}</p>
            <span class="badge-extra">SD Category</span>
          </div>

          <div class="stat">
            <p>{{ $num_torrent }}</p>
            <span class="badge-extra">Total Torrents</span>
          </div>

          <br>

          <div class="stat">
            <p>{{ $num_user }}</p>
            <span class="badge-extra">Users</span>
          </div>

          <div class="stat">
            <p>{{ $num_seeders }}</p>
            <span class="badge-extra">Seeders</span>
          </div>

          <div class="stat">
            <p>{{ $num_leechers }}</p>
            <span class="badge-extra">Leechers</span>
          </div>

          <div class="stat">
            <p>{{ $num_peers }}</p>
            <span class="badge-extra">Peers</span>
          </div>

          <br>

          <div class="stat">
            <p>{{ \App\Helpers\StringHelper::formatBytes($tot_upload ,2) }}</p>
            <span class="badge-extra">Total Upload</span>
          </div>

          <div class="stat">
            <p>{{ \App\Helpers\StringHelper::formatBytes($tot_download ,2) }}</p>
            <span class="badge-extra">Total Download</span>
          </div>

          <div class="stat">
            <p>{{ \App\Helpers\StringHelper::formatBytes($tot_up_down ,2) }}</p>
            <span class="badge-extra">Total Traffic</span>
          </div>
        </div>
      </div>
      <img src="{{ url('img/sheldon.png') }}" width="321" height="379">
    </div>
  </div>
  <br>
<h3 class="text-center">Please Select A Category Below</h3>
<div class="row">
<div class="col-sm-4">
  <div class="well well-sm mt-20">
    <a href="{{ route('uploaded') }}">
      <p class="lead text-green text-center">Users</p>
    </a>
  </div>
</div>
<div class="col-sm-4">
  <div class="well well-sm mt-20">
    <a href="{{ route('seeded') }}">
      <p class="lead text-blue text-center">Torrents</p>
    </a>
  </div>
</div>
<div class="col-sm-4">
  <div class="well well-sm mt-20">
    <a href="{{ route('bountied') }}">
      <p class="lead text-orange text-center">Requests</p>
    </a>
  </div>
</div>
<div class="col-sm-4">
  <div class="well well-sm mt-20">
    <a href="{{ route('groups') }}">
      <p class="lead text-red text-center">Groups</p>
    </a>
  </div>
</div>
{{--<div class="col-sm-4">
  <div class="well well-sm mt-20">
    <a href="#">
      <p class="lead text-pink text-center">Teams</p>
    </a>
  </div>
</div>--}}
</br>
</br>
</div>
<p class="text-purple text-center text-mono">All Stats Displayed In Top 100 Format</p>
</div>
@stop
