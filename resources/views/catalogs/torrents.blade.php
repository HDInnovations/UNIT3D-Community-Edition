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
  <div class="torrents col-md-12">
    <div class="table-responsive">
      <table class="table table-condensed table-bordered table-striped table-hover">
        <thead>
          <tr>
            <th>Poster</th>
            <th>Category</th>
            <th>Name</th>
            <th><i class="fa fa-clock-o"></i></th>
            <th><i class="fa fa-file"></i></th>
            <th><i class="fa fa-check-square-o"></i></th>
            <th><i class="fa fa-arrow-circle-up"></i></th>
            <th><i class="fa fa-arrow-circle-down"></i></th>
          </tr>
        </thead>
        <tbody id="result">
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
          <tr>
            <td>
              <div class="torrent-poster pull-left"><img src="{{ $movie->poster }}" data-poster-mid="{{ $movie->poster }}" class="img-tor-poster torrent-poster-img-small" alt="Poster"></div>
            </td>
            <td>
              <center>
                  @if($t->category_id == "1")
                  <i class="fa fa-film torrent-icon" data-toggle="tooltip" title="" data-original-title="Movie Torrent"></i>
                  @elseif($t->category_id == "2")
                  <i class="fa fa-tv torrent-icon" data-toggle="tooltip" title="" data-original-title="TV-Show Torrent"></i>
                  @else
                  <i class="fa fa-film torrent-icon" data-toggle="tooltip" title="" data-original-title="Movie Torrent"></i>
                  @endif
                <br>
                <br>
                <span class="label label-success">{{ $t->type }}</span>
              </center>
            </td>
            <td>
              <a class="view-torrent" data-id="{{ $t->id }}" data-slug="{{ $t->slug }}" href="{{ route('torrent', array('slug' => $t->slug, 'id' => $t->id)) }}" data-toggle="tooltip" title="" data-original-title="{{ $t->name }}">{{ $t->name }}</a>
              <a href="{{ route('download', array('slug' => $t->slug, 'id' => $t->id)) }}">&nbsp;&nbsp;
                <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip" title="" data-original-title="DOWNLOAD!"><i class="livicon" data-name="download" data-size="18" data-color="white" data-hc="white" data-l="true"></i></button>
              </a>
              <br>
              <strong>
              @if ($t->anon == 1)
              <span class="badge-extra text-bold">
              <i class="fa fa-upload"></i> By ANONYMOUS USER
              @if ($user->id == $t->user->id || $user->group->is_modo)
              <a href="{{ route('profile', ['username' => $t->user->username, 'id' => $t->user->id]) }}">({{ $t->user->username }})</a>
              </span>
              @endif
              @else
              <span class="badge-extra text-bold">
              <i class="fa fa-upload"></i> By
              <a href="{{ route('profile', ['username' => $t->user->username, 'id' => $t->user->id]) }}">{{ $t->user->username }}</a>
              </span>
              @endif

              @if ($user->ratings == 1)
              <a rel="nofollow" href="http://www.imdb.com/title/tt{{ $t->imdb }}">
              <span class="badge-extra text-bold">
                <span class="text-gold movie-rating-stars">
                  <i class="fa fa-star" data-toggle="tooltip" title="" data-original-title="View More"></i>
                </span>
                {{ $movie->imdbRating }}/10 ({{ $movie->imdbVotes }} votes)
              </span>
              </a>
              @else
              @if ($t->category_id == '2')
              <a rel="nofollow" href="https://www.themoviedb.org/tv/34307">
              <span class="badge-extra text-bold">
                <span class="text-gold movie-rating-stars">
                  <i class="fa fa-star" data-toggle="tooltip" title="" data-original-title="View More"></i>
                </span>
                {{ $movie->tmdbRating }}/10 ({{ $movie->tmdbVotes }} votes)
              </span>
              </a>
              @else
              <a rel="nofollow" href="https://www.themoviedb.org/movie/34307">
              <span class="badge-extra text-bold">
                <span class="text-gold movie-rating-stars">
                  <i class="fa fa-star" data-toggle="tooltip" title="" data-original-title="View More"></i>
                </span>
                {{ $movie->tmdbRating }}/10 ({{ $movie->tmdbVotes }} votes)
              </span>
              </a>
              @endif
              @endif
              <span class="badge-extra text-bold text-pink"><i class="fa fa-heart" data-toggle="tooltip" title="" data-original-title="Thanks Given"></i> {{ $t->thanks()->count() }}</span>
              @if($t->stream == "1")<span class="badge-extra text-bold"><i class="fa fa-play text-red" data-toggle="tooltip" title="" data-original-title="Stream Optimized"></i> Stream Optimized</span> @endif
              @if($t->doubleup == "1")<span class="badge-extra text-bold"><i class="fa fa-diamond text-green" data-toggle="tooltip" title="" data-original-title="Double upload"></i> Double Upload</span> @endif
              @if($t->free == "1")<span class="badge-extra text-bold"><i class="fa fa-star text-gold" data-toggle="tooltip" title="" data-original-title="100% Free"></i> 100% Free</span> @endif
              @if(config('other.freeleech') == true)<span class="badge-extra text-bold"><i class="fa fa-globe text-blue" data-toggle="tooltip" title="" data-original-title="Global FreeLeech"></i> Global FreeLeech</span> @endif
              @if(config('other.doubleup') == true)<span class="badge-extra text-bold"><i class="fa fa-globe text-green" data-toggle="tooltip" title="" data-original-title="Double Upload"></i> Global Double Upload</span> @endif
              @if($t->leechers >= "5") <span class="badge-extra text-bold"><i class="fa fa-fire text-orange" data-toggle="tooltip" title="" data-original-title="Hot!"></i> Hot!</span> @endif
              @if($t->sticky == 1) <span class="badge-extra text-bold"><i class="fa fa-thumb-tack text-black" data-toggle="tooltip" title="" data-original-title="Sticky!"></i> Sticky!</span> @endif
              @if($user->updated_at->getTimestamp() < $t->created_at->getTimestamp()) <span class="badge-extra text-bold"><i class="fa fa-magic text-black" data-toggle="tooltip" title="" data-original-title="NEW!"></i> NEW!</span> @endif
              @if($t->highspeed == 1)<span class="badge-extra text-bold"><i class="fa fa-tachometer text-red" data-toggle="tooltip" title="" data-original-title="High Speeds!"></i> High Speeds!</span> @endif
              </strong>
            </td>

            <td>
              <time datetime="{{ date('Y-m-d H:m:s', strtotime($t->created_at)) }}">{{$t->created_at->diffForHumans()}}</time>
            </td>
            <td><span class="badge-extra text-blue text-bold">{{ $t->getSize() }}</span></td>
            <td><span class="badge-extra text-orange text-bold">{{ $t->times_completed }} {{ trans('common.times') }}</span></td>
            <td><span class="badge-extra text-green text-bold">{{ $t->seeders }}</span></td>
            <td><span class="badge-extra text-red text-bold">{{ $t->leechers }}</span></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @endif
</div>
@endsection
