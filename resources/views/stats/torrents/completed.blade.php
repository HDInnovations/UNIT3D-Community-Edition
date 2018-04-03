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
  <a href="{{ route('completed') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('stat.top-completed') }}</span>
  </a>
</li>
@endsection

@section('content')
<div class="container">
@include('partials.statstorrentmenu')

<div class="block">
  <h2>{{ trans('stat.top-completed') }}</h2>
  <hr>
  <div class="row">
    <div class="col-md-12">
    <p class="text-info"><strong><i class="fa fa-line-chart"></i> {{ trans('stat.top-downloaded') }}</strong></p>
    </center>
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
      @foreach($completed as $key => $c)
      <tr>
        <td>
            {{ ++$key }}
        </td>
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
@endsection
