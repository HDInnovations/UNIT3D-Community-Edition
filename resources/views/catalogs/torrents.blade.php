@extends('layout.default')

@section('title')
<title>{{ trans('torrent.torrents') }} - {{ Config::get('other.title') }}</title>
@stop

@section('meta')
<meta name="description" content="Catalog">
@stop

@section('breadcrumb')
<li>
    <a href="{{ route('catalogs') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.catalogs') }}</span>
    </a>
</li>
<li>
  <a href="#" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.catalog') }}</span>
  </a>
</li>
<li class="active">
  <a href="#" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.torrents') }}</span>
  </a>
</li>
@stop

@section('content')
<div class="container box">
  <div class="header gradient yellow">
    <div class="inner_content">
      <h1>{{ trans('comman.results') }}</h1>
    </div>
  </div>
  @if(count($torrents) == 0)
  <p>The are no results in database for this film!</p>
  @else
  @foreach($torrents as $t)
  <?php $client = new \App\Services\MovieScrapper('aa8b43b8cbce9d1689bef3d0c3087e4d', '3DF2684FC0240D28', 'b8272f7d'); ?>
  <?php $movie = $client->scrape('movie', 'tt'.$t->imdb); ?>
  <div class="col-sm-12 movie-list">
    <h3 class="movie-title">
      <a href="#" title="{{ $t->name }}">{{ $t->name }}</a>
    </h3>
    <ul class="list-inline">
      <span class="badge-extra text-blue"><i class="fa fa-database"></i> <strong>{{ trans('torrent.size') }}: </strong> {{ $t->getSize() }}</span>
      <span class="badge-extra text-blue"><i class="fa fa-fw fa-calendar"></i> <strong>{{ trans('torrent.released') }}: </strong> {{ $t->created_at->diffForHumans() }}</span>
      <span class="badge-extra text-green"><li><i class="fa fa-arrow-up"></i> <strong>{{ trans('torrent.seeders') }}: </strong> {{ $t->seeders }}</li></span>
      <span class="badge-extra text-red"><li><i class="fa fa-arrow-down"></i> <strong>{{ trans('torrent.leechers') }}: </strong> {{ $t->leechers }}</li></span>
      <span class="badge-extra text-orange"><li><i class="fa fa-check-square-o"></i> <strong>{{ trans('torrent.completed') }}: </strong> {{ $t->times_completed }}</li></span>
    </ul>
  </div>
  @endforeach
  @endif
</div>
@stop
