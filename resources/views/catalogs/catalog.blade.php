@extends('layout.default')

@section('title')
<title>{{ $catalog->name }} - Torrents - {{ Config::get('other.title') }}</title>
@stop

@section('meta')
<meta name="description" content="{{ $catalog->name }} Catalog">
@stop

@section('breadcrumb')
<li>
    <a href="{{ route('categories') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Catalogs</span>
    </a>
</li>
<li class="active">
  <a href="#" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Catalog</span>
  </a>
</li>
@stop

@section('content')
<div class="container box">
  <div class="header gradient pink">
    <div class="inner_content">
      <h1>Movies in {{ $catalog->name }} Catalog</h1>
    </div>
  </div>
  @foreach($records as $r)
    <div class="row">
      <?php $client = new \App\Services\MovieScrapper('aa8b43b8cbce9d1689bef3d0c3087e4d', '3DF2684FC0240D28', 'b8272f7d'); ?>
      <?php $movie = $client->scrape('movie', 'tt'.$r->imdb); ?>
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
@stop
