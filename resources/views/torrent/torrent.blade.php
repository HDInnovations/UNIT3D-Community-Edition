@extends('layout.default')

@section('title')
<title>{{ $torrent->name }} - {{ trans('torrent.torrents') }} - {{ Config::get('other.title') }}</title>
@stop

@section('stylesheets')
<link rel="stylesheet" href="{{ url('files/wysibb/theme/default/wbbtheme.css') }}">
@stop

@section('meta')
<meta name="description" content="{{ 'Download ' . $torrent->name . ' at maximum speed!' }}">
@stop

@section('breadcrumb')
<li>
  <a href="{{ route('torrents') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.torrents') }}</span>
  </a>
</li>
<li class="active">
  <a href="{{ route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $torrent->name }}</span>
  </a>
</li>
@stop

@section('content')
<div class="torrent box container">
  <div style="line-height: 15px;height:45px;width:100%;background: repeating-linear-gradient( 45deg,#D13A3A,#D13A3A 10px,#DF4B4B 10px,#DF4B4B 20px);border:solid 1px #B22929;-webkit-box-shadow: 0px 0px 6px #B22929;margin-bottom:-0px;margin-top:0px;font-family:Verdana;font-size:large;text-align:center;color:white">
    <br>Please remember to say <b>thanks</b> and <b>seed</b> for as long as you can!</div>
  <div class="movie-wrapper">
    <div class="movie-backdrop" style="background-image: url({{ $movie->backdrop }});">
      <div class="tags">
        {{ $torrent->category->name }}
      </div>
    </div>
    <div class="movie-overlay"></div>
    <div class="container movie-container">
      <div class="row movie-row ">
        <div class="col-xs-12 col-sm-8 col-md-8 col-sm-push-4 col-md-push-3 movie-heading-box">
        	<h1 class="movie-heading">
        				<span class="text-bold">{{ $movie->title }}</span><span class="text-bold"><em> ({{ $movie->releaseYear }})</em></span>
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
        				</h1>
        				<span class="movie-overview">
                  {{ $movie->plot }}
        				</span>
        				<ul class="movie-details">
                  <li>
                    @if($movie->genres)
                    @foreach($movie->genres as $genre)
                    <span class="badge-user text-bold text-green">{{ $genre }}</span>
                    @endforeach
                    @endif
                    <span class="badge-user text-bold text-orange">Rated: {{ $movie->rated }} </span> <span class="badge-user text-bold text-orange">Runtime: {{ $movie->runtime }} minutes</span>
                  </li>
                  <li>
                  <span class="badge-user text-bold text-orange">
                    <a rel="nofollow" href="https://anon.to?http://www.imdb.com/title/{{ $movie->imdb }}" title="IMDB" target="_blank">IMDB: {{ $movie->imdb }}</a>
                  </span>
                    @if($torrent->category_id == "2")
                    <span class="badge-user text-bold text-orange">
                      <a rel="nofollow" href="https://anon.to?https://www.themoviedb.org/tv/{{ $movie->tmdb }}" title="TheMovieDatabase" target="_blank">TMDB: {{ $movie->tmdb }}</a>
                    </span>
                    @else
                    <span class="badge-user text-bold text-orange">
                      <a rel="nofollow" href="https://anon.to?https://www.themoviedb.org/movie/{{ $movie->tmdb }}" title="TheMovieDatabase" target="_blank">TMDB: {{ $movie->tmdb }}</a>
                    </span>
                    @endif
                    @if($torrent->mal != 0 && $torrent->mal != null)
                    <span class="badge-user text-bold text-pink">
                      <a rel="nofollow" href="https://anon.to?https://myanimelist.net/anime/{{ $torrent->mal }}" title="MAL" target="_blank">MAL: {{ $torrent->mal }}</a>
                    </span>
                    @endif
                    @if($torrent->category_id == "2" && $torrent->tvdb != 0 && $torrent->tvdb != null)
                    <span class="badge-user text-bold text-pink">
                      <a rel="nofollow" href="https://anon.to?https://www.thetvdb.com/?tab=series&id={{ $torrent->tvdb }}" title="TVDB" target="_blank">TVDB: {{ $torrent->tvdb }}</a>
                    </span>
                    @endif
                    <span class="badge-user text-bold text-pink">
                    <a href="{{ $movie->videoTrailer }}" title="View Trailer">View Trailer <i class="fa fa-external-link"></i></a>
                    </span>
                  </li>
                </ul>
                {{--<br>
                <br>
                <p>
                  <li>
                    <legend class="xsmall-legend strong-legend"></legend>
                    <div class="row cast-list">
                    @if($movie->actors)
                      @foreach(array_slice($movie->actors, 0,6) as $actor)
                        <div class="col-xs-4 col-md-2 text-center">
                          <a rel="nofollow" href="https://anon.to?https://www.themoviedb.org/person/{{ $actor->tmdb }}" title="TheMovieDatabase" target="_blank">
                            <img class="img-circle" style="height:100px; width:100px;" src="https://via.placeholder.com/100x100">
                            <span class="badge-user"><strong>{{ $actor->name }}</strong></span>
                          </a>
                        </div>
                      @endforeach
                      @endif
                      </div>
                    </li>
                </p>--}}
      			    </div>

      <div class="col-xs-12 col-sm-4 col-md-3 col-sm-pull-8 col-md-pull-8">
        <img src="{{ $movie->poster }}" class="movie-poster img-responsive hidden-xs">
        </div>
      </div>
    </div>
  </div>

  <!-- Info -->
  <div class="table-responsive">
    <table class="table table-condensed table-bordered table-striped">
      <tbody>
        @if($torrent->featured == 0)
        <tr class="success">
          <td><strong>Discounts</strong></td>
          <td>
            @if($torrent->doubleup == "1" || $torrent->free == "1" || config('other.freeleech') == true || config('other.doubleup') == true)
            @if($torrent->doubleup == "1")<span class="badge-extra text-bold"><i class="fa fa-diamond text-green" data-toggle="tooltip" title="" data-original-title="Double upload"></i> Double Upload</span> @endif
            @if($torrent->free == "1")<span class="badge-extra text-bold"><i class="fa fa-star text-gold" data-toggle="tooltip" title="" data-original-title="100% Free"></i> 100% Free</span> @endif
            @if(config('other.freeleech') == true)<span class="badge-extra text-bold"><i class="fa fa-globe text-blue" data-toggle="tooltip" title="" data-original-title="Global FreeLeech"></i> Global FreeLeech</span> @endif
            @if(config('other.doubleup') == true)<span class="badge-extra text-bold"><i class="fa fa-globe text-green" data-toggle="tooltip" title="" data-original-title="Double Upload"></i> Global Double Upload</span> @endif
            @else
            <span class="text-bold text-danger"><i class="fa fa-frown-o"></i> Currently No Discounts</span>
            @endif
          </td>
        </tr>
        @endif

        @if($torrent->featured == 1)
        <tr class="info">
          <td><strong>Featured</strong></td>
          <td>
            <span class="badge-user text-bold text-pink" style="background-image:url(https://i.imgur.com/F0UCb7A.gif);">This Is A Featured Torrent Until {{ $featured->created_at->addDay(7)->toFormattedDateString() }} ({{ $featured->created_at->addDay(7)->diffForHumans() }}!), Grab it while you can!</span>
            <span class="small"><em>Featured torrents are <i class="fa fa-star text-gold"></i><strong> 100% Free</strong> and <i class="fa fa-diamond text-green"></i><strong> Double Upload!</strong></em></span>
          </td>
        </tr>
        @endif

        <tr>
          <td class="col-sm-2"><strong>Filename</strong></td>
          <td>{{ $torrent->name }} &nbsp; &nbsp; &nbsp; @if(Auth::user()->group->is_modo)
            @if($torrent->free == 0)
            <a href="{{ route('torrent_fl', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" class="btn btn-success btn-xs" role="button">Grant FL</a>
            @else
            <a href="{{ route('torrent_fl', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" class="btn btn-danger btn-xs" role="button">Revoke FL</a>
            @endif
            @if($torrent->doubleup == 0)
            <a href="{{ route('torrent_doubleup', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" class="btn btn-success btn-xs" role="button">Grant DoubleUp</a>
            @else
            <a href="{{ route('torrent_doubleup', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" class="btn btn-danger btn-xs" role="button">Revoke DoubleUp</a>
            @endif
            @if($torrent->sticky == 0)
            <a href="{{ route('torrent_sticky', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" class="btn btn-success btn-xs" role="button">Sticky</a>
            @else
            <a href="{{ route('torrent_sticky', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" class="btn btn-danger btn-xs" role="button">UnSticky</a>
            @endif
            <a href="{{ route('bumpTorrent', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" class="btn btn-primary btn-xs" role="button">Bump Torrent</a>
            @if($torrent->featured == 0)
            <a href="{{ route('torrent_feature', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" class="btn btn-default btn-xs" role="button">Feature Torrent</a>
            @else
            <a href="{{ route('torrent_feature', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" class="btn btn-default btn-xs disabled" role="button">Already Featured</a>
            @endif
            @endif
            @if(Auth::user()->group->is_modo || Auth::user()->id == $uploader->id)
            <a class="btn btn-warning btn-xs" href="{{ route('edit', array('slug' => $torrent->slug, 'id' => $torrent->id)) }}" role="button">Edit</a>
            @endif
            @if(Auth::user()->group->is_modo || ( Auth::user()->id == $uploader->id && Carbon\Carbon::now()->lt($torrent->created_at->addDay())))
            <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal_torrent_delete">
              <span class="icon"><i class="fa fa-fw fa-times"></i> Delete</span>
            </button>
            @endif
          </td>
        </tr>

        <tr>
          <td class="col-sm-2"><strong>Uploader</strong></td>
          <td>
            @if($torrent->anon == 1)
            <span class="badge-user text-orange text-bold">ANONYMOUS @if(Auth::user()->id == $uploader->id || Auth::user()->group->is_modo)<a href="{{ route('profil', ['username' => $uploader->username, 'id' => $uploader->id]) }}">({{ $uploader->username }})</a>@endif</span>
            @else
            <span class="badge-user text-bold"><a href="{{ route('profil', ['username' => $uploader->username, 'id' => $uploader->id]) }}" style="color:{{ $uploader->group->color }};">{{ $uploader->username }}</a></span>
            @endif
            <a href="{{ route('torrentThank', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" class="btn btn-xs btn-success pro-ajax" data-id="" data-toggle="tooltip" title="" data-original-title="Thank Uploader">
              <i class="fa fa-thumbs-up"></i> Thank Uploader</a>
            <span class="badge-extra text-pink"><i class="fa fa-heart"></i> {{ $thanks }} Thanks</span>
          </td>
        </tr>

        <tr>
          <td class="col-sm-2"><strong>Uploaded On</strong></td>
          <td>{{ $torrent->created_at }} ({{ $torrent->created_at->diffForHumans() }})</td>
        </tr>

        <tr>
          <td class="col-sm-2"><strong>File Size</strong></td>
          <td>{{ $torrent->getSize() }}</td>
        </tr>

        <tr>
          <td class="col-sm-2"><strong>Estimated Ratio after Download</strong></td>
          <td>{{ $user->ratioAfterSizeString($torrent->size, $torrent->isFreeleech(Auth::user())) }}</td>
        </tr>

        <tr>
          <td class="col-sm-2"><strong>{{ trans('torrent.category') }}</strong></td>
          <td>
            @if($torrent->category_id == "1")
            <i class="fa fa-film torrent-icon torrent-icon-small" data-toggle="tooltip" title="" data-original-title="Movie Torrent"></i> {{ $torrent->category->name }}
            @elseif($torrent->category_id == "2")
            <i class="fa fa-tv torrent-icon torrent-icon-small" data-toggle="tooltip" title="" data-original-title="TV-Show Torrent"></i>{{ $torrent->category->name }}
            @else
            <i class="fa fa-video-camera torrent-icon torrent-icon-small" data-toggle="tooltip" title="" data-original-title="FANRES Torrent"></i> {{ $torrent->category->name }}
            @endif
        </tr>

        <tr>
          <td class="col-sm-2"><strong>{{ trans('torrent.type') }}</strong></td>
          <td>{{ $torrent->type }}</td>
        </tr>

        <tr>
          <td class="col-sm-2"><strong>Stream Optimized?</strong></td>
          <td>
            @if($torrent->stream == "1") YES @else NO @endif
          </td>
        </tr>

        <tr>
          <td class="col-sm-2"><strong>Info Hash</strong></td>
          <td>{{ $torrent->info_hash }}</td>
        </tr>

        <tr>
          <td class="col-sm-2"><strong>{{ trans('torrent.peers') }}</strong></td>
          <td>
            <span class="badge-extra text-green"><i class="fa fa-fw fa-arrow-up"></i> {{ $torrent->seeders }}</span>
            <span class="badge-extra text-red"><i class="fa fa-fw fa-arrow-down"></i> {{ $torrent->leechers }}</span>
            <span class="badge-extra text-info"><i class="fa fa-fw fa-check"></i>{{ $torrent->times_completed }} Times</span>
            <span class="badge-extra"><a href="{{ route('peers', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" title="View Torrent Peers">View {{ trans('torrent.peers') }}</a></span>
            <span class="badge-extra"><a href="{{ route('history', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" title="View Torrent History">View {{ trans('torrent.history') }}</a></span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  @if($torrent->mediainfo != null)
  <div class="table-responsive">
  <table class="table table-condensed table-bordered table-striped">
  <tbody>
        <tr>
          <td>
            <div class="panel-body torrent-desc">
                <center><span class="text-bold text-blue">@emojione(':blue_heart:') Media Info Output @emojione(':blue_heart:')</span></center>
                <br>
                @if($general !== null && isset($general['file_name']))
                  <span class="text-bold text-blue">@emojione(':name_badge:') FILE:</span>
                  <span class="text-bold"><em>{{ $general['file_name'] }}</em></span>
                  <br>
                  <br>
                @endif
                @if($general_crumbs !== null)
                  <span class="text-bold text-blue">@emojione(':information_source:') GENERAL:</span>
                  <span class="text-bold"><em>
                      @foreach($general_crumbs as $crumb)
                        {{ $crumb }}
                        @if(!$loop->last)
                          /
                        @endif
                      @endforeach
                    </em></span>
                  <br>
                  <br>
                @endif
                @if($video_crumbs !== null)
                  @foreach($video_crumbs as $key => $v)
                    <span class="text-bold text-blue">@emojione(':projector:') VIDEO:</span>
                    <span class="text-bold"><em>
                        @foreach($v as $crumb)
                          {{ $crumb }}
                          @if(!$loop->last)
                            /
                          @endif
                        @endforeach
                      </em></span>
                    <br>
                    <br>
                  @endforeach
                @endif
                @if($audio_crumbs !== null)
                  @foreach($audio_crumbs as $key => $a)
                  <span class="text-bold text-blue">@emojione(':loud_sound:') AUDIO {{ ++$key }}:</span>
                  <span class="text-bold"><em>
                      @foreach($a as $crumb)
                        {{ $crumb }}
                        @if(!$loop->last)
                          /
                        @endif
                      @endforeach
                    </em></span>
                  <br>
                  @endforeach
                @endif
                <br>
                @if($text_crumbs !== null)
                  @foreach($subtitle as $key => $s)
                  <span class="text-bold text-blue">@emojione(':speech_balloon:') SUBTITLE {{ ++$key }}:</span>
                  <span class="text-bold"><em>
                      @foreach($s as $crumb)
                          {{ $crumb }}
                          @if(!$loop->last)
                            /
                        @endif
                      @endforeach
                    </em></span>
                  <br>
                  @endforeach
                @endif
                {{--@if($settings)
                <br>
                <span class="text-bold text-blue">@emojione(':gear:') ENCODE SETTINGS:</span>
                <br>
                <div class="decoda-code text-black">{{ $settings }}</div>
                @endif--}}
                <br>
                <br>
                <center>
                <button class="show_hide btn btn-labeled btn-primary" href="#">
                  <span class="btn-label">@emojione(':poop:')</span>Show/Hide Original Dump</button>
                </center>
                <div class="slidingDiv">
                  <pre class="decoda-code"><code>{{ $torrent->mediainfo }}</code></pre>
              </div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  @endif

  <div class="table-responsive">
  <table class="table table-condensed table-bordered table-striped">
  <tbody>
        <tr>
          <td>
            <div class="panel-body torrent-desc">
              @emojione($torrent->getDescriptionHtml())
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

        <div class="table-responsive">
        <table class="table table-condensed table-bordered table-striped">
        <tbody>
        <tr>
          <td class="col-sm-2"><strong>Tip Jar</strong></td>
          <td>
            <p>In Total <span class="text-red text-bold">{{ $total_tips }}</span> BON has been tipped to the uploader, in which <span class="text-red text-bold">{{ $user_tips }}</span> are from you.</p>
              <span class="text-red text-bold">(This will be deducted from your available bonus points)</span>
            {{ Form::open(array('route' => array('tip_uploader', 'slug' => $torrent->slug, 'id' => $torrent->id))) }}
            <input type="number" name="tip" value="0" placeholder="0" class="form-control">
            <button type="submit" class="btn btn-primary">Leave Tip</button>
            <br>
            <br>
            <span class="text-green text-bold">Quick Tip Amounts</span>
            <br>
            <button type="submit" value="10" name="tip" class="btn"><img src="/images/10coin.png"/></button>
            <button type="submit" value="20" name="tip" class="btn"><img src="/images/20coin.png"/></button>
            <button type="submit" value="50" name="tip" class="btn"><img src="/images/50coin.png"/></button>
            <button type="submit" value="100" name="tip" class="btn"><img src="/images/100coin.png"/></button>
            <button type="submit" value="200" name="tip" class="btn"><img src="/images/200coin.png"/></button>
            <button type="submit" value="500" name="tip" class="btn"><img src="/images/500coin.png"/></button>
            <button type="submit" value="1000" name="tip" class="btn"><img src="/images/1000coin.png"/></button>
            {{ Form::close() }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <!-- /Info-->

  <div class="torrent-bottom col-md-12">
    <center>
        <span class="badge-user">
        <a href="{{ route('download_check', array('slug' => $torrent->slug, 'id' => $torrent->id)) }}" role="button" class="btn btn-labeled btn-success">
          <span class='btn-label'><i class='fa fa-download'></i></span>{{ trans('common.download') }}</a>
        <button class="btn btn-labeled btn-primary" data-toggle="modal" data-target="#modal-10">
          <span class='btn-label'><i class='fa fa-file'></i></span>View NFO</button>
        <a href="{{ route('comment_thanks', array('id' => $torrent->id)) }}" role="button" class="btn btn-labeled btn-primary">
          <span class='btn-label'><i class='fa fa-heart'></i></span>Quick Comment</a>
        <a data-toggle="modal" href="#myModal" role="button" class="btn btn-labeled btn-primary">
          <span class='btn-label'><i class='fa fa-file'></i></span>Show Files</a>
        <a href="{{ route('bookmark', ['id' => $torrent->id]) }}" class="btn btn-labeled btn-primary" role="button">
          <span class="btn-label"><i class="fa fa-fw fa-bookmark-o"></i></span>Bookmark</a>
        @if($torrent->seeders <= 2)
          <a href="{{ route('reseed', array('slug' => $torrent->slug, 'id' => $torrent->id)) }}" role="button" class="btn btn-labeled btn-warning">
          <span class='btn-label'><i class='fa fa-envelope'></i></span>Request Reseed</a>
        @endif
        <button class="btn btn-labeled btn-danger" data-toggle="modal" data-target="#modal_torrent_report">
          <span class="btn-label"><i class="fa fa-fw fa-eye"></i></span>Report Torrent</button>
      </span>
    </center>
  </div>
</div>

  <div class="torrent box container">
    <div class="panel-collapse">
    <div class="panel-heading collapsed" data-toggle="collapse" data-target="#collapseMovie" aria-expanded="false">
      <div class="movie-title">
        <center><i class="fa fa-arrow-right" aria-hidden="true"></i><span class="text-bold"> Similar Torrents For {{ $torrent->name }} </span><i class="fa fa-arrow-left" aria-hidden="true"></i></center>
      </div>
      </div>
    <div id="collapseMovie" class="panel-body collapse" aria-expanded="false" style="height: 30px;">
      @foreach($similar as $s)
      <div class="col-sm-12 movie-list">
        <h3 class="movie-title">
          <a href="{{ route('torrent', array('slug' => $s->slug, 'id' => $s->id)) }}" data-toggle="tooltip" title="" data-original-title="{{ $s->name }}"" title="{{ $s->name }}">{{ $s->name }}</a>
        </h3>
        <div class="movie-details">
        </div>
        <ul class="list-inline">
          <span class="badge-extra text-blue"><i class="fa fa-database"></i> <strong>Size: </strong> {{ $s->getSize() }}</span>
          <span class="badge-extra text-blue"><i class="fa fa-fw fa-calendar"></i> <strong>Released: </strong> {{ $s->created_at->diffForHumans() }}</span>
          <span class="badge-extra text-green"><li><i class="fa fa-arrow-up"></i> <strong>Seeders: </strong> {{ $s->seeders }}</li></span>
          <span class="badge-extra text-red"><li><i class="fa fa-arrow-down"></i> <strong>Leechers: </strong> {{ $s->leechers }}</li></span>
          <span class="badge-extra text-orange"><li><i class="fa fa-check-square-o"></i> <strong>Completed: </strong> {{ $s->times_completed }}</li></span>
        </ul>
      </div>
      @endforeach
    </div>
    </div>
  </div>

<div class="torrent box container">
  <!-- Comments -->
  <div class="clearfix"></div>
  <div class="row ">
    <div class="col-md-12 col-sm-12">
      <div class="panel panel-danger">
        <div class="panel-heading border-light">
          <h4 class="panel-title">
          <i class="livicon" data-name="mail" data-size="18" data-color="white" data-hc="white" data-l="true"></i> Comments
        </h4>
        </div>
        <div class="panel-body no-padding">
          <ul class="media-list comments-list">
          @if(count($comments) == 0)
          <center><h4 class="text-bold text-danger"><i class="fa fa-frown-o"></i> No Comments Yet!</h4></center>
          @else
          @foreach($comments as $comment)
          <li class="media" style="border-left: 5px solid #01BC8C">
            <div class="media-body">
            @if($comment->anon == 1)
            <a href="#" class="pull-left">
            <img src="{{ url('img/profil.png') }}" alt="{{ $comment->user->username }}" class="img-avatar-48">
            <strong>ANONYMOUS</strong></a> @if(Auth::user()->id == $comment->user->id || Auth::user()->group->is_modo)<a href="{{ route('profil', ['username' => $comment->user->username, 'id' => $comment->user->id]) }}">({{ $comment->user->username }})</a>@endif
            @else
            <a href="{{ route('profil', array('username' => $comment->user->username, 'id' => $comment->user->id)) }}" class="pull-left">
            @if($comment->user->image != null)
            <img src="{{ url('files/img/' . $comment->user->image) }}" alt="{{ $comment->user->username }}" class="img-avatar-48"></a>
            @else
            <img src="{{ url('img/profil.png') }}" alt="{{ $comment->user->username }}" class="img-avatar-48"></a>
            @endif
            <strong>By <a href="{{ route('profil', ['username' => $comment->user->username, 'id' => $comment->user->id]) }}">{{ $comment->user->username }}</a></strong> @endif
            <span class="text-muted"><small><em>{{$comment->created_at->diffForHumans() }}</em></small></span>
            @if($comment->user_id == Auth::id() || Auth::user()->group->is_modo)
            <a title="Delete your comment" href="{{route('comment_delete',['comment_id'=>$comment->id])}}"><i class="pull-right fa fa-lg fa-times" aria-hidden="true"></i></a>
            <a title="Edit your comment" data-toggle="modal" data-target="#modal-comment-edit-{{ $comment->id }}"><i class="pull-right fa fa-lg fa-pencil" aria-hidden="true"></i></a>
            @endif
            <div class="pt-5">
            @emojione($comment->getContentHtml())
            </div>
          </div>
          </li>
          @include('partials.modals', ['comment' => $comment])
          @endforeach
          @endif
          </ul>
        </div>
      </div>
    </div>
  <!-- /Comments -->

  <div class="col-md-12 home-pagination">
    <center>{{ $comments->links() }}</center>
  </div>
  <br>

  <!-- Add comment -->
  <div class="col-md-12">
    {{ Form::open(array('route' => array('comment_torrent', 'slug' => $torrent->slug, 'id' => $torrent->id))) }}
    <div class="form-group">
      <label for="content">Your comment:</label><span class="badge-extra">Type <strong>:</strong> for emoji</span> <span class="badge-extra">BBCode is allowed</span>
      <textarea id="content" name="content" cols="30" rows="5" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-danger">{{ trans('common.submit') }}</button>
    <label class="radio-inline"><strong>Anonymous Comment:</strong></label>
      <input type="radio" value="1" name="anonymous"> Yes
      <input type="radio" value="0" checked="checked" name="anonymous"> No
    {{ Form::close() }}
  </div>
  <!-- /Add comment -->
</div>
</div>
@include('torrent.torrent_modals', ['user' => $user, 'torrent' => $torrent])
@stop

@section('javascripts')
<script type="text/javascript" src="{{ url('files/wysibb/jquery.wysibb.js') }}"></script>
<script>
$(document).ready(function() {
    var wbbOpt = { }
    $("#content").wysibb(wbbOpt);
});
</script>

<script>
$(document).ready(function(){

$(".slidingDiv").hide();
$(".show_hide").show();

$('.show_hide').click(function(){
$(".slidingDiv").slideToggle();
});

});
</script>
@stop
