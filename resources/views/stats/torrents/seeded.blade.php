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
  <a href="{{ route('seeded') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Top Seeded</span>
  </a>
</li>
@stop

@section('content')
<div class="container">
@include('partials.statstorrentmenu')

<div class="block">
  <h2>Top Seeded</h2>
  <hr>
  <div class="row">
    <div class="col-md-12">
    <p class="text-success"><strong><i class="fa fa-trophy"></i> Top Torrents</strong> (best seeded)</p>
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
        @foreach($seeded as $s)
        <tr>
          <td>
            <a class="view-torrent" data-id="{{ $s->id }}" data-slug="{{ $s->slug }}" href="{{ route('torrent', array('slug' => $s->slug, 'id' => $s->id)) }}" data-toggle="tooltip" title="" data-original-title="{{ $s->name }}">{{ $s->name }}</a>
          </td>
          <td><span class="text-green">{{ $s->seeders }}</span></td>
          <td>{{ $s->leechers }}</td>
          <td>
            <span>{{ $s->times_completed }}</span>
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
