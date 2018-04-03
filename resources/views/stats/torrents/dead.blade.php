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
  <a href="{{ route('dead') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('stat.top-dead') }}</span>
  </a>
</li>
@endsection

@section('content')
<div class="container">
@include('partials.statstorrentmenu')

<div class="block">
  <h2>{{ trans('stat.top-dead') }}</h2>
  <hr>
  <div class="row">
    <div class="col-md-12">
    <p class="text-red"><strong><i class="fa fa-recycle"></i> {{ trans('stat.top-dead') }}</strong></p>
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
        @foreach($dead as $key => $d)
        <tr>
          <td>
              {{ ++$key }}
          </td>
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
@endsection
