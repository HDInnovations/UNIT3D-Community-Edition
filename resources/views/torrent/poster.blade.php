@extends('layout.default')

@section('breadcrumb')
<li>
  <a href="{{ route('torrents') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.torrents') }}</span>
  </a>
</li>
<li>
  <a href="{{ route('poster') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.poster-view') }}</span>
  </a>
</li>
@endsection

@section('content')
<div class="container box">
  <h1 class="torrents-title">{{ trans('torrent.torrents') }}</h1>
  <hr>
  <center>
    <p class="text-danger">{{ trans('common.search') }}</p>
  </center>
  <div class="row">
    <div class="col-md-12">
      <form action="{{route('poster_search')}}" class="form-horizontal" method="get">
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group">
              <label class="pull-left control-label" for="name">{{ trans('common.name') }}:</label>
              <input type="text" id="name" name="name" value="{{Request::old('name')}}" class="form-control">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4">
            <div class="form-group">
              <label class="pull-left control-label" for="genre">{{ trans('torrent.category') }}:</label>
              <select name="category_id" id="category_id" class="form-control">
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-lg-3 col-lg-offset-1">
            <div class="form-group">
              <label class="pull-left control-label" for="rating">{{ trans('torrent.type') }}:</label>
              <select name="type" id="type" class="form-control">
                @foreach($types as $type)
                  <option value="{{ $type->name }}">{{ $type->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-lg-3 col-lg-offset-1">
            <div class="form-group">
              <label class="pull-left control-label" for="order">{{ trans('common.order-by') }}:</label>
              <select class="form-control" name="order" id="order" value="{{Request::old('order')}}">
                <option value="created_at:desc" selected>{{ trans('common.latest') }}</option>
                <option value="created_at:asc">{{ trans('common.oldest') }}</option>
                <option value="seeders:desc">{{ trans('torrent.seeders') }}</option>
                <option value="leechers:desc">{{ trans('torrent.leechers') }}</option>
                <option value="times_completed:desc">{{ trans('torrent.completed') }} {{ strtolower(trans('common.times')) }}</option>
                <option value="size:desc">{{ trans('torrent.size') }}</option>
              </select>
            </div>
            <div class="form-group">
              <button class="pull-right btn btn btn-primary">{{ trans('common.search') }}</button>
            </div>
          </div>
        </div>
      </form>
      <div style="float:left;">
        <strong>{{ trans('common.extra') }}:</strong>
        <a href="{{ route('categories') }}" class="btn btn-xs btn-primary"><em class="icon fa fa-film"></em> {{ trans('torrent.categories') }}</a>
        <a href="{{ route('catalogs') }}" class="btn btn-xs btn-primary"><em class="icon fa fa-film"></em> {{ trans('torrent.catalogs') }}</a>
      </div>
      <div style="float:right;">
        <strong>{{ trans('common.view') }}:</strong>
        <a href="{{ route('torrents') }}" class="btn btn-xs btn-primary"><i class="fa fa-list"></i> {{ trans('common.lists') }}</a>
        <a href="{{ route('poster') }}" class="btn btn-xs btn-info"><i class="fa fa-image"></i> {{ trans('torrent.posters') }}</a>
      </div>
      <br>
    </div>
  </div>
</div>
</div>
<div class="block">
  <div class="row">
    @foreach($torrents as $k => $t)
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
    <div class="col-xs-6 col-sm-4 col-md-3">
      <div class="image-box text-center mb-20">
        <div class="overlay-container">
          <img class='details-poster' src="{{ $movie->poster }}">
          <div class="overlay-top">
            <div class="text">
              <h2>
                <a data-id="{{ $t->id }}" data-slug="{{ $t->slug }}" href="{{ route('torrent', array('slug' => $t->slug, 'id' => $t->id)) }}">{{ $t->name }}</a>
              </h2>
            </div>
          </div>
          <div class="overlay-bottom">
            <div class="links">
              <span class='label label-success'>{{ $t->type }}</span>
              <div class="separator mt-10"></div>
              <ul class="list-unstyled margin-clear">
                <li><i class="fa fa-database"></i> {{ trans('torrent.size') }}: {{ $t->getSize() }}</li>
                <li><i class="fa fa-arrow-up"></i> {{ trans('torrent.seeds') }}: {{ $t->seeders }}</li>
                <li><i class="fa fa-arrow-down"></i> {{ trans('torrent.leeches') }}: {{ $t->leechers }}</li>
                <li><i class="fa fa-check"></i> {{ trans('torrent.completed') }}: {{ $t->times_completed }}</li>
                <li>
                  <a rel="nofollow" href="https://anon.to?http://www.imdb.com/title/tt{{ $t->imdb }}" title="IMDB" target="_blank"><span class='label label-success'>{{ trans('common.view') }} IMDB</span></a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  {{ $torrents->links() }}
</div>
</div>
@endsection
