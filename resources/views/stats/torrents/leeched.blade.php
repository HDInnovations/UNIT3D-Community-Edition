@extends('layout.default')

@section('title')
<title>{{ trans('stat.stats') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
<li class="active">
  <a href="{{ route('stats') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('stat.stats') }}</span>
  </a>
</li>
<li>
  <a href="{{ route('leeched') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('stat.top-leeched') }}</span>
  </a>
</li>
@endsection

@section('content')
<div class="container">
@include('partials.statstorrentmenu')

<div class="block">
  <h2>{{ trans('stat.top-leeched') }}</h2>
  <hr>
  <div class="row">
    <div class="col-md-12">
    <p class="text-warning"><strong><i class="fa fa-line-chart"></i> {{ trans('stat.top-leeched') }}</strong></p>
    <table class="table table-condensed table-striped table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>{{ trans('torrent.torrent') }}</th>
          <th>{{ trans('torrent.seeders') }}</th>
          <th>{{ trans('torrent.leechers') }}</th>
          <th>{{ trans('torrent.completed') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($leeched as $key => $l)
        <tr>
          <td>
              {{ ++$key }}
          </td>
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
@endsection
