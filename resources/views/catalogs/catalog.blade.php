@extends('layout.default')

@section('title')
<title>{{ $catalog->name }} - {{ trans('torrent.torrents') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
<meta name="description" content="{{ $catalog->name }} {{ strtolower(trans('torrent.catalog')) }}">
@endsection

@section('breadcrumb')
<li>
    <a href="{{ route('categories') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.catalogs') }}</span>
    </a>
</li>
<li class="active">
  <a href="#" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.catalog') }}</span>
  </a>
</li>
@endsection

@section('content')
<div class="container box">
  <div class="header gradient pink">
    <div class="inner_content">
      <h1>{{ trans('torrent.torrents') }}: {{ $catalog->name }} {{ strtolower(trans('torrent.catalog')) }}</h1>
    </div>
  </div>
  @foreach($records as $r)
    <div class="row">
      @php $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb')); @endphp
      @if ($r->category_id == 2)
          @if ($r->tmdb || $r->tmdb != 0)
          @php $movie = $client->scrape('tv', null, $r->tmdb); @endphp
          @else
          @php $movie = $client->scrape('tv', 'tt'. $r->imdb); @endphp
          @endif
      @else
          @if ($r->tmdb || $r->tmdb != 0)
          @php $movie = $client->scrape('movie', null, $r->tmdb); @endphp
          @else
          @php $movie = $client->scrape('movie', 'tt'. $r->imdb); @endphp
          @endif
      @endif
		<div class="col-md-12">
			<div class="well">
					<h2><a href="{{ route('catalog_torrents', array('imdb' => $r->imdb)) }}">{{ $movie->title }} ({{ $movie->releaseYear }})</a></h2>
          <div class="movie-details">
            <p class="movie-plot">{{ $movie->plot }}</p>
          </div>
      </div>
    </div>
  </div>
@endforeach
</div>
</div>
@endsection
