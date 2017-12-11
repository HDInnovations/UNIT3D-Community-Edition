@extends('layout.default')

@section('title')
<title>Stats - {{ Config::get('other.title') }}</title>
@stop

@section('breadcrumb')
<li class="active">
  <a href="{{ route('stats') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Stats</span>
  </a>
</li>
<li>
  <a href="{{ route('leeched') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Top Leeched</span>
  </a>
</li>
@stop

@section('content')
<div class="container">
@include('partials.statstorrentmenu')

<div class="block">
  <h2>Top Leeched</h2>
  <hr>
  <div class="row">
    <div class="col-md-12">
    <p class="text-warning"><strong><i class="fa fa-line-chart"></i> Top Leeched Torrents</strong> (most active leeches)</p>
    <table class="table table-condensed table-striped table-bordered">
      <thead>
        <tr>
          <th>Torrent</th>
          <th>Seeders</th>
          <th>Leechers</th>
          <th>Completed</th>
        </tr>
      </thead>
      <tbody>
        @foreach($leeched as $l)
        <tr>
          <td>
            <a class="view-torrent" data-id="{{ $l->id }}" data-slug="{{ $l->slug }}" href="{{ route('torrent', array('slug' => $l->slug, 'id' => $l->id)) }}" data-toggle="tooltip" title="" data-original-title="{{ $l->name }}">{{ $l->name }}</a>
          </td>
          <td>{{ $l->seeders }}</td>
          <td><span class="text-red">{{ $l->leechers }}</span></td>
          <td>
            <span>{{ $l->times_completed }}</span>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    </div>
  </div>
</div>
</div>
@stop
