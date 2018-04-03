@extends('layout.default')

@section('title')
<title>{{ trans('torrent.torrents') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
<meta name="description" content="Catalog">
@endsection

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
@endsection

@section('content')
<div class="container box">
  <div class="header gradient yellow">
    <div class="inner_content">
      <h1>{{ trans('common.results') }}</h1>
    </div>
  </div>
  @if(count($torrents) == 0)
  <p>{{ trans('common.no-result') }}</p>
  @else
  @foreach($torrents as $t)
  @php $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb')); @endphp
  @if ($t->category_id == 2)
      @if ($t->tmdb || $t->tmdb != 0)
      @php $movie = $client->scrape('tv', null, $t->tmdb); @endphp
      @else
      @php $movie = $client->scrape('tv', 'tt'. $t->imdb); @endphp
      @endif
  @else
      @if ($t->tmdb || $t->tmdb != 0)
      @php $movie = $client->scrape('movie', null, $t->tmdb); @endphp
      @else
      @php $movie = $client->scrape('movie', 'tt'. $t->imdb); @endphp
      @endif
  @endif
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
@endsection
