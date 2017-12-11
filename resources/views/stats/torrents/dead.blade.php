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
  <a href="{{ route('dead') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Top Dead</span>
  </a>
</li>
@stop

@section('content')
<div class="container">
@include('partials.statstorrentmenu')

<div class="block">
  <h2>Top Dead</h2>
  <hr>
  <div class="row">
    <div class="col-md-12">
    <p class="text-red"><strong><i class="fa fa-recycle"></i> Top Dead</strong></p>
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
        @foreach($dead as $d)
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
