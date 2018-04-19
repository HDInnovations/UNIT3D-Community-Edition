@extends('layout.default')

@section('title')
<title>{{ trans('torrent.torrents') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
<meta name="description" content="{{ trans('torrent.torrents') }}">
@endsection

@section('breadcrumb')
<li>
    <a href="{{ route('torrents') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.torrents') }}</span>
    </a>
</li>
<li>
    <a href="{{ route('grouping_categories') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Grouping Categories</span>
    </a>
</li>
<li>
    <a href="#" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $category->name }} Grouping</span>
    </a>
</li>
@endsection

@section('content')
<div class="container box">
  <div class="header gradient light_blue">
    <div class="inner_content">
      <h1>{{ $category->name }} Grouping</h1>
    </div>
  </div>
  @foreach($torrents as $t)
  @php $client = new \App\Services\MovieScrapper(config('api-keys.tmdb') , config('api-keys.tvdb') , config('api-keys.omdb')) @endphp
  @if($t->category_id == 2)
  @php $movie = $client->scrape('tv', 'tt'. $t->imdb); @endphp
  @else
  @php $movie = $client->scrape('movie', 'tt'. $t->imdb); @endphp
  @endif
  <div class="row">
    <div class="col-sm-12 movie-list">
      <div class="pull-left">
        <a href="#">
          <img src="{{ $movie->poster }}" style="height:200px; margin-right:10px;" alt="{{ $movie->title }} Poster">
        </a>
      </div>
      <h2 class="movie-title">
          <a href="{{ route('grouping_results', ['category_id' => $t->category_id, 'imdb' => $t->imdb]) }}" title="{{ $movie->title }} ({{ $movie->releaseYear }})">{{ $movie->title }} ({{ $movie->releaseYear }})</a>
          <span class="badge-user text-bold text-gold">Rating:
            <span class="movie-rating-stars">
              <i class="fa fa-star"></i>
            </span>
            @if($user->ratings == 1)
            {{ $movie->imdbRating }}/10 ({{ $movie->imdbVotes }} votes)
            @else
            {{ $movie->tmdbRating }}/10 ({{ $movie->tmdbVotes }} votes)
            @endif
         </span>
      </h2>
      <div class="movie-details">
        <p class="movie-plot">{{ $movie->plot }}</p>
        <strong>ID:</strong>
        <span class="badge-user"><a rel="nofollow" href="http://www.imdb.com/title/tt{{ $movie->imdb }}">{{ $movie->imdb }}</a></span>
        <span class="badge-user"><a rel="nofollow" href="https://www.themoviedb.org/movie/{{ $movie->tmdb }}">{{ $movie->tmdb }}</a></span>
        <strong>Genre: </strong>
        @if($movie->genres)
        @foreach($movie->genres as $genre)
        <span class="badge-user text-bold text-green">{{ $genre }}</span>
        @endforeach
        @endif
      </div>
      <br>
      <ul class="list-inline">
         @php $count = DB::table('torrents')->where('imdb',$t->imdb)->where('category_id', $category->id)->count(); @endphp
        <li><i class="fa fa-files-o"></i> <strong>Torrents: </strong> {{ $count }}</li>
      </ul>
    </div>
  </div>
  @endforeach
  {{ $torrents->links() }}
</div>
@endsection
