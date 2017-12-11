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
  <a href="{{ route('dying') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Top Dying</span>
  </a>
</li>
@stop

@section('content')
<div class="container">
@include('partials.statstorrentmenu')

<div class="block">
  <h2>Top Dying</h2>
  <hr>
  <div class="row">
    <div class="col-md-12">
    <p class="text-orange"><strong><i class="fa fa-exclamation-triangle"></i> Top Dying</strong></p>
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
        @foreach($dying as $d)
        <tr>
          <td>
            <a class="view-torrent" data-id="{{ $d->id }}" data-slug="{{ $d->slug }}" href="{{ route('torrent', array('slug' => $d->slug, 'id' => $d->id)) }}" data-toggle="tooltip" title="" data-original-title="{{ $d->name }}">{{ $d->name }}</a>
          </td>
          <td>{{ $d->seeders }}</td>
          <td>{{ $d->leechers }}</td>
          <td>
            <span>{{ $d->times_completed }}</span>
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
