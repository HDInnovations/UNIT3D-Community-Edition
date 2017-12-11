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
  <a href="{{ route('completed') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Top Completed</span>
  </a>
</li>
@stop

@section('content')
<div class="container">
@include('partials.statstorrentmenu')

<div class="block">
  <h2>Top Completed</h2>
  <hr>
  <div class="row">
    <div class="col-md-12">
    <p class="text-info"><strong><i class="fa fa-line-chart"></i> Top Downloaded Torrents</strong> (most times completed)</p>
    </center>
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
      @foreach($completed as $c)
      <tr>
        <td>
          <a class="view-torrent" data-id="{{ $c->id }}" data-slug="{{ $c->slug }}" href="{{ route('torrent', array('slug' => $c->slug, 'id' => $c->id)) }}" data-toggle="tooltip" title="" data-original-title="{{ $c->name }}">{{ $c->name }}</a>
        </td>
        <td>{{ $c->seeders }}</td>
        <td>{{ $c->leechers }}</td>
        <td>
          <span class="text-orange">{{ $c->times_completed }}</span>
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
