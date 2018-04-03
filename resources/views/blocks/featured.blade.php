<style>
.movie-card {
  background-size: contain;
  background-position: 0% 80%;
  background-repeat: no-repeat;
  height: 300px;
  display: block;
}

.color-overlay {
  width: 100%;
  height: 100%;
  background: -webkit-linear-gradient(left, rgba(42, 159, 255, 0.2) 0%, #212120 60%, #212120 100%);
  background: linear-gradient(to right, rgba(42, 159, 255, 0.2), #212120 39.5%, rgba(10, 10, 10, 0.89));
  background-blend-mode: multiply;
}

.movie-content {
  width: 60%;
  display: block;
  position: relative;
  float: right;
  padding-right: 1em;
}
.movie-content .movie-title {
  color: #ffffff;
  margin-bottom: .25em;
  opacity: .75;
}
.movie-content .movie-info {
  text-transform: uppercase;
  letter-spacing: 2px;
  font-size: .8em;
  color: #2a9fff;
  line-height: 1;
  margin: 0;
  font-weight: 700;
  opacity: .5;
}
.movie-content .movie-header {
  margin-bottom: 2em;
}
.movie-content .movie-desc {
  font-weight: 300;
  opacity: .84;
  margin-bottom: 2em;
}
@media only screen and (max-width: 720px) {
   .mobile-hide{ display: none !important; }
}
.carousel-inner {
  -webkit-transform-style: preserve-3d;
  -webkit-font-smoothing: subpixel-antialiased
}

.transition-timer-carousel-progress-bar {
    height: 3px;
    background-color: #5cb85c;
    width: 0%;
    margin: 0px 0px 0px 0px;
    border: none;
    z-index: 11;
    position: relative;
}
</style>

@php $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb')); @endphp
<div class="mobile-hide">
<div class="col-md-10 col-sm-10 col-md-offset-1">
  <div class="clearfix visible-sm-block"></div>
  <div class="panel panel-chat shoutbox">
    <div class="panel-heading">
      <h4>{{ trans('blocks.featured-torrents') }}</h4>
    </div>
      <div id="myCarousel" class="carousel slide" data-ride="carousel">

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
          <div class="item active">
            <div id="movie-card-list">
          <div class="movie-card" style="background-image: url('https://image.tmdb.org/t/p/original/6G2fLCVm9fiLyHvBrccq6GSe2ih.jpg');">
            <div class="color-overlay">
              <div class="movie-content">
                <div class="movie-header">
                  <h1 class="movie-title">{{ config('other.title') }} - {{ trans('blocks.featured-torrents') }}</h1>
                  <h4 class="movie-info">
                    {{ trans('blocks.featured-torrents-intro') }}
                    <br>
                    <br>
                    <span class="badge-user text-bold text-pink" style="background-image:url(https://i.imgur.com/F0UCb7A.gif);">{{ trans('torrent.freeleech') }}</span>
                    <span class="badge-user text-bold text-pink" style="background-image:url(https://i.imgur.com/F0UCb7A.gif);">{{ trans('torrent.double-upload') }}</span>
                  </h4>
                </div>
                <span class="movie-desc">

                </span>
              </div>
            </div>
          </div>
        </div>
        </div>
        @foreach($featured as $key => $feature)
        @if ($feature->torrent->category_id == 2)
            @if ($feature->torrent->tmdb || $feature->torrent->tmdb != 0)
            @php $movie = $client->scrape('tv', null, $feature->torrent->tmdb); @endphp
            @else
            @php $movie = $client->scrape('tv', 'tt'. $feature->torrent->imdb); @endphp
            @endif
        @else
            @if ($feature->torrent->tmdb || $feature->torrent->tmdb != 0)
            @php $movie = $client->scrape('movie', null, $feature->torrent->tmdb); @endphp
            @else
            @php $movie = $client->scrape('movie', 'tt'. $feature->torrent->imdb); @endphp
            @endif
        @endif
          <div class="item">
            <div id="movie-card-list">
              <div class="tags">
                {{ ++$key }}
              </div>
          <div class="movie-card" style="background-image: url({{ $movie->backdrop }});">
            <div class="color-overlay">
              <div class="movie-content">
                <div class="movie-header">
                  <a href="{{ route('torrent', ['slug' => $feature->torrent->slug, 'id' => $feature->torrent->id]) }}"><h1 class="movie-title">{{ $feature->torrent->name }}</h1></a>
                  <h4 class="movie-info">
                    @if($movie->genres)
                    @foreach($movie->genres as $genre)
                    | {{ $genre }} |
                    @endforeach
                    @endif
                  </h4>
                </div>
                <span class="movie-desc">
                  {{ str_limit(strip_tags($movie->plot), 200) }}...
                  <br>
                  <br>
                <ul class="list-inline">
                <span class="badge-extra text-blue"><i class="fa fa-database"></i> <strong>{{ trans('torrent.size') }}: </strong> {{ $feature->torrent->getSize() }}</span>
                <span class="badge-extra text-blue"><i class="fa fa-fw fa-calendar"></i> <strong>{{ trans('torrent.released') }}: </strong> {{ $feature->torrent->created_at->diffForHumans() }}</span>
                <span class="badge-extra text-green"><li><i class="fa fa-arrow-up"></i> <strong>{{ trans('torrent.seeders') }}: </strong> {{ $feature->torrent->seeders }}</li></span>
                <span class="badge-extra text-red"><li><i class="fa fa-arrow-down"></i> <strong>{{ trans('torrent.leechers') }}: </strong> {{ $feature->torrent->leechers }}</li></span>
                <span class="badge-extra text-orange"><li><i class="fa fa-check-square-o"></i> <strong>{{ trans('torrent.completed') }}: </strong> {{ $feature->torrent->times_completed }}</li></span>
                <br>
                <span class="badge-user text-bold text-pink" style="background-image:url(https://i.imgur.com/F0UCb7A.gif);">{{ trans('blocks.featured-until') }}: {{ $feature->created_at->addDay(7)->toFormattedDateString() }} ({{ $feature->created_at->addDay(7)->diffForHumans() }}!)</span>
                <span class="badge-user text-bold text-pink" style="background-image:url(https://i.imgur.com/F0UCb7A.gif);">{{ trans('blocks.featured-by') }}: {{ $feature->user->username }}!</span>
                </ul>
                </span>
              </div>
            </div>
          </div>
        </div>
        </div>
        @endforeach
        </div>

        <!-- Left and right controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left"></span>
          <span class="sr-only">{{ trans('common.previous') }}</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right"></span>
          <span class="sr-only">{{ trans('common.next') }}</span>
        </a>
      </div>
    </div>
  </div>
</div>
