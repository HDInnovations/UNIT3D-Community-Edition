@extends('layout.default')

@section('title')
    <title>{{ $torrent->name }} - {{ trans('torrent.torrents') }} - {{ config('other.title') }}</title>
@endsection

@section('stylesheets')

@endsection

@section('meta')
    <meta name="description" content="{{ trans('torrent.meta-desc', ['name' => $torrent->name]) }}!">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('torrents') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.torrents') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $torrent->name }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="torrent box container">
        <div style="line-height: 15px;height:45px;width:100%;background: repeating-linear-gradient( 45deg,#D13A3A,#D13A3A 10px,#DF4B4B 10px,#DF4B4B 20px);border:solid 1px #B22929;-webkit-box-shadow: 0px 0px 6px #B22929;margin-bottom:-0px;margin-top:0px;font-family:Verdana;font-size:large;text-align:center;color:white">
            <br>{!! trans('torrent.say-thanks') !!}!
        </div>
        @if($torrent->category->meta == 1)
            <div class="movie-wrapper">
                <div class="movie-backdrop" style="background-image: url({{ $movie->backdrop ?? 'https://via.placeholder.com/1400x800' }});">
                    <div class="tags">
                        {{ $torrent->category->name }}
                    </div>
                </div>
                <div class="movie-overlay"></div>
                <div class="container movie-container">
                    <div class="row movie-row ">
                        <div class="col-xs-12 col-sm-8 col-md-8 col-sm-push-4 col-md-push-3 movie-heading-box">
                            <h1 class="movie-heading">
                                @if($movie->title)
                                <span class="text-bold">{{ $movie->title }}</span><span
                                        class="text-bold"><em> {{ $movie->releaseYear }}</em></span>
                                @else
                                    <span class="text-bold">Sorry Not Meta Found</span>
                                @endif
                                @if($movie->imdbRating || $movie->tmdbRating)
                                <span class="badge-user text-bold text-gold">{{ trans('torrent.rating') }}:
                    <span class="movie-rating-stars">
                      <i class="fa fa-star"></i>
                    </span>
                                    @if($user->ratings == 1)
                                        {{ $movie->imdbRating }}/10({{ $movie->imdbVotes }} {{ trans('torrent.votes') }}
                                        )
                                    @else
                                        {{ $movie->tmdbRating }}/10({{ $movie->tmdbVotes }} {{ trans('torrent.votes') }}
                                        )
                                    @endif
                 </span>
                                    @endif
                            </h1>
                            <br>
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
                                    @if($movie->rated )
                                    <span class="badge-user text-bold text-orange">{{ trans('torrent.rated') }}
                                        : {{ $movie->rated }} </span>
                                        @endif
                                        @if($movie->runtime )
                                        <span class="badge-user text-bold text-orange">{{ trans('torrent.runtime') }}
                                        : {{ $movie->runtime }} {{ trans('common.minute') }}{{ trans('common.plural-suffix') }}</span>
                                            @endif
                                </li>
                                <li>
                                    @if($torrent->imdb != 0 && $torrent->imdb != null)
                  <span class="badge-user text-bold text-orange">
                    <a rel="nofollow" href="https://anon.to?http://www.imdb.com/title/tt{{ $torrent->imdb }}" title="IMDB"
                       target="_blank">IMDB: {{ $torrent->imdb }}</a>
                  </span>
                                    @endif
                                    @if($torrent->category_id == "2" && $torrent->tmdb != 0 && $torrent->tmdb != null)
                                        <span class="badge-user text-bold text-orange">
                      <a rel="nofollow" href="https://anon.to?https://www.themoviedb.org/tv/{{ $movie->tmdb }}"
                         title="TheMovieDatabase" target="_blank">TMDB: {{ $movie->tmdb }}</a>
                    </span>
                                    @elseif($torrent->tmdb != 0 && $torrent->tmdb != null)
                                        <span class="badge-user text-bold text-orange">
                      <a rel="nofollow" href="https://anon.to?https://www.themoviedb.org/movie/{{ $movie->tmdb }}"
                         title="TheMovieDatabase" target="_blank">TMDB: {{ $movie->tmdb }}</a>
                    </span>
                                    @endif
                                    @if($torrent->mal != 0 && $torrent->mal != null)
                                        <span class="badge-user text-bold text-pink">
                      <a rel="nofollow" href="https://anon.to?https://myanimelist.net/anime/{{ $torrent->mal }}"
                         title="MAL" target="_blank">MAL: {{ $torrent->mal }}</a>
                    </span>
                                    @endif
                                    @if($torrent->category_id == "2" && $torrent->tvdb != 0 && $torrent->tvdb != null)
                                        <span class="badge-user text-bold text-pink">
                      <a rel="nofollow"
                         href="https://anon.to?https://www.thetvdb.com/?tab=series&id={{ $torrent->tvdb }}" title="TVDB"
                         target="_blank">TVDB: {{ $torrent->tvdb }}</a>
                    </span>
                                    @endif
                                    @if($movie->videoTrailer != '')
                                        <span onclick="showTrailer()" style="cursor: pointer;"
                                              class="badge-user text-bold">
                            <a class="text-pink" title="View Trailer">{{ trans('torrent.view-trailer') }} <i
                                        class="fa fa-external-link"></i></a>
                        </span>
                                    @endif
                                </li>
                                <li>
                                    <div class="row cast-list">
                                        @if($movie->actors)
                                            @php $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb')); @endphp
                                            @foreach(array_slice($movie->actors, 0,6) as $actor)
                                                @php $person = $client->person($actor->tmdb); @endphp
                                                <div class="col-xs-4 col-md-2 text-center">
                                                    <img class="img-circle" style="height:50px; width:50px;"
                                                         src="{{ $person->photo }}">
                                                    <a rel="nofollow"
                                                       href="https://anon.to?https://www.themoviedb.org/person/{{ $actor->tmdb }}"
                                                       title="TheMovieDatabase" target="_blank">
                                                        <span class="badge-user"
                                                              style="white-space:normal;"><strong>{{ $actor->name }}</strong></span>
                                                    </a>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3 col-sm-pull-8 col-md-pull-8">
                            <img src="{{ $movie->poster ?? 'https://via.placeholder.com/600x900' }}" class="movie-poster img-responsive hidden-xs">
                        </div>
                    </div>
                </div>
            </div>
        @endif
    <!-- Info -->
        <div class="table-responsive">
            <table class="table table-condensed table-bordered table-striped">
                <tbody>
                @if($torrent->featured == 0)
                    <tr class="success">
                        <td><strong>{{ trans('torrent.discounts') }}</strong></td>
                        <td>
                            @if($torrent->doubleup == "1" || $torrent->free == "1" || config('other.freeleech') == true || config('other.doubleup') == true || $personal_freeleech || $user->group->is_freeleech == 1 || $freeleech_token)
                                @if($freeleech_token)<span class="badge-extra text-bold"><i
                                            class="fa fa-viacoin text-bold" data-toggle="tooltip" title=""
                                            data-original-title="{{ trans('common.fl_token') }}"></i> {{ trans('common.fl_token') }}</span> @endif
                                @if($user->group->is_freeleech == 1)<span class="badge-extra text-bold"><i
                                            class="fa fa-trophy text-purple" data-toggle="tooltip" title=""
                                            data-original-title="{{ trans('common.special') }} {{ trans('torrent.freeleech') }}"></i> {{ trans('common.special') }} {{ trans('torrent.freeleech') }}</span> @endif
                                @if($personal_freeleech)<span class="badge-extra text-bold"><i
                                            class="fa fa-id-badge text-orange" data-toggle="tooltip" title=""
                                            data-original-title="{{ trans('common.personal') }} {{ trans('torrent.freeleech') }}"></i> {{ trans('common.personal') }} {{ trans('torrent.freeleech') }}</span> @endif
                                @if($torrent->doubleup == "1")<span class="badge-extra text-bold"><i
                                            class="fa fa-diamond text-green" data-toggle="tooltip" title=""
                                            data-original-title="{{ trans('torrent.double-upload') }}"></i> {{ trans('torrent.double-upload') }}</span> @endif
                                @if($torrent->free == "1")<span class="badge-extra text-bold"><i
                                            class="fa fa-star text-gold" data-toggle="tooltip" title=""
                                            data-original-title="100% {{ trans('common.free') }}"></i> 100% {{ trans('common.free') }}</span> @endif
                                @if(config('other.freeleech') == true)<span class="badge-extra text-bold"><i
                                            class="fa fa-globe text-blue" data-toggle="tooltip" title=""
                                            data-original-title="{{ trans('common.global') }} {{ trans('torrent.freeleech') }}"></i> {{ trans('common.global') }} {{ trans('torrent.freeleech') }}</span> @endif
                                @if(config('other.doubleup') == true)<span class="badge-extra text-bold"><i
                                            class="fa fa-globe text-green" data-toggle="tooltip" title=""
                                            data-original-title="{{ trans('common.global') }} {{ strtolower(trans('torrent.double-upload')) }}"></i> {{ trans('common.global') }} {{ strtolower(trans('torrent.double-upload')) }}</span> @endif
                            @else
                                <span class="text-bold text-danger"><i
                                            class="fa fa-frown-o"></i> {{ trans('torrent.no-discounts') }}</span>
                            @endif
                        </td>
                    </tr>
                    @if($torrent->free == "0" && config('other.freeleech') == false && !$personal_freeleech && $user->group->is_freeleech == 0 && !$freeleech_token)
                        <tr>
                            <td><strong>{{ trans('common.fl_token') }}</strong></td>
                            <td>
                                <a href="{{ route('freeleech_token', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                                   class="btn btn-default btn-xs"
                                   role="button">{{ trans('torrent.use-fl-token') }}
                                </a>
                                <span class="small">
                                    <em>{!! trans('torrent.fl-tokens-left', ['tokens' => $user->fl_tokens]) !!}
                                        !</strong>
                                    </em>
                                </span>
                            </td>
                        </tr>
                    @endif
                @endif

                @if($torrent->featured == 1)
                    <tr class="info">
                        <td><strong>{{ trans('torrent.featured') }}</strong></td>
                        <td>
                            <span class="badge-user text-bold text-pink"
                                  style="background-image:url(https://i.imgur.com/F0UCb7A.gif);">{{ trans('torrent.featured-until') }} {{ $featured->created_at->addDay(7)->toFormattedDateString() }}
                                ({{ $featured->created_at->addDay(7)->diffForHumans() }}!)</span>
                            <span class="small"><em>{!! trans('torrent.featured-desc') !!}</em></span>
                        </td>
                    </tr>
                @endif

                <tr>
                    <td class="col-sm-2"><strong>{{ trans('torrent.name') }}</strong></td>
                    <td>{{ $torrent->name }} &nbsp; &nbsp;
                        &nbsp; @if(auth()->user()->group->is_modo || auth()->user()->group->is_internal)
                            @if($torrent->free == 0)
                                <a href="{{ route('torrent_fl', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                                   class="btn btn-success btn-xs"
                                   role="button">{{ trans('torrent.grant') }} {{ trans('torrent.freeleech') }}</a>
                            @else
                                <a href="{{ route('torrent_fl', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                                   class="btn btn-danger btn-xs"
                                   role="button">{{ trans('torrent.revoke') }} {{ trans('torrent.freeleech') }}</a>
                            @endif
                            @if($torrent->doubleup == 0)
                                <a href="{{ route('torrent_doubleup', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                                   class="btn btn-success btn-xs"
                                   role="button">{{ trans('torrent.grant') }} {{ trans('torrent.double-upload') }}</a>
                            @else
                                <a href="{{ route('torrent_doubleup', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                                   class="btn btn-danger btn-xs"
                                   role="button">{{ trans('torrent.revoke') }} {{ trans('torrent.double-upload') }}</a>
                            @endif
                            @if($torrent->sticky == 0)
                                <a href="{{ route('torrent_sticky', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                                   class="btn btn-success btn-xs" role="button">{{ trans('torrent.sticky') }}</a>
                            @else
                                <a href="{{ route('torrent_sticky', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                                   class="btn btn-danger btn-xs" role="button">{{ trans('torrent.unsticky') }}</a>
                            @endif
                            <a href="{{ route('bumpTorrent', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                               class="btn btn-primary btn-xs" role="button">{{ trans('torrent.bump') }}</a>
                            @if($torrent->featured == 0)
                                <a href="{{ route('torrent_feature', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                                   class="btn btn-default btn-xs" role="button">{{ trans('torrent.feature') }}</a>
                            @else
                                <a href="{{ route('torrent_feature', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                                   class="btn btn-default btn-xs disabled"
                                   role="button">{{ trans('torrent.featured') }}</a>
                            @endif
                        @endif
                        @if(auth()->user()->group->is_modo || auth()->user()->id == $uploader->id)
                            <a class="btn btn-warning btn-xs"
                               href="{{ route('edit_form', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                               role="button">{{ trans('common.edit') }}</a>
                        @endif
                        @if(auth()->user()->group->is_modo || ( auth()->user()->id == $uploader->id && Carbon\Carbon::now()->lt($torrent->created_at->addDay())))
                            <button class="btn btn-danger btn-xs" data-toggle="modal"
                                    data-target="#modal_torrent_delete">
                                <span class="icon"><i class="fa fa-fw fa-times"></i> {{ trans('common.delete') }}</span>
                            </button>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td class="col-sm-2"><strong>{{ trans('torrent.uploader') }}</strong></td>
                    <td>
                        @if($torrent->anon == 1)
                            <span class="badge-user text-orange text-bold">{{ strtoupper(trans('common.anonymous')) }} @if(auth()->user()->id == $uploader->id || auth()->user()->group->is_modo)
                                    <a href="{{ route('profile', ['username' => $uploader->username, 'id' => $uploader->id]) }}">({{ $uploader->username }}
                                        )</a>@endif</span>
                        @else
                            <a href="{{ route('profile', ['username' => $uploader->username, 'id' => $uploader->id]) }}"><span
                                        class="badge-user text-bold"
                                        style="color:{{ $uploader->group->color }}; background-image:{{ $uploader->group->effect }};"><i
                                            class="{{ $uploader->group->icon }}" data-toggle="tooltip" title=""
                                            data-original-title="{{ $uploader->group->name }}"></i> {{ $uploader->username }}</span></a>
                        @endif
                        <a href="{{ route('torrentThank', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                           class="btn btn-xs btn-success pro-ajax" data-id="" data-toggle="tooltip" title=""
                           data-original-title="{{ trans('torrent.thank') }}">
                            <i class="fa fa-thumbs-up"></i> {{ trans('torrent.thank') }}</a>
                        <span class="badge-extra text-pink"><i
                                    class="fa fa-heart"></i> {{ $thanks }} {{ trans('torrent.thanks') }}</span>
                    </td>
                </tr>

                <tr>
                    <td class="col-sm-2"><strong>{{ trans('torrent.uploaded') }}</strong></td>
                    <td>{{ $torrent->created_at }} ({{ $torrent->created_at->diffForHumans() }})</td>
                </tr>

                <tr>
                    <td class="col-sm-2"><strong>{{ trans('torrent.size') }}</strong></td>
                    <td>{{ $torrent->getSize() }}</td>
                </tr>

                <tr>
                    <td class="col-sm-2"><strong>{{ trans('torrent.estimated-ratio') }}</strong></td>
                    <td>{{ $user->ratioAfterSizeString($torrent->size, $torrent->isFreeleech(auth()->user())) }}</td>
                </tr>

                <tr>
                    <td class="col-sm-2"><strong>{{ trans('torrent.category') }}</strong></td>
                    <td><i class="{{ $torrent->category->icon }} torrent-icon torrent-icon-small" data-toggle="tooltip"
                           title=""
                           data-original-title="{{ $torrent->category->name }} {{ trans('torrent.torrent') }}"></i> {{ $torrent->category->name }}
                    </td>
                </tr>

                <tr>
                    <td class="col-sm-2"><strong>{{ trans('torrent.type') }}</strong></td>
                    <td>{{ $torrent->type }}</td>
                </tr>

                <tr>
                    <td class="col-sm-2"><strong>{{ trans('torrent.stream-optimized') }}?</strong></td>
                    <td>
                        @if($torrent->stream == "1") {{ trans('common.yes') }} @else {{ trans('common.no') }} @endif
                    </td>
                </tr>

                <tr>
                    <td class="col-sm-2"><strong>Info Hash</strong></td>
                    <td>{{ $torrent->info_hash }}</td>
                </tr>

                <tr>
                    <td class="col-sm-2"><strong>{{ trans('torrent.peers') }}</strong></td>
                    <td>
                        <span class="badge-extra text-green"><i
                                    class="fa fa-fw fa-arrow-up"></i> {{ $torrent->seeders }}</span>
                        <span class="badge-extra text-red"><i
                                    class="fa fa-fw fa-arrow-down"></i> {{ $torrent->leechers }}</span>
                        <span class="badge-extra text-info"><i
                                    class="fa fa-fw fa-check"></i>{{ $torrent->times_completed }} {{ strtolower(trans('common.times')) }}</span>
                        <span class="badge-extra"><a
                                    href="{{ route('peers', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                                    title="View Torrent Peers">{{ trans('common.view') }} {{ trans('torrent.peers') }}</a></span>
                        <span class="badge-extra"><a
                                    href="{{ route('history', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                                    title="View Torrent History">{{ trans('common.view') }} {{ trans('torrent.history') }}</a></span>
                    </td>
                </tr>

                @if($torrent->seeders == 0)
                    <tr>
                        <td class="col-sm-2"><strong>{{ trans('torrent.last-seed-activity') }}</strong></td>
                        <td>
                            @if($last_seed_activity)
                                <span class="badge-extra text-orange"><i
                                            class="fa fa-fw fa-clock-o"></i> {{ $last_seed_activity->updated_at->diffForHumans() }}</span>
                            @else
                                <span class="badge-extra text-orange"><i
                                            class="fa fa-fw fa-clock-o"></i> {{ trans('common.unknown') }}</span>
                            @endif
                        </td>
                    </tr>
                @endif
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
                                <div class="text-center"><span
                                            class="text-bold text-blue">@emojione(':blue_heart:') {{ trans('torrent.media-info') }}
                                        @emojione(':blue_heart:')</span></div>
                                <br>
                                @if($general !== null && isset($general['file_name']))
                                    <span class="text-bold text-blue">@emojione(':name_badge:') {{ strtoupper(trans('torrent.file')) }}
                                        :</span>
                                    <span class="text-bold"><em>{{ $general['file_name'] }}</em></span>
                                    <br>
                                    <br>
                                @endif
                                @if($general_crumbs !== null)
                                    <span class="text-bold text-blue">@emojione(':information_source:') {{ strtoupper(trans('torrent.general')) }}
                                        :</span>
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
                                        <span class="text-bold text-blue">@emojione(':projector:') {{ strtoupper(trans('torrent.video')) }}
                                            :</span>
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
                                        <span class="text-bold text-blue">@emojione(':loud_sound:') {{ strtoupper(trans('torrent.audio')) }} {{ ++$key }}
                                            :</span>
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
                                    @foreach($text_crumbs as $key => $s)
                                        <span class="text-bold text-blue">@emojione(':speech_balloon:') {{ strtoupper(trans('torrent.subtitle')) }} {{ ++$key }}
                                            :</span>
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
                                @if($settings)
                                    <br>
                                    <span class="text-bold text-blue">@emojione(':gear:') {{ strtoupper(trans('torrent.encode-settings')) }}
                                        :</span>
                                    <br>
                                    <div class="decoda-code text-black">{{ $settings }}</div>
                                @endif
                                <br>
                                <br>
                                <div class="text-center">
                                    <button class="show_hide btn btn-labeled btn-primary" href="#">
                                        <span class="btn-label">@emojione(':poop:')</span>{{ strtoupper(trans('torrent.original-output')) }}
                                    </button>
                                </div>
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
                    <td class="col-sm-2"><strong>{{ trans('torrent.tip-jar') }}</strong></td>
                    <td>
                        <p>{!! trans('torrent.torrent-tips', ['total' => $total_tips, 'user' => $user_tips]) !!}.</p>
                        <span class="text-red text-bold">({{ trans('torrent.torrent-tips-desc') }})</span>
                        {{ Form::open(array('route' => array('tip_uploader', 'slug' => $torrent->slug, 'id' => $torrent->id))) }}
                        <input type="number" name="tip" value="0" placeholder="0" class="form-control">
                        <button type="submit" class="btn btn-primary">{{ trans('torrent.leave-tip') }}</button>
                        <br>
                        <br>
                        <span class="text-green text-bold">{{ trans('torrent.quick-tip') }}</span>
                        <br>
                        <button type="submit" value="10" name="tip" class="btn"><img src="/images/10coin.png"/></button>
                        <button type="submit" value="20" name="tip" class="btn"><img src="/images/20coin.png"/></button>
                        <button type="submit" value="50" name="tip" class="btn"><img src="/images/50coin.png"/></button>
                        <button type="submit" value="100" name="tip" class="btn"><img src="/images/100coin.png"/>
                        </button>
                        <button type="submit" value="200" name="tip" class="btn"><img src="/images/200coin.png"/>
                        </button>
                        <button type="submit" value="500" name="tip" class="btn"><img src="/images/500coin.png"/>
                        </button>
                        <button type="submit" value="1000" name="tip" class="btn"><img src="/images/1000coin.png"/>
                        </button>
                        {{ Form::close() }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!-- /Info-->

        <div class="torrent-bottom col-md-12">
            <div class="text-center">
        <span class="badge-user">
        <a href="{{ route('download_check', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" role="button"
           class="btn btn-labeled btn-success">
                <span class='btn-label'><i class='fa fa-download'></i></span>{{ trans('common.download') }}</a>
            @if($torrent->imdb != 0)
                <a href="{{ route('grouping_results', ['category_id' => $torrent->category_id, 'imdb' => $torrent->imdb]) }}"
                   role="button"
                   class="btn btn-labeled btn-primary">
          <span class='btn-label'><i class='fa fa-file'></i></span>Similar Torrents</a>
            @endif
            @if($torrent->nfo != null)
                <button class="btn btn-labeled btn-primary" data-toggle="modal" data-target="#modal-10">
          <span class='btn-label'><i class='fa fa-file'></i></span>{{ trans('common.view') }} NFO</button>
            @endif
            <a href="{{ route('comment_thanks', ['id' => $torrent->id]) }}" role="button"
               class="btn btn-labeled btn-primary">
          <span class='btn-label'><i class='fa fa-heart'></i></span>{{ trans('torrent.quick-comment') }}</a>
        <a data-toggle="modal" href="#myModal" role="button" class="btn btn-labeled btn-primary">
          <span class='btn-label'><i class='fa fa-file'></i></span>{{ trans('torrent.show-files') }}</a>

            <bookmark :id="{{ $torrent->id }}" :state="{{ $torrent->bookmarked()  ? 1 : 0}}"></bookmark>

            @if($torrent->seeders <= 2)
                <a href="{{ route('reseed', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" role="button"
                   class="btn btn-labeled btn-warning">
          <span class='btn-label'><i class='fa fa-envelope'></i></span>{{ trans('torrent.request-reseed') }}</a>
            @endif
            <button class="btn btn-labeled btn-danger" data-toggle="modal" data-target="#modal_torrent_report">
          <span class="btn-label"><i
                      class="fa fa-fw fa-eye"></i></span>{{ trans('common.report') }} {{ strtolower(trans('torrent.torrent')) }}</button>
      </span>
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
                            <i class="livicon" data-name="mail" data-size="18" data-color="white" data-hc="white"
                               data-l="true"></i> {{ trans('common.comments') }}
                        </h4>
                    </div>
                    <div class="panel-body no-padding">
                        <ul class="media-list comments-list">
                            @if(count($comments) == 0)
                                <div class="text-center"><h4 class="text-bold text-danger"><i
                                                class="fa fa-frown-o"></i> {{ trans('common.no-comments') }}!</h4>
                                </div>
                            @else
                                @foreach($comments as $comment)
                                    <li class="media" style="border-left: 5px solid #01BC8C">
                                        <div class="media-body">
                                            @if($comment->anon == 1)
                                                <a href="#" class="pull-left">
                                                    <img src="{{ url('img/profile.png') }}"
                                                         alt="{{ $comment->user->username }}" class="img-avatar-48">
                                                    <strong>{{ strtoupper(trans('common.anonymous')) }}</strong></a> @if(auth()->user()->id == $comment->user->id || auth()->user()->group->is_modo)
                                                    <a href="{{ route('profile', ['username' => $comment->user->username, 'id' => $comment->user->id]) }}">({{ $comment->user->username }}
                                                        )</a>@endif
                                            @else
                                                <a href="{{ route('profile', ['username' => $comment->user->username, 'id' => $comment->user->id]) }}"
                                                   class="pull-left">
                                                    @if($comment->user->image != null)
                                                        <img src="{{ url('files/img/' . $comment->user->image) }}"
                                                             alt="{{ $comment->user->username }}" class="img-avatar-48"></a>
                                                @else
                                                    <img src="{{ url('img/profile.png') }}"
                                                         alt="{{ $comment->user->username }}" class="img-avatar-48"></a>
                                                @endif
                                                <strong>{{ trans('common.author') }} <a
                                                            href="{{ route('profile', ['username' => $comment->user->username, 'id' => $comment->user->id]) }}">{{ $comment->user->username }}</a></strong> @endif
                                            <span class="text-muted"><small><em>{{$comment->created_at->diffForHumans() }}</em></small></span>
                                            @if($comment->user_id == auth()->id() || auth()->user()->group->is_modo)
                                                <a title="{{ trans('common.delete-comment') }}"
                                                   href="{{route('comment_delete',['comment_id'=>$comment->id])}}"><i
                                                            class="pull-right fa fa-lg fa-times" aria-hidden="true"></i></a>
                                                <a title="{{ trans('common.edit-comment') }}" data-toggle="modal"
                                                   data-target="#modal-comment-edit-{{ $comment->id }}"><i
                                                            class="pull-right fa fa-lg fa-pencil"
                                                            aria-hidden="true"></i></a>
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
                <div class="text-center">{{ $comments->links() }}</div>
            </div>
            <br>

            <!-- Add comment -->
            <div class="col-md-12">
                {{ Form::open(array('route' => array('comment_torrent', 'slug' => $torrent->slug, 'id' => $torrent->id))) }}
                <div class="form-group">
                    <label for="content">{{ trans('common.your-comment') }}:</label><span class="badge-extra">{{ trans('common.type') }}
                        <strong>:</strong> {{ trans('common.for') }} emoji</span> <span
                            class="badge-extra">BBCode {{ trans('common.is-allowed') }}</span>
                    <textarea id="content" name="content" cols="30" rows="5" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-danger">{{ trans('common.submit') }}</button>
                <label class="radio-inline"><strong>{{ trans('common.anonymous') }} {{ trans('common.comment') }}
                        :</strong></label>
                <input type="radio" value="1" name="anonymous"> {{ trans('common.yes') }}
                <input type="radio" value="0" checked="checked" name="anonymous"> {{ trans('common.no') }}
                {{ Form::close() }}
            </div>
            <!-- /Add comment -->
        </div>
    </div>
    @include('torrent.torrent_modals', ['user' => $user, 'torrent' => $torrent])
@endsection

@section('javascripts')
    <script>
      $(document).ready(function () {
        $('#content').wysibb({})
        emoji.textcomplete()
      })
    </script>

    <script>
      $(document).ready(function () {

        $('.slidingDiv').hide()
        $('.show_hide').show()

        $('.show_hide').click(function () {
          $('.slidingDiv').slideToggle()
        })

      })
    </script>

    <script type="text/javascript">
      function showTrailer () {
        swal({
          showConfirmButton: false,
          showCloseButton: true,
          background: '#232323',
          width: 970,
          html: '<iframe width="930" height="523" src="{{ str_replace("watch?v=","embed/",$movie->videoTrailer) }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>',
          title: '<i style="color: #a5a5a5;">{{ $movie->title }}</i>',
          text: ''
        })
      }
    </script>

@endsection
