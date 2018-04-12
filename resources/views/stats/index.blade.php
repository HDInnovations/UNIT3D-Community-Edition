@extends('layout.default')

@section('title')
<title>{{ trans('stat.stats') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
<li>
    <a href="{{ route('stats') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('stat.stats') }}</span>
    </a>
</li>
@endsection

@section('content')
<div class="container box">
  <div class="header gradient red">
    <div class="inner_content">
      <h1>{{ trans('stat.site-stats') }}</h1>
    </div>
  </div>
  <div class="stats">
    <div class="content">
      <div class="inner_content">
        <h1>{{ trans('stat.nerd-stats') }}</h1>
        <p>{{ trans('stat.nerd-stats-desc') }}. <b>(Updated Every 60 Minutes!)</b></p>

        <div class="inner_stats">
        @foreach($categories as $category)
          <div class="stat">
            <p>{{ $category->num_torrent }}</p>
            <span class="badge-extra">{{ $category->name }} {{ trans('torrent.category') }}</span>
          </div>
        @endforeach

          <div class="stat">
            <p>{{ $num_torrent }}</p>
            <span class="badge-extra">{{ trans('stat.total-torrents') }}</span>
          </div>

          <div class="stat">
            <p>{{ $num_hd }}</p>
            <span class="badge-extra">HD {{ trans('torrent.torrents') }}</span>
          </div>

          <div class="stat">
            <p>{{ $num_sd }}</p>
            <span class="badge-extra">SD {{ trans('torrent.torrents') }}</span>
          </div>

          <br>

          <div class="stat">
            <p>{{ $num_user }}</p>
            <span class="badge-extra">{{ trans('common.users') }}</span>
          </div>

          <div class="stat">
            <p>{{ $num_seeders }}</p>
            <span class="badge-extra">{{ trans('torrent.seeders') }}</span>
          </div>

          <div class="stat">
            <p>{{ $num_leechers }}</p>
            <span class="badge-extra">{{ trans('torrent.leechers') }}</span>
          </div>

          <div class="stat">
            <p>{{ $num_peers }}</p>
            <span class="badge-extra">{{ trans('torrent.peers') }}</span>
          </div>

          <br>

          <div class="stat">
            <p>{{ \App\Helpers\StringHelper::formatBytes($actual_upload ,2) }}</p>
            <span class="badge-extra">Real {{ trans('stat.total-upload') }}</span>
          </div>

          <div class="stat">
            <p>{{ \App\Helpers\StringHelper::formatBytes($actual_download ,2) }}</p>
            <span class="badge-extra">Real {{ trans('stat.total-download') }}</span>
          </div>

          <div class="stat">
            <p>{{ \App\Helpers\StringHelper::formatBytes($actual_up_down ,2) }}</p>
            <span class="badge-extra">Real {{ trans('stat.total-traffic') }}</span>
          </div>

          <br>

          <div class="stat">
            <p>{{ \App\Helpers\StringHelper::formatBytes($credited_upload ,2) }}</p>
            <span class="badge-extra">Credited {{ trans('stat.total-upload') }}</span>
          </div>

          <div class="stat">
            <p>{{ \App\Helpers\StringHelper::formatBytes($credited_download ,2) }}</p>
            <span class="badge-extra">Credited {{ trans('stat.total-download') }}</span>
          </div>

          <div class="stat">
            <p>{{ \App\Helpers\StringHelper::formatBytes($credited_up_down ,2) }}</p>
            <span class="badge-extra">Credited {{ trans('stat.total-traffic') }}</span>
          </div>

        </div>
      </div>
      <img src="{{ url('img/sheldon.png') }}" width="321" height="379">
    </div>
  </div>
  <br>
<h3 class="text-center">{{ trans('stat.select-category') }}</h3>
<div class="row">
<div class="col-sm-4">
  <div class="well well-sm mt-20">
    <a href="{{ route('uploaded') }}">
      <p class="lead text-green text-center">{{ trans('common.users') }}</p>
    </a>
  </div>
</div>
<div class="col-sm-4">
  <div class="well well-sm mt-20">
    <a href="{{ route('seeded') }}">
      <p class="lead text-blue text-center">{{ trans('torrent.torrents') }}</p>
    </a>
  </div>
</div>
<div class="col-sm-4">
  <div class="well well-sm mt-20">
    <a href="{{ route('bountied') }}">
      <p class="lead text-orange text-center">{{ trans('request.requests') }}</p>
    </a>
  </div>
</div>
<div class="col-sm-4">
  <div class="well well-sm mt-20">
    <a href="{{ route('groups') }}">
      <p class="lead text-red text-center">{{ trans('common.groups') }}</p>
    </a>
  </div>
</div>
{{--<div class="col-sm-4">
  <div class="well well-sm mt-20">
    <a href="#">
      <p class="lead text-pink text-center">{{ trans('commmon.teams') }}</p>
    </a>
  </div>
</div>--}}
</br>
</br>
</div>
<p class="text-purple text-center text-mono">{{ trans('stat.stats-format') }}</p>
</div>
@endsection
