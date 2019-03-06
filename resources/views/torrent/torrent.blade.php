@extends('layout.default')

@section('title')
    <title>{{ $torrent->name }} - @lang('torrent.torrents') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('torrent.meta-desc', ['name' => $torrent->name])!">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('torrents') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.torrents')</span>
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
        @if (! $torrent->category->no_meta)
            <div class="movie-wrapper">
                <div class="movie-backdrop" style="background-image: url({{ $meta->backdrop ?? 'https://via.placeholder.com/1400x800' }});">
                    <div class="tags">
                        {{ $torrent->category->name }}
                    </div>
                </div>
                <div class="movie-overlay"></div>
                <div class="container movie-container">
                    <div class="row movie-row ">
                        <div class="col-xs-12 col-sm-8 col-md-8 col-sm-push-4 col-md-push-3 movie-heading-box">
                            <h1 class="movie-heading">
                                @if ($meta->title)
                                <span class="text-bold">{{ $meta->title }}</span><span
                                        class="text-bold"><em> {{ $meta->releaseYear }}</em></span>
                                @else
                                    <span class="text-bold">@lang('torrent.no-meta')</span>
                                @endif
                                @if ($meta->imdbRating || $meta->tmdbRating)
                                <span class="badge-user text-bold text-gold">@lang('torrent.rating'):
                    <span class="movie-rating-stars">
                      <i class="{{ config('other.font-awesome') }} fa-star"></i>
                    </span>
                                    @if ($user->ratings == 1)
                                        {{ $meta->imdbRating }}/10({{ $meta->imdbVotes }} @lang('torrent.votes')
                                        )
                                    @else
                                        {{ $meta->tmdbRating }}/10({{ $meta->tmdbVotes }} @lang('torrent.votes')
                                        )
                                    @endif
                 </span>
                                    @endif
                            </h1>
                            <br>
                            <span class="movie-overview">
                            {{ $meta->plot }}
                        </span>
                            <ul class="movie-details">
                                <li>
                                    @if ($meta->genres)
                                        @foreach ($meta->genres as $genre)
                                            <span class="badge-user text-bold text-green">{{ $genre }}</span>
                                        @endforeach
                                    @endif
                                    @if ($meta->rated )
                                    <span class="badge-user text-bold text-orange">@lang('torrent.rated')
                                        : {{ $meta->rated }} </span>
                                        @endif
                                        @if ($meta->runtime )
                                        <span class="badge-user text-bold text-orange">@lang('torrent.runtime')
                                        : {{ $meta->runtime }} @lang('common.minute')@lang('common.plural-suffix')</span>
                                            @endif
                                </li>
                                <li>
                                    @if ($torrent->imdb != 0 && $torrent->imdb != null)
                  <span class="badge-user text-bold text-orange">
                    <a href="https://www.imdb.com/title/tt{{ $torrent->imdb }}" title="IMDB"
                       target="_blank">IMDB: {{ $torrent->imdb }}</a>
                  </span>
                                    @endif
                                    @if ($torrent->category_id == "2" && $torrent->tmdb != 0 && $torrent->tmdb != null)
                                        <span class="badge-user text-bold text-orange">
                      <a href="https://www.themoviedb.org/tv/{{ $meta->tmdb }}"
                         title="TheMovieDatabase" target="_blank">TMDB: {{ $meta->tmdb }}</a>
                    </span>
                                    @elseif ($torrent->tmdb != 0 && $torrent->tmdb != null)
                                        <span class="badge-user text-bold text-orange">
                      <a href="https://www.themoviedb.org/movie/{{ $meta->tmdb }}"
                         title="TheMovieDatabase" target="_blank">TMDB: {{ $meta->tmdb }}</a>
                    </span>
                                    @endif
                                    @if ($torrent->mal != 0 && $torrent->mal != null)
                                        <span class="badge-user text-bold text-pink">
                      <a href="https://myanimelist.net/anime/{{ $torrent->mal }}"
                         title="MAL" target="_blank">MAL: {{ $torrent->mal }}</a>
                    </span>
                                    @endif
                                    @if ($torrent->category_id == "2" && $torrent->tvdb != 0 && $torrent->tvdb != null)
                                        <span class="badge-user text-bold text-pink">
                      <a
                         href="https://www.thetvdb.com/?tab=series&id={{ $torrent->tvdb }}" title="TVDB"
                         target="_blank">TVDB: {{ $torrent->tvdb }}</a>
                    </span>
                                    @endif
                                    @if ($meta->videoTrailer != '')
                                        <span onclick="showTrailer()" style="cursor: pointer;"
                                              class="badge-user text-bold">
                            <a class="text-pink" title="@lang('torrent.trailer')">@lang('torrent.trailer') <i
                                        class="{{ config('other.font-awesome') }} fa-external-link"></i></a>
                        </span>
                                    @endif
                                </li>
                                <li>
                                    <div class="row cast-list">
                                        @if ($meta->actors)
                                            @php $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb')); @endphp
                                            @foreach (array_slice($meta->actors, 0,6) as $actor)
                                                @php $person = $client->person($actor->tmdb); @endphp
                                                <div class="col-xs-4 col-md-2 text-center">
                                                    <img class="img-people" src="{{ $person->photo }}">
                                                    <a
                                                       href="https://www.themoviedb.org/person/{{ $actor->tmdb }}"
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
                            <img src="{{ $meta->poster ?? 'https://via.placeholder.com/600x900' }}" class="movie-poster img-responsive hidden-xs">
                        </div>
                    </div>
                </div>
            </div>
        @endif

    <!-- Info -->
        <div class="table-responsive">
            <table class="table table-condensed table-bordered table-striped">
                <div class="text-center">
                <span class="badge-user" style=" margin: 0; width: 100%; margin-bottom: 25px; background-color: rgba(0, 0, 0, 0.19);">
                    @if (config('torrent.download_check_page') == 1)
                        <a href="{{ route('download_check', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                           role="button" class="btn btn-labeled btn-success">
                            <span class='btn-label'>
                                <i class='{{ config("other.font-awesome") }} fa-download'></i> @lang('common.download')
                            </span>
                        </a>
                    @else
                        <a href="{{ route('download', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                           role="button" class="btn btn-labeled btn-success">
                            <span class='btn-label'>
                                <i class='{{ config("other.font-awesome") }} fa-download'></i> @lang('common.download')
                            </span>
                        </a>
                    @endif
                    @if ($torrent->tmdb != 0)
                        <a href="{{ route('torrents.similar', ['tmdb' => $torrent->tmdb]) }}"
                           role="button"
                           class="btn btn-labeled btn-primary">
          <span class='btn-label'><i class='{{ config("other.font-awesome") }} fa-file'></i></span> @lang('torrent.similar')</a>
                    @endif
                    @if ($torrent->nfo != null)
                        <button class="btn btn-labeled btn-primary" data-toggle="modal" data-target="#modal-10">
          <span class='btn-label'><i class='{{ config("other.font-awesome") }} fa-file'></i></span> @lang('common.view') NFO</button>
                    @endif
                    <a href="{{ route('comment_thanks', ['id' => $torrent->id]) }}" role="button"
                       class="btn btn-labeled btn-primary">
          <span class='btn-label'><i class='{{ config("other.font-awesome") }} fa-heart'></i></span> @lang('torrent.quick-comment')</a>
        <a data-toggle="modal" href="#myModal" role="button" class="btn btn-labeled btn-primary">
          <span class='btn-label'><i class='{{ config("other.font-awesome") }} fa-file'></i></span> @lang('torrent.show-files')</a>

            <bookmark :id="{{ $torrent->id }}" :state="{{ $torrent->bookmarked()  ? 1 : 0}}"></bookmark>

                    @if ($torrent->seeders <= 2)
                        <a href="{{ route('reseed', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" role="button"
                           class="btn btn-labeled btn-warning">
          <span class='btn-label'><i class='{{ config("other.font-awesome") }} fa-envelope'></i></span> @lang('torrent.request-reseed')</a>
                    @endif
                    <button class="btn btn-labeled btn-danger" data-toggle="modal" data-target="#modal_torrent_report">
          <span class="btn-label"><i
                      class="{{ config('other.font-awesome') }} fa-fw fa-eye"></i></span> @lang('common.report') @lang('torrent.torrent')</button>
      </span>
                </div>
                <tbody>
                @if ($torrent->featured == 0)
                    <tr class="success">
                        <td><strong>@lang('torrent.discounts')</strong></td>
                        <td>
                            @if ($torrent->doubleup == "1" || $torrent->free == "1" || config('other.freeleech') == true || config('other.doubleup') == true || $personal_freeleech || $user->group->is_freeleech == 1 || $freeleech_token)
                                @if ($freeleech_token)<span class="badge-extra text-bold"><i
                                            class="{{ config('other.font-awesome') }} fa-coins text-bold" data-toggle="tooltip"
                                            data-original-title="@lang('common.fl_token')"></i> @lang('common.fl_token')</span> @endif
                                @if ($user->group->is_freeleech == 1)<span class="badge-extra text-bold"><i
                                            class="{{ config('other.font-awesome') }} fa-trophy text-purple" data-toggle="tooltip"
                                            data-original-title="@lang('common.special') @lang('torrent.freeleech')"></i> @lang('common.special') @lang('torrent.freeleech')</span> @endif
                                @if ($personal_freeleech)<span class="badge-extra text-bold"><i
                                            class="{{ config('other.font-awesome') }} fa-id-badge text-orange" data-toggle="tooltip"
                                            data-original-title="@lang('common.personal') @lang('torrent.freeleech')"></i> @lang('common.personal') @lang('torrent.freeleech')</span> @endif
                                @if ($torrent->doubleup == "1")<span class="badge-extra text-bold"><i
                                            class="{{ config('other.font-awesome') }} fa-gem text-green" data-toggle="tooltip"
                                            data-original-title="@lang('torrent.double-upload')"></i> @lang('torrent.double-upload')</span> @endif
                                @if ($torrent->free == "1")<span class="badge-extra text-bold"><i
                                            class="{{ config('other.font-awesome') }} fa-star text-gold" data-toggle="tooltip"
                                            data-original-title="100% @lang('common.free')"></i> 100% @lang('common.free')</span> @endif
                                @if (config('other.freeleech') == true)<span class="badge-extra text-bold"><i
                                            class="{{ config('other.font-awesome') }} fa-globe text-blue" data-toggle="tooltip"
                                            data-original-title="@lang('common.global') @lang('torrent.freeleech')"></i> @lang('common.global') @lang('torrent.freeleech')</span> @endif
                                @if (config('other.doubleup') == true)<span class="badge-extra text-bold"><i
                                            class="{{ config('other.font-awesome') }} fa-globe text-green" data-toggle="tooltip"
                                            data-original-title="@lang('common.global') {{ strtolower(trans('torrent.double-upload')) }}"></i> @lang('common.global') {{ strtolower(trans('torrent.double-upload')) }}</span> @endif
                            @else
                                <span class="text-bold text-danger"><i
                                            class="{{ config('other.font-awesome') }} fa-frown"></i> @lang('torrent.no-discounts')</span>
                            @endif
                        </td>
                    </tr>
                    @if ($torrent->free == "0" && config('other.freeleech') == false && !$personal_freeleech && $user->group->is_freeleech == 0 && !$freeleech_token)
                        <tr>
                            <td><strong>@lang('common.fl_token')</strong></td>
                            <td>
                                <a href="{{ route('freeleech_token', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                                   class="btn btn-default btn-xs"
                                   role="button">@lang('torrent.use-fl-token')
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

                @if ($torrent->featured == 1)
                    <tr class="info">
                        <td><strong>@lang('torrent.featured')</strong></td>
                        <td>
                            <span class="badge-user text-bold text-pink"
                                  style="background-image:url(https://i.imgur.com/F0UCb7A.gif);">@lang('torrent.featured-until') {{ $featured->created_at->addDay(7)->toFormattedDateString() }}
                                ({{ $featured->created_at->addDay(7)->diffForHumans() }}!)</span>
                            <span class="small"><em>{!! trans('torrent.featured-desc') !!}</em></span>
                        </td>
                    </tr>
                @endif

                <tr>
                    <td class="col-sm-2"><strong>@lang('torrent.name')</strong></td>
                    <td>{{ $torrent->name }} &nbsp; &nbsp;
                        @if (auth()->user()->group->is_modo || auth()->user()->id == $uploader->id)
                            <a class="btn btn-warning btn-xs" href="{{ route('edit_form', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" role="button">
                                <i class="{{ config('other.font-awesome') }} fa-pencil-alt"></i> @lang('common.edit')
                            </a>
                        @endif
                        @if (auth()->user()->group->is_modo || ( auth()->user()->id == $uploader->id && Carbon\Carbon::now()->lt($torrent->created_at->addDay())))
                            <button class="btn btn-danger btn-xs" data-toggle="modal"
                                    data-target="#modal_torrent_delete">
                                <i class="{{ config('other.font-awesome') }} fa-times"></i> @lang('common.delete')
                            </button>
                        @endif
                    </td>
                </tr>

                @if (auth()->user()->group->is_modo)
                <tr>
                    <td class="col-sm-2"><strong>@lang('common.moderation')</strong></td>
                    <td>
                        <a href="{{ route('moderation_approve', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                           role='button' class='btn btn-labeled btn-success btn-xs @if ($torrent->isApproved()) disabled @endif'>
                            <i class="{{ config('other.font-awesome') }} fa-thumbs-up"></i> @lang('common.moderation-approve')
                        </a>

                        <button data-target="#postpone-{{ $torrent->id }}" data-toggle="modal"
                                class="btn btn-labeled btn-warning btn-xs @if ($torrent->isPostponed()) disabled @endif">
                            <i class="{{ config('other.font-awesome') }} fa-pause"></i> @lang('common.moderation-postpone')
                        </button>

                        <button data-target="#reject-{{ $torrent->id }}" data-toggle="modal"
                                class="btn btn-labeled btn-danger btn-xs @if ($torrent->isRejected()) disabled @endif">
                            <i class="{{ config('other.font-awesome') }} fa-thumbs-down"></i> @lang('common.moderation-reject')
                        </button>

                        <span>
                            &nbsp;[ @lang('common.moderated-by')
                            <a href="{{ route('profile', ['username' => $torrent->moderated->username, 'id' => $torrent->moderated->id]) }}" style="color:{{ $torrent->moderated->group->color }};">
                                <i class="{{ $torrent->moderated->group->icon }}" data-toggle="tooltip" data-original-title="{{ $torrent->moderated->group->name }}"></i> {{ $torrent->moderated->username }}
                            </a>]
                        </span>
                    </td>
                </tr>
                @endif


                @if (auth()->user()->group->is_modo || auth()->user()->group->is_internal)
                <tr>
                    <td class="col-sm-2"><strong>Staff Tools</strong></td>
                    <td>
                        @if ($torrent->free == 0)
                            <a href="{{ route('torrent_fl', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" class="btn btn-success btn-xs" role="button">
                                <i class="{{ config('other.font-awesome') }} fa-star"></i> @lang('torrent.grant') @lang('torrent.freeleech')
                            </a>
                        @else
                            <a href="{{ route('torrent_fl', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" class="btn btn-danger btn-xs" role="button">
                                <i class="{{ config('other.font-awesome') }} fa-star"></i> @lang('torrent.revoke') @lang('torrent.freeleech')
                            </a>
                        @endif
                        @if ($torrent->doubleup == 0)
                            <a href="{{ route('torrent_doubleup', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" class="btn btn-success btn-xs" role="button">
                                <i class="{{ config('other.font-awesome') }} fa-chevron-double-up"></i> @lang('torrent.grant') @lang('torrent.double-upload')
                            </a>
                        @else
                            <a href="{{ route('torrent_doubleup', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" class="btn btn-danger btn-xs" role="button">
                                <i class="{{ config('other.font-awesome') }} fa-chevron-double-up"></i> @lang('torrent.revoke') @lang('torrent.double-upload')
                            </a>
                        @endif
                        @if ($torrent->sticky == 0)
                            <a href="{{ route('torrent_sticky', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" class="btn btn-success btn-xs" role="button">
                                <i class="{{ config('other.font-awesome') }} fa-thumbtack"></i> @lang('torrent.sticky')
                            </a>
                        @else
                            <a href="{{ route('torrent_sticky', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" class="btn btn-danger btn-xs" role="button">
                                <i class="{{ config('other.font-awesome') }} fa-thumbtack"></i> @lang('torrent.unsticky')
                            </a>
                        @endif
                            <a href="{{ route('bumpTorrent', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" class="btn btn-primary btn-xs" role="button">
                                <i class="{{ config('other.font-awesome') }} fa-arrow-to-top"></i> @lang('torrent.bump')
                            </a>
                        @if ($torrent->featured == 0)
                            <a href="{{ route('torrent_feature', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" class="btn btn-default btn-xs" role="button">
                                <i class="{{ config('other.font-awesome') }} fa-certificate"></i> @lang('torrent.feature')
                            </a>
                        @else
                            <a href="{{ route('torrent_feature', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" class="btn btn-default btn-xs disabled" role="button">
                                <i class="{{ config('other.font-awesome') }} fa-certificate"></i> @lang('torrent.featured')
                            </a>
                        @endif
                    </td>
                </tr>
                @endif

                <tr>
                    <td class="col-sm-2"><strong>@lang('torrent.uploader')</strong></td>
                    <td>
                        @if ($torrent->anon == 1)
                            <span class="badge-user text-orange text-bold">{{ strtoupper(trans('common.anonymous')) }} @if (auth()->user()->id == $uploader->id || auth()->user()->group->is_modo)
                                    <a href="{{ route('profile', ['username' => $uploader->username, 'id' => $uploader->id]) }}">({{ $uploader->username }}
                                        )</a>@endif</span>
                        @else
                            <a href="{{ route('profile', ['username' => $uploader->username, 'id' => $uploader->id]) }}"><span
                                        class="badge-user text-bold"
                                        style="color:{{ $uploader->group->color }}; background-image:{{ $uploader->group->effect }};"><i
                                            class="{{ $uploader->group->icon }}" data-toggle="tooltip"
                                            data-original-title="{{ $uploader->group->name }}"></i> {{ $uploader->username }}</span></a>
                        @endif
                        @if ($torrent->anon !== 1 && $uploader->private_profile !== 1)
                        @if (auth()->user()->isFollowing($uploader->id))
                            <a href="{{ route('unfollow', ['user' => $uploader->id]) }}"
                                id="delete-follow-{{ $uploader->target_id }}" class="btn btn-xs btn-info"
                                title="@lang('user.unfollow')">
                                <i class="{{ config('other.font-awesome') }} fa-user"></i> @lang('user.unfollow')
                             </a>
                        @else
                            <a href="{{ route('follow', ['user' => $uploader->id]) }}"
                                id="follow-user-{{ $uploader->id }}" class="btn btn-xs btn-success"
                                title="@lang('user.follow')">
                                <i class="{{ config('other.font-awesome') }} fa-user"></i> @lang('user.follow')
                            </a>
                        @endif
                        @endif
                        <a href="{{ route('torrentThank', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                           class="btn btn-xs btn-success" data-toggle="tooltip"
                           data-original-title="@lang('torrent.thank')">
                            <i class="{{ config('other.font-awesome') }} fa-heart"></i> @lang('torrent.thank')</a>
                        <span class="badge-extra text-pink"><i
                                    class="{{ config('other.font-awesome') }} fa-heart"></i> {{ $torrent->thanks()->count() }} @lang('torrent.thanks')</span>
                    </td>
                </tr>

                <tr>
                    <td class="col-sm-2"><strong>@lang('torrent.uploaded')</strong></td>
                    <td>{{ $torrent->created_at }} ({{ $torrent->created_at->diffForHumans() }})</td>
                </tr>

                <tr>
                    <td class="col-sm-2"><strong>@lang('torrent.size')</strong></td>
                    <td>{{ $torrent->getSize() }}</td>
                </tr>

                <tr>
                    <td class="col-sm-2"><strong>@lang('torrent.estimated-ratio')</strong></td>
                    <td>{{ $user->ratioAfterSizeString($torrent->size, $torrent->isFreeleech(auth()->user())) }}</td>
                </tr>

                <tr>
                    <td class="col-sm-2"><strong>@lang('torrent.category')</strong></td>
                    <td><i class="{{ $torrent->category->icon }} torrent-icon torrent-icon-small" data-toggle="tooltip"

                           data-original-title="{{ $torrent->category->name }} @lang('torrent.torrent')"></i> {{ $torrent->category->name }}
                    </td>
                </tr>

                <tr>
                    <td class="col-sm-2"><strong>@lang('torrent.type')</strong></td>
                    <td>{{ $torrent->type }}</td>
                </tr>

                <tr>
                    <td class="col-sm-2"><strong>@lang('torrent.stream-optimized')?</strong></td>
                    <td>
                        @if ($torrent->stream == "1") @lang('common.yes') @else @lang('common.no') @endif
                    </td>
                </tr>

                <tr>
                    <td class="col-sm-2"><strong>Info Hash</strong></td>
                    <td>{{ $torrent->info_hash }}</td>
                </tr>

                <tr>
                    <td class="col-sm-2"><strong>@lang('torrent.peers')</strong></td>
                    <td>
                        <span class="badge-extra text-green"><i
                                    class="{{ config('other.font-awesome') }} fa-fw fa-arrow-up"></i> {{ $torrent->seeders }}</span>
                        <span class="badge-extra text-red"><i
                                    class="{{ config('other.font-awesome') }} fa-fw fa-arrow-down"></i> {{ $torrent->leechers }}</span>
                        <span class="badge-extra text-info"><i
                                    class="{{ config('other.font-awesome') }} fa-fw fa-check"></i>{{ $torrent->times_completed }} {{ strtolower(trans('common.times')) }}</span>
                        <span class="badge-extra"><a
                                    href="{{ route('peers', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                                    title="@lang('common.view') @lang('torrent.peers')">@lang('common.view') @lang('torrent.peers')</a></span>
                        <span class="badge-extra"><a
                                    href="{{ route('history', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                                    title="@lang('common.view') @lang('torrent.history')">@lang('common.view') @lang('torrent.history')</a></span>
                    </td>
                </tr>

                @if ($torrent->seeders == 0)
                    <tr>
                        <td class="col-sm-2"><strong>@lang('torrent.last-seed-activity')</strong></td>
                        <td>
                            @if ($last_seed_activity)
                                <span class="badge-extra text-orange"><i
                                            class="{{ config('other.font-awesome') }} fa-fw fa-clock"></i> {{ $last_seed_activity->updated_at->diffForHumans() }}</span>
                            @else
                                <span class="badge-extra text-orange"><i
                                            class="{{ config('other.font-awesome') }} fa-fw fa-clock"></i> @lang('common.unknown')</span>
                            @endif
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        @if ($torrent->mediainfo != null)
            <div class="table-responsive">
                <table class="table table-condensed table-bordered table-striped">
                    <tbody>
                    <tr>
                        <td>
                            <div class="panel-body">
                                <div class="text-center"><span
                                            class="text-bold text-blue">@emojione(':blue_heart:') @lang('torrent.media-info')
                                        @emojione(':blue_heart:')</span></div>
                                <br>
                                @if ($general !== null && isset($general['file_name']))
                                    <span class="text-bold text-blue">@emojione(':file_folder:') {{ strtoupper(trans('torrent.file')) }}
                                        :</span>
                                    <span class="text-bold"><em>{{ $general['file_name'] }}</em></span>
                                    <br>
                                    <br>
                                @endif
                                @if ($general_crumbs !== null)
                                    <span class="text-bold text-blue">@emojione(':information_source:') {{ strtoupper(trans('torrent.general')) }}
                                        :</span>
                                    <span class="text-bold"><em>
                      @foreach ($general_crumbs as $crumb)
                                                {{ $crumb }}
                                                @if (!$loop->last)
                                                    /
                                                @endif
                                            @endforeach
                    </em></span>
                                    <br>
                                    <br>
                                @endif
                                @if ($video_crumbs !== null)
                                    @foreach ($video_crumbs as $key => $v)
                                        <span class="text-bold text-blue">@emojione(':projector:') {{ strtoupper(trans('torrent.video')) }}
                                            :</span>
                                        <span class="text-bold"><em>
                        @foreach ($v as $crumb)
                                                    {{ $crumb }}
                                                    @if (!$loop->last)
                                                        /
                                                    @endif
                                                @endforeach
                      </em></span>
                                        <br>
                                        <br>
                                    @endforeach
                                @endif
                                @if ($audio_crumbs !== null)
                                    @foreach ($audio_crumbs as $key => $a)
                                        <span class="text-bold text-blue">@emojione(':loud_sound:') {{ strtoupper(trans('torrent.audio')) }} {{ ++$key }}
                                            :</span>
                                        <span class="text-bold"><em>
                      @foreach ($a as $crumb)
                                                    {{ $crumb }}
                                                    @if (!$loop->last)
                                                        /
                                                    @endif
                                                @endforeach
                    </em></span>
                                        <br>
                                    @endforeach
                                @endif
                                <br>
                                @if ($text_crumbs !== null)
                                    @foreach ($text_crumbs as $key => $s)
                                        <span class="text-bold text-blue">@emojione(':speech_balloon:') {{ strtoupper(trans('torrent.subtitle')) }} {{ ++$key }}
                                            :</span>
                                        <span class="text-bold"><em>
                      @foreach ($s as $crumb)
                                                    {{ $crumb }}
                                                    @if (!$loop->last)
                                                        /
                                                    @endif
                                                @endforeach
                    </em></span>
                                        <br>
                                    @endforeach
                                @endif
                                @if ($settings)
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
                        <div class="panel-body">
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
                    <td class="col-sm-2"><strong>@lang('torrent.tip-jar')</strong></td>
                    <td>
                        <p>{!! trans('torrent.torrent-tips', ['total' => $total_tips, 'user' => $user_tips]) !!}.</p>
                        <span class="text-red text-bold">(@lang('torrent.torrent-tips-desc'))</span>
                        <form role="form" method="POST" action="{{ route('tip_uploader', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
                        @csrf
                        <input type="number" name="tip" value="0" placeholder="0" class="form-control">
                        <button type="submit" class="btn btn-primary">@lang('torrent.leave-tip')</button>
                        <br>
                        <br>
                        <span class="text-green text-bold">@lang('torrent.quick-tip')</span>
                        <br>
                        <button type="submit" value="10" name="tip" class="btn"><img src="/img/coins/10coin.png"/></button>
                        <button type="submit" value="20" name="tip" class="btn"><img src="/img/coins/20coin.png"/></button>
                        <button type="submit" value="50" name="tip" class="btn"><img src="/img/coins/50coin.png"/></button>
                        <button type="submit" value="100" name="tip" class="btn"><img src="/img/coins/100coin.png"/>
                        </button>
                        <button type="submit" value="200" name="tip" class="btn"><img src="/img/coins/200coin.png"/>
                        </button>
                        <button type="submit" value="500" name="tip" class="btn"><img src="/img/coins/500coin.png"/>
                        </button>
                        <button type="submit" value="1000" name="tip" class="btn"><img src="/img/coins/1000coin.png"/>
                        </button>
                        </form>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /Info-->

    @if ($movie->recommendations)
        <div class="torrent box container">
            <section class="recommendations">
                <div class="text-center">
                    <h2><u>Recommendations</u></h2>
                </div>
                <div class="scroller">
                    @foreach($movie->recommendations['results'] as $recommendation)
                        <div class="item mini backdrop mini_card">
                            <p class="tv flex">
                                <a href="{{ route('torrents.similar', ['tmdb' => $recommendation['id']]) }}">
                                    <span class="text-bold">{{  isset($recommendation['title']) ? $recommendation['title'] : $recommendation['name'] }}</span>
                                </a>
                            </p>
                            <div class="image_content">
                                <a href="{{ route('torrents.similar', ['tmdb' => $recommendation['id']]) }}">
                                    <img class="backdrop" src="https://image.tmdb.org/t/p/w1280{{ $recommendation['backdrop_path'] }}">
                                    <div class="meta">
                                        <span class="release_date"><i class="fas fa-calendar"></i> Year: {{ isset($recommendation['release_date']) ? substr($recommendation['release_date'], 0, 4) : substr($recommendation['first_air_date'], 0, 4) }}</span>
                                        <span class="vote_average"><i class="fas fa-star"></i> Rating: {{ $recommendation['vote_average'] }}</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    @endif

    <div class="torrent box container" id="comments">
        <!-- Comments -->
        <div class="clearfix"></div>
        <div class="row ">
            <div class="col-md-12 col-sm-12">
                <div class="panel panel-danger">
                    <div class="panel-heading border-light">
                        <h4 class="panel-title">
                            <i class="{{ config('other.font-awesome') }} fa-comment"></i> @lang('common.comments')
                        </h4>
                    </div>
                    <div class="panel-body no-padding">
                        <ul class="media-list comments-list">
                            @if (count($comments) == 0)
                                <div class="text-center"><h4 class="text-bold text-danger"><i
                                                class="{{ config('other.font-awesome') }} fa-frown"></i> @lang('common.no-comments')!</h4>
                                </div>
                            @else
                                @foreach ($comments as $comment)
                                    <li class="media" style="border-left: 5px solid #01BC8C">
                                        <div class="media-body">
                                            @if ($comment->anon == 1)
                                                <a href="#" class="pull-left" style="padding-right: 10px">
                                                    <img src="{{ url('img/profile.png') }}"
                                                         alt="{{ $comment->user->username }}" class="img-avatar-48">
                                                    <strong>{{ strtoupper(trans('common.anonymous')) }}</strong></a> @if (auth()->user()->id == $comment->user->id || auth()->user()->group->is_modo)
                                                    <a href="{{ route('profile', ['username' => $comment->user->username, 'id' => $comment->user->id]) }}" style="color:{{ $comment->user->group->color }}">(<span><i class="{{ $comment->user->group->icon }}"></i> {{ $comment->user->username }}</span>)</a> @endif
                                            @else
                                                <a href="{{ route('profile', ['username' => $comment->user->username, 'id' => $comment->user->id]) }}"
                                                   class="pull-left" style="padding-right: 10px">
                                                    @if ($comment->user->image != null)
                                                        <img src="{{ url('files/img/' . $comment->user->image) }}"
                                                             alt="{{ $comment->user->username }}" class="img-avatar-48"></a>
                                                @else
                                                    <img src="{{ url('img/profile.png') }}"
                                                         alt="{{ $comment->user->username }}" class="img-avatar-48"></a>
                                                @endif
                                                <strong><a
                                                            href="{{ route('profile', ['username' => $comment->user->username, 'id' => $comment->user->id]) }}" style="color:{{ $comment->user->group->color }}"><span><i class="{{ $comment->user->group->icon }}"></i> {{ $comment->user->username }}</span></a></strong> @endif
                                            <span class="text-muted"><small><em>{{ $comment->created_at->toDayDateTimeString() }} ({{ $comment->created_at->diffForHumans() }})</em></small></span>
                                            @if ($comment->user_id == auth()->id() || auth()->user()->group->is_modo)
                                                <a title="@lang('common.delete-comment')"
                                                   href="{{route('comment_delete',['comment_id'=>$comment->id])}}"><i
                                                            class="pull-right {{ config('other.font-awesome') }} fa fa-times" aria-hidden="true"></i></a>
                                                <a title="@lang('common.edit-comment')" data-toggle="modal"
                                                   data-target="#modal-comment-edit-{{ $comment->id }}"><i
                                                            class="pull-right {{ config('other.font-awesome') }} fa-pencil"
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

            <div class="clearfix"></div>
            <div class="col-md-12 home-pagination">
                <div class="text-center">{{ $comments->links() }}</div>
            </div>
            <br>

            <!-- Add comment -->
            <div class="col-md-12">
                <form role="form" method="POST" action="{{ route('comment_torrent', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
                @csrf
                <div class="form-group">
                    <label for="content">@lang('common.your-comment'):</label><span class="badge-extra">@lang('common.type')
                        <strong>:</strong> @lang('common.for') emoji</span> <span
                            class="badge-extra">BBCode @lang('common.is-allowed')</span>
                    <textarea id="content" name="content" cols="30" rows="5" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-danger">@lang('common.submit')</button>
                <label class="radio-inline"><strong>@lang('common.anonymous') @lang('common.comment')
                        :</strong></label>
                <input type="radio" value="1" name="anonymous"> @lang('common.yes')
                <input type="radio" value="0" checked="checked" name="anonymous"> @lang('common.no')
                </form>
            </div>
            <!-- /Add comment -->
        </div>
    </div>
    @include('torrent.torrent_modals', ['user' => $user, 'torrent' => $torrent])
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
      $(document).ready(function () {
        $('#content').wysibb({});
        emoji.textcomplete()
      })
    </script>

    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
      $(document).ready(function () {

        $('.slidingDiv').hide();
        $('.show_hide').show();

        $('.show_hide').click(function () {
          $('.slidingDiv').slideToggle()
        })

      })
    </script>

    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
      function showTrailer () {
        swal({
          showConfirmButton: false,
          showCloseButton: true,
          background: '#232323',
          width: 970,
          html: '<iframe width="930" height="523" src="{{ str_replace("watch?v=","embed/",$meta->videoTrailer) }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>',
          title: '<i style="color: #a5a5a5;">{{ $meta->title }}</i>',
          text: ''
        })
      }
    </script>

@endsection
