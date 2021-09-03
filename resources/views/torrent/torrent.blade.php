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
        <a href="{{ route('torrent', ['id' => $torrent->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $torrent->name }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div id="torrent-page">
        <div class="meta-wrapper box container" id="meta-info">
            @if ($torrent->category->movie_meta)
                @include('torrent.partials.movie_meta')
            @endif

            @if ($torrent->category->tv_meta)
               @include('torrent.partials.tv_meta')
            @endif

            @if ($torrent->category->game_meta)
                @include('torrent.partials.game_meta')
            @endif

	        @if ($torrent->category->no_meta)
		        @include('torrent.partials.no_meta')
	        @endif

                <div class="torrent-buttons">
                <div class="button-overlay"></div>
                <div class="vibrant-overlay"></div>
                <div class="button-block">
                    @if (file_exists(public_path().'/files/torrents/'.$torrent->file_name))
                    @if (config('torrent.download_check_page') == 1)
                        <a href="{{ route('download_check', ['id' => $torrent->id]) }}" role="button" class="down btn btn-sm btn-success">
                            <i class='{{ config("other.font-awesome") }} fa-download'></i> @lang('common.download')
                        </a>
                    @else
                        <a href="{{ route('download', ['id' => $torrent->id]) }}" role="button" class="down btn btn-sm btn-success">
                            <i class='{{ config("other.font-awesome") }} fa-download'></i> @lang('common.download')
                        </a>

                        @if ($torrent->free == "0" && config('other.freeleech') == false && !$personal_freeleech && $user->group->is_freeleech == 0 && !$freeleech_token)
                            <a href="{{ route('freeleech_token', ['id' => $torrent->id]) }}"
                                class="btn btn-default btn-sm torrent-freeleech-token"
                                data-toggle=tooltip
                                data-html="true"
                                title='{!! trans('torrent.fl-tokens-left', ['tokens' => $user->fl_tokens]) !!}!'
                                role="button"
                            >
                                @lang('torrent.use-fl-token')
                            </a>
                        @endif
                    @endif
                    @else
                        <a href="magnet:?dn={{ $torrent->name }}&xt=urn:btih:{{ $torrent->info_hash }}&as={{ route('torrent.download.rsskey', ['id' => $torrent->id, 'rsskey' => $user->rsskey ]) }}&tr={{ route('announce', ['passkey' => $user->passkey]) }}&xl={{ $torrent->size }}" role="button" class="down btn btn-sm btn-success">
                            <i class='{{ config("other.font-awesome") }} fa-magnet'></i> @lang('common.magnet')
                        </a>
                    @endif

                    @livewire('thank-button', ['torrent' => $torrent->id])

                    @if ($torrent->nfo != null)
                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-10">
                            <i class='{{ config("other.font-awesome") }} fa-info-circle'></i> NFO
                        </button>
                    @endif

                    <a href="{{ route('comment_thanks', ['id' => $torrent->id]) }}" role="button" class="btn btn-sm btn-primary">
                        <i class='{{ config("other.font-awesome") }} fa-heart'></i> @lang('torrent.quick-comment')
                    </a>

                    <a data-toggle="modal" href="#myModal" role="button" class="btn btn-sm btn-primary">
                        <i class='{{ config("other.font-awesome") }} fa-file'></i>  @lang('torrent.show-files')
                    </a>

                    @livewire('bookmark-button', ['torrent' => $torrent->id])

                    @if ($playlists->count() > 0)
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal_playlist_torrent">
                        <i class="{{ config('other.font-awesome') }} fa-list-ol"></i> @lang('torrent.add-to-playlist')
                    </button>
                    @endif

                    @if ($current = $user->history->where('info_hash', $torrent->info_hash)->first())
                        @if ($current->seeder == 0 && $current->active == 1 && $torrent->seeders <= 2)
                            <a href="{{ route('reseed', ['id' => $torrent->id]) }}" role="button" class="btn btn-sm btn-warning">
                                <i class='{{ config("other.font-awesome") }} fa-envelope'></i> @lang('torrent.request-reseed')
                            </a>
                        @endif
                    @endif

                    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal_torrent_report">
                        <i class="{{ config('other.font-awesome') }} fa-fw fa-eye"></i> @lang('common.report')
                    </button>

                    <a role="button" class="btn btn-sm btn-primary" href="{{ route('upload_form', ['category_id' => $torrent->category_id, 'title' => \rawurlencode($torrent->name) ?? 'Unknown', 'imdb' => $torrent->imdb, 'tmdb' => $torrent->tmdb]) }}">
                        <i class="{{ config('other.font-awesome') }} fa-upload"></i> @lang('common.upload')
                    </a>
                </div>
            </div>
        </div>

        <div class="meta-general box container">
            <div class="panel panel-chat shoutbox torrent-general">
                <div class="panel-heading">
                    <h4><i class="{{ config("other.font-awesome") }} fa-info"></i> @lang('torrent.general')</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-condensed table-bordered table-striped">
                        <tbody>
                            <tr class="success torrent-discounts">
                                <td>
                                    <strong>@lang('torrent.discounts')</strong>
                                </td>
                                <td>
                                    @if ($torrent->featured == '0')
                                        @if ($freeleech_token || $user->group->is_freeleech == '1' || $personal_freeleech || $torrent->free == '1' || config('other.freeleech') == '1' || $torrent->doubleup == '1' || $user->group->is_double_upload == '1' || config('other.doubleup') == '1')
                                            @if ($freeleech_token || $user->group->is_freeleech == '1' || $personal_freeleech || $torrent->free == '1' || config('other.freeleech') == '1')
                                                <span class="badge-extra" data-toggle="tooltip" data-html="true" title="
                                        
                                                    @if ($freeleech_token)
                                                        <p>@lang('common.fl_token')</p>
                                                    @endif
                        
                                                    @if ($user->group->is_freeleech == '1')
                                                        <p>@lang('common.special') @lang('torrent.freeleech')</p>
                                                    @endif
                        
                                                    @if ($personal_freeleech)
                                                        <p>@lang('common.personal') @lang('torrent.freeleech')</p>
                                                    @endif
                        
                                                    @if ($torrent->free == '1')
                                                        <p>100% @lang('common.free')</p>
                                                    @endif
                        
                                                    @if (config('other.freeleech') == '1')
                                                        <p>@lang('common.global') @lang('torrent.freeleech')</p>
                                                    @endif

                                                    "
                                                >
                                                    <i class="{{ config('other.font-awesome') }} fa-star text-gold"></i>
                                                </span>
                                            @endif

                                            @if ($torrent->doubleup == '1' || $user->group->is_double_upload == '1' || config('other.doubleup') == '1')
                                                <span class="badge-extra" data-toggle="tooltip" data-html="true" title="

                                                    @if ($torrent->doubleup == '1')
                                                        <p>@lang('torrent.double-upload')</p>
                                                    @endif
                        
                                                    @if ($user->group->is_double_upload == '1')
                                                        <p>@lang('common.special') @lang('torrent.double-upload')</p>
                                                    @endif
                        
                                                    @if (config('other.doubleup') == '1')
                                                        <p>@lang('common.global') {{ strtolower(trans('torrent.double-upload')) }}</p>
                                                    @endif

                                                    "
                                                >
                                                    <i class="{{ config('other.font-awesome') }} fa-gem text-green"></i>
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-danger badge-extra" data-toggle="tooltip" title="@lang('torrent.no-discounts')">
                                                <i class="{{ config('other.font-awesome') }} fa-frown"></i>
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge-extra" data-toggle="tooltip" data-html="true" title='@lang("torrent.featured-until") {{ $featured->created_at->addDay(7)->toFormattedDateString() }} ({{ $featured->created_at->addDay(7)->diffForHumans() }}!) {!! trans("torrent.featured-desc") !!}'>
                                            <i class="{{ config('other.font-awesome') }} fa-certificate text-orange"></i>
                                        </span>
                                    @endif
                                </td>
                            </tr>

                        <tr class="torrent-name">
                            <td class="col-sm-2">
                                <strong>@lang('torrent.name')</strong>
                            </td>
                            <td>{{ $torrent->name }} &nbsp; &nbsp;
                                @if (auth()->user()->group->is_modo || auth()->user()->id === $uploader->id)
                                    <a class="btn btn-warning btn-xs" href="{{ route('edit_form', ['id' => $torrent->id]) }}" role="button">
                                        <i class="{{ config('other.font-awesome') }} fa-pencil-alt"></i> @lang('common.edit')
                                    </a>
                                @endif
                                @if (auth()->user()->group->is_modo || ( auth()->user()->id === $uploader->id && Carbon\Carbon::now()->lt($torrent->created_at->addDay())))
                                    <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal_torrent_delete">
                                        <i class="{{ config('other.font-awesome') }} fa-times"></i> @lang('common.delete')
                                    </button>
                                @endif
                            </td>
                        </tr>

                        @if (auth()->user()->group->is_modo)
                            <tr class="torrent-moderation-controls">
                                <td class="col-sm-2">
                                    <strong>@lang('common.moderation')</strong>
                                </td>
                                <td>
                                    <a href="{{ route('staff.moderation.approve', ['id' => $torrent->id]) }}" role='button'
                                       class='btn btn-labeled btn-success btn-xs @if ($torrent->isApproved()) disabled @endif'>
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
                                        <a href="{{ route('users.show', ['username' => $torrent->moderated->username]) }}"
                                           style="color:{{ $torrent->moderated->group->color }};">
                                            <i class="{{ $torrent->moderated->group->icon }}" data-toggle="tooltip"
                                               data-original-title="{{ $torrent->moderated->group->name }}"></i> {{ $torrent->moderated->username }}
                                        </a>]
                                    </span>
                                </td>
                            </tr>
                        @endif


                        @if (auth()->user()->group->is_modo || auth()->user()->group->is_internal)
                            <tr class="torrent-internal-controls">
                                <td class="col-sm-2"><strong>@lang('common.staff-tools')</strong></td>
                                <td>
                                    @if ($torrent->free == 0)
                                        <a href="{{ route('torrent_fl', ['id' => $torrent->id]) }}"
                                           class="btn btn-success btn-xs" role="button">
                                            <i class="{{ config('other.font-awesome') }} fa-star"></i> @lang('torrent.grant') @lang('torrent.freeleech')
                                        </a>
                                    @else
                                        <a href="{{ route('torrent_fl', ['id' => $torrent->id]) }}"
                                           class="btn btn-danger btn-xs" role="button">
                                            <i class="{{ config('other.font-awesome') }} fa-star"></i> @lang('torrent.revoke') @lang('torrent.freeleech')
                                        </a>
                                    @endif

                                    @if ($torrent->doubleup == 0)
                                        <a href="{{ route('torrent_doubleup', ['id' => $torrent->id]) }}"
                                           class="btn btn-success btn-xs" role="button">
                                            <i class="{{ config('other.font-awesome') }} fa-chevron-double-up"></i> @lang('torrent.grant') @lang('torrent.double-upload')
                                        </a>
                                    @else
                                        <a href="{{ route('torrent_doubleup', ['id' => $torrent->id]) }}"
                                           class="btn btn-danger btn-xs" role="button">
                                            <i class="{{ config('other.font-awesome') }} fa-chevron-double-up"></i> @lang('torrent.revoke') @lang('torrent.double-upload')
                                        </a>
                                    @endif

                                    @if ($torrent->sticky == 0)
                                        <a href="{{ route('torrent_sticky', ['id' => $torrent->id]) }}"
                                           class="btn btn-success btn-xs" role="button">
                                            <i class="{{ config('other.font-awesome') }} fa-thumbtack"></i> @lang('torrent.sticky')
                                        </a>
                                    @else
                                        <a href="{{ route('torrent_sticky', ['id' => $torrent->id]) }}"
                                           class="btn btn-danger btn-xs" role="button">
                                            <i class="{{ config('other.font-awesome') }} fa-thumbtack"></i> @lang('torrent.unsticky')
                                        </a>
                                    @endif

                                    <a href="{{ route('bumpTorrent', ['id' => $torrent->id]) }}"
                                       class="btn btn-primary btn-xs" role="button">
                                        <i class="{{ config('other.font-awesome') }} fa-arrow-to-top"></i> @lang('torrent.bump')
                                    </a>

                                    @if ($torrent->featured == 0)
                                        <a href="{{ route('torrent_feature', ['id' => $torrent->id]) }}"
                                           class="btn btn-default btn-xs" role="button">
                                            <i class="{{ config('other.font-awesome') }} fa-certificate"></i> @lang('torrent.feature')
                                        </a>
                                    @else
                                        <a href="{{ route('torrent_revokefeature', ['id' => $torrent->id]) }}"
                                           class="btn btn-danger btn-xs" role="button">
                                            <i class="{{ config('other.font-awesome') }} fa-certificate"></i> @lang('torrent.revokefeatured')
                                        </a>
                                    @endif

                                </td>
                            </tr>
                        @endif

                        <tr class="torrent-uploader">
                            <td class="col-sm-2"><strong>@lang('torrent.uploader')</strong></td>
                            <td>
                                @if ($torrent->anon == 1)
                                    <span class="badge-user text-orange text-bold">{{ strtoupper(trans('common.anonymous')) }}
                                        @if (auth()->user()->id == $uploader->id || auth()->user()->group->is_modo)
                                            <a href="{{ route('users.show', ['username' => $uploader->username]) }}">
                                                ({{ $uploader->username }}
                                            )</a>
                                        @endif
                                    </span>
                                @else
                                    <a href="{{ route('users.show', ['username' => $uploader->username]) }}">
                                        <span class="badge-user text-bold" style="color:{{ $uploader->group->color }}; background-image:{{ $uploader->group->effect }};">
                                            <i class="{{ $uploader->group->icon }}" data-toggle="tooltip" data-original-title="{{ $uploader->group->name }}"></i> {{ $uploader->username }}
                                        </span>
                                    </a>
                                @endif

                                @if ($torrent->anon !== 1 && $uploader->private_profile !== 1)
                                    @if (auth()->user()->isFollowing($uploader->id))
                                        <form class="form-inline" role="form" action="{{ route('follow.destroy', ['username' => $uploader->username]) }}"
                                              style="display: inline-block;" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="form-group">
                                            <button type="submit" id="delete-follow-{{ $uploader->target_id }}" class="btn btn-xs btn-info"
                                                    title="@lang('user.unfollow')">
                                                <i class="{{ config('other.font-awesome') }} fa-user"></i> @lang('user.unfollow')
                                            </button>
                                            </div>
                                        </form>
                                    @else
                                        <form class="form-inline" role="form" action="{{ route('follow.store', ['username' => $uploader->username]) }}"
                                              style="display: inline-block;" method="POST">
                                            @csrf
                                            <div class="form-group">
                                            <button type="submit" id="follow-user-{{ $uploader->id }}" class="btn btn-xs btn-success"
                                                    title="@lang('user.follow')">
                                                <i class="{{ config('other.font-awesome') }} fa-user"></i> @lang('user.follow')
                                            </button>
                                            </div>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>

                        <tr class="torrent-uploaded-time">
                            <td class="col-sm-2"><strong>@lang('torrent.uploaded')</strong></td>
                            <td>
                                <span data-toggle="tooltip" title="{{ $torrent->created_at }}">
                                    {{ $torrent->created_at->diffForHumans() }}
                                </span>
                            </td>
                        </tr>

                        <tr class="torrent-size">
                            <td class="col-sm-2"><strong>@lang('torrent.size')</strong></td>
                            <td>
                                <span
                                    data-toggle="tooltip"
                                    title="@lang('torrent.estimated-ratio'): {{ $user->ratioAfterSizeString($torrent->size, $torrent->isFreeleech(auth()->user())) }}"
                                >
                                    {{ $torrent->getSize() }}
                                </span>
                            </td>
                        </tr>

                        <tr class="torrent-category">
                            <td class="col-sm-2"><strong>@lang('torrent.category')</strong></td>
                            <td><i class="{{ $torrent->category->icon }} torrent-icon torrent-icon-small"
                                   data-toggle="tooltip" data-original-title="{{ $torrent->category->name }} @lang('torrent.torrent')"></i>
                                {{ $torrent->category->name }}
                            </td>
                        </tr>

                        <tr class="torrent-type">
                            <td class="col-sm-2"><strong>@lang('torrent.type')</strong></td>
                            <td>{{ $torrent->type->name }}</td>
                        </tr>

                        <tr class="torrent-resolution">
                            <td class="col-sm-2"><strong>@lang('torrent.resolution')</strong></td>
                            <td>{{ $torrent->resolution->name ?? 'No Res' }}</td>
                        </tr>

                        @if ($torrent->keywords->isNotEmpty())
                            <tr class="torrent-keywords">
                                <td class="col-sm-2"><strong>@lang('torrent.keywords')</strong></td>
                                <td>
                                    @foreach($torrent->keywords as $keyword)
                                        <span class="badge-user text-bold">{{ $keyword->name }}</span>
                                    @endforeach
                                </td>
                            </tr>
                        @endif

                        <tr class="torrent-stream-optimized">
                            <td class="col-sm-2"><strong>@lang('torrent.stream-optimized')?</strong></td>
                            <td>
                                @if ($torrent->stream == "1") @lang('common.yes') @else @lang('common.no') @endif
                            </td>
                        </tr>

                        <tr class="torrent-info-hash">
                            <td class="col-sm-2"><strong>@lang('torrent.info-hash')</strong></td>
                            <td>{{ $torrent->info_hash }}</td>
                        </tr>

                        <tr class="torrent-peers">
                            <td class="col-sm-2"><strong>@lang('torrent.peers')</strong></td>
                            <td>
                                <a
                                    href="{{ route('peers', ['id' => $torrent->id]) }}"
                                    class="badge-extra text-green"
                                    data-toggle="tooltip"
                                    title="{{ $torrent->seeders }} {{ strtolower(trans('torrent.seeders')) }}"
                                >
                                    <i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-up"></i> {{ $torrent->seeders }}
                                </a>
                                <a
                                    href="{{ route('peers', ['id' => $torrent->id]) }}"
                                    class="badge-extra text-red"
                                    data-toggle="tooltip"
                                    title="{{ $torrent->leechers }} {{ strtolower(trans('torrent.leechers')) }}"
                                >
                                    <i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-down"></i> {{ $torrent->leechers }}
                                </a>
                                <a
                                    href="{{ route('history', ['id' => $torrent->id]) }}"
                                    class="badge-extra text-info"
                                    data-toggle="tooltip"
                                    title="{{ $torrent->times_completed }} {{ strtolower(trans('common.times')) }}"
                                >
                                    <i class="{{ config('other.font-awesome') }} fa-fw fa-check"></i> {{ $torrent->times_completed }}
                                </a>

                                @if ($torrent->seeders == 0)
                                    @if ($last_seed_activity)
                                        <span class="badge-extra text-orange torrent-last-seed-activity">
                                            <i class="{{ config('other.font-awesome') }} fa-fw fa-clock"></i> @lang('torrent.last-seed-activity'): {{ $last_seed_activity->updated_at->diffForHumans() }}
                                        </span>
                                    @else
                                        <span class="badge-extra text-orange torrent-last-seed-activity">
                                            <i class="{{ config('other.font-awesome') }} fa-fw fa-clock"></i> @lang('torrent.last-seed-activity'): @lang('common.unknown')
                                        </span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

	        @if (auth()->user()->group->is_modo)
		        @include('torrent.partials.audits')
	        @endif

	        @if ($torrent->mediainfo != null)
		        <div class="panel panel-chat shoutbox torrent-mediainfo" x-data="{ show: false }">
		    	    <div class="panel-heading">
		    		    <h4>
		    			    <i class="{{ config("other.font-awesome") }} fa-info-square"></i> MediaInfo
		    			    <i class="{{ config("other.font-awesome") }} fa-plus-circle fa-pull-right" style="cursor: pointer;" @click="show = !show"></i>
		    		    </h4>
		    	    </div>
		    	    <div class="table-responsive tabla-mediainfo">
		    		    <table class="table table-condensed table-bordered table-striped">
		    			    <tbody>
		    			        <tr>
		    				        <td>
		    				    	    <div class="panel-body">
		    				    		    <div class="slidingDiv2">
		    				    			    <div class="text-left text-main mediainfo-filename" style="border-bottom: 1px solid #444444; padding-bottom: 5px; margin-bottom: 5px;">
		    				    				    @if ($mediaInfo !== null && isset($mediaInfo['general']['file_name']))
		    				    					    <span class="text-bold text-main">{{ $mediaInfo['general']['file_name'] ?? '' }}</span>
		    				    				    @endif
		    				    			    </div>
		    				    			    <div class="mediainfo-main" style="width: 100%; display:table;">
		    				    				    <div class="mediainfo-general" style="width: 20%; display:table-cell; text-align: left;">
		    				    					    <div class="text-bold">@joypixels(':information_source:') General:</div>
		    				    					    <div><u style="font-weight: bold;">Format:</u> {{ $mediaInfo['general']['format'] ?? '' }}</div>
		    				    					    <div><u style="font-weight: bold;">Duration:</u> {{ $mediaInfo['general']['duration'] ?? '' }}</div>
		    				    					    <div><u style="font-weight: bold;">Global Bit Rate:</u> {{ $mediaInfo['general']['bit_rate'] ?? '' }}</div>
		    				    					    <div><u style="font-weight: bold;">Overall Size:</u> {{ App\Helpers\StringHelper::formatBytes($mediaInfo['general']['file_size'] ?? 0, 2) }}</div>
		    				    				    </div>
		    				    				    <div class="mediainfo-video" style="width: 30%; display:table-cell; text-align: left;">
		    				    					    <div class="text-bold">@joypixels(':projector:') Video Tracks:</div>
		    				    					    @if ($mediaInfo !== null && isset($mediaInfo['video']))
		    				    						    @foreach ($mediaInfo['video'] as $key => $videoElement)
		    				    							    <div>Track {{ ++$key }}:</div>
		    				    							    <div><u style="font-weight: bold;">Format:</u> {{ $videoElement['format'] ?? '' }} ({{ $videoElement['bit_depth'] ?? '' }})</div>
		    				    							    <div><u style="font-weight: bold;">Resolution:</u> {{ $videoElement['width'] ?? '' }} x {{ $videoElement['height'] ?? '' }}</div>
		    				    							    <div><u style="font-weight: bold;">Aspect Ratio:</u> {{ $videoElement['aspect_ratio'] ?? '' }}</div>
		    				    							    <div><u style="font-weight: bold;">Frame Rate:</u> {{ $videoElement['frame_rate'] ?? '' }}</div>
		    				    							    <div><u style="font-weight: bold;">Bit Rate:</u> {{ $videoElement['bit_rate'] ?? '' }}</div>
													            @if(isset($videoElement['format']) && $videoElement['format'] === 'HEVC')
		    				    								    <div><u style="font-weight: bold;">HDR Format:</u> {{ $videoElement['hdr_format'] ?? '' }}</div>
		    				    								    <div><u style="font-weight: bold;">Color Primaries:</u> {{ $videoElement['color_primaries'] ?? '' }}</div>
		    				    								    <div><u style="font-weight: bold;">Transfer Characteristics:</u> {{ $videoElement['transfer_characteristics'] ?? '' }}</div>
		    				    							    @endif
		    				    							    @if(count($mediaInfo['video']) > 1) <div style="border-top: 1px solid #444444; padding-top: 5px; margin-top: 5px; width: 75%;"></div> @endif
		    				    						    @endforeach
		    				    					    @endif
		    				    				    </div>
		    				    				    <div class="mediainfo-audio" style="width: 50%; display:table-cell; text-align: left;">
		    				    					    <div class="text-bold">@joypixels(':loud_sound:') Audio Tracks:</div>
		    				    					    @if ($mediaInfo !== null && isset($mediaInfo['audio']))
		    				    						    @foreach ($mediaInfo['audio'] as $key => $audioElement)
		    				    							    <div>Track {{ ++$key }}:</div>
		    				    						        <div>{{ $audioElement['language'] ?? '' }} | {{ $audioElement['format'] ?? '' }} | {{ $audioElement['channels'] ?? '' }} | {{ $audioElement['bit_rate'] ?? '' }} | {{ $audioElement['title'] ?? '' }}</div>
		    				    							    @if(count($mediaInfo['audio']) > 1) <div style="border-top: 1px solid #444444; padding-top: 5px; margin-top: 5px; width: 75%;"></div> @endif
		    				    						    @endforeach
		    				    					    @endif
		    				    				    </div>
		    				    			    </div>

								    	        <div class="text-left text-main mediainfo-subtitles" style="border-top: 1px solid #444444; padding-top: 5px; margin-top: 5px;">
								    		        <span class="text-bold">@joypixels(':speech_balloon:') Subtitles:</span>
								    		    	@if ($mediaInfo !== null && isset($mediaInfo['text']))
								    		    		@foreach ($mediaInfo['text'] as $key => $textElement)
								    		    		    <span><img src="{{ \language_flag($textElement['language'] ?? '') }}" alt="{{ $textElement['language'] ?? '' }}" width="20" height="13" data-toggle="tooltip" data-original-title="{{ $textElement['language'] ?? '' }} | {{ $textElement['format'] ?? '' }} | {{ $textElement['title'] ?? '' }}">&nbsp;</span>
								    		    		@endforeach
								    		    	@endif
								    	        </div>

									            @if ($mediaInfo !== null && isset($mediaInfo['video']))
									    	        @foreach ($mediaInfo['video'] as $key => $videoElement)
									    	    	    @if ($mediaInfo !== null && isset($videoElement['encoding_settings']))
									    	    		    <div class="text-left text-main mediainfo-encode-settings" style="border-top: 1px solid #444444; padding-top: 5px; margin-top: 5px;">
									    	    			    <span class="text-bold">@joypixels(':information_source:') Encode Settings:</span>
									    	    			    <br>
									    	    			    <pre class="decoda-code"><code>{{ $videoElement['encoding_settings'] ?? '' }}</code></pre>
									    	    		    </div>
									    	    	    @endif
									    	        @endforeach
									            @endif
								            </div>

								            <div class="torrent-mediainfo-dump" style="opacity: 1; display: none;" x-show="show">
									            <div style="border-top: 1px solid #444444; padding-top: 5px; margin-top: 5px;">
									        	    <span class="text-center text-bold">Full MediaInfo Dump</span>
									        		<pre class="decoda-code"><code>{{ $torrent->mediainfo }}</code></pre>
									            </div>
								            </div>
						    	        </div>
						            </td>
					            </tr>
					        </tbody>
				        </table>
			        </div>
		        </div>
	        @endif

	        @if ($torrent->bdinfo != null)
		        <div class="panel panel-chat shoutbox torrent-bdinfo">
		    	    <div class="panel-heading">
		    		    <h4><i class="{{ config("other.font-awesome") }} fa-compact-disc"></i> BDInfo</h4>
		    	    </div>
		    	    <div class="table-responsive">
		    		    <table class="table table-condensed table-bordered table-striped">
		    			    <tbody>
		    			    <tr>
		    				    <td>
		    					    <div class="panel-body">
		    						    <pre class="decoda-code"><code>{{ $torrent->bdinfo }}</code></pre>
		    					    </div>
		    				    </td>
		    			    </tr>
		    			    </tbody>
		    		    </table>
		    	    </div>
		        </div>
	        @endif

	        <div class="panel panel-chat shoutbox torrent-description">
		        <div class="panel-heading">
		    	    <h4><i class="{{ config("other.font-awesome") }} fa-sticky-note"></i> @lang('common.description')</h4>
		        </div>
		        <div class="table-responsive">
		    	    <table class="table table-condensed table-bordered table-striped">
		    		    <tbody>
		    		    <tr>
		    			    <td>
		    				    <div class="panel-body">
		    					    @joypixels($torrent->getDescriptionHtml())

			    				    @if (! empty($meta->collection['0']) && $torrent->category->movie_meta)
			    					    <hr>
			    					    <div id="collection_waypoint" class="collection">
			    						    <div class="header collection"
			    						         @php $backdrop = $meta->collection['0']->backdrop; @endphp
			    						         style=" background-image: url({{ isset($backdrop) ? \tmdb_image('back_big', $backdrop) : 'https://via.placeholder.com/1280x300' }}); background-size: cover; background-position: 50% 50%;">
			    							    <div class="collection-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-image: linear-gradient(rgba(0, 0, 0, 0.87), rgba(45, 71, 131, 0.46));"></div>
			    							    <section class="collection">
			    								    <h2>Part of the {{ $meta->collection['0']->name }}</h2>
			    								    <p class="text-blue">Includes:
			    									    @foreach($meta->collection['0']->movie as $collection_movie)
			    										    {{ $collection_movie->title }},
			    									    @endforeach
			    								    </p>

				    							    <a href="{{ route('mediahub.collections.show', ['id' => $meta->collection['0']->id]) }}"
				    							       role="button" class="btn btn-labeled btn-primary"
				    							       style=" margin: 0; text-transform: uppercase; position: absolute; bottom: 50px;">
				    								<span class="btn-label">
				    									<i class="{{ config("other.font-awesome") }} fa-copy"></i> View The Collection
				    								</span>
				    							    </a>
				    						    </section>
				    					    </div>
				    				    </div>
				    			    @endif
				    		    </div>
				    	    </td>
				        </tr>
				        </tbody>
			        </table>
		        </div>
	        </div>

	        {{-- Subtitles Block --}}
	        @if($torrent->category->movie_meta || $torrent->category->tv_meta)
	        	@include('torrent.partials.subtitles')
	        @endif

	        <div class="panel panel-chat shoutbox torrent-tip-jar">
	        	<div class="panel-heading">
	        		<h4><i class="{{ config("other.font-awesome") }} fa-coins"></i> @lang('torrent.tip-jar')</h4>
	        	</div>
	        	<div class="table-responsive">
	        		<table class="table table-condensed table-bordered table-striped">
	        			<tbody>
	        			<tr>
	        				<td>
	        					<div class="col-md-7">
	        						<form role="form" method="POST"
	        							  action="{{ route('tip_uploader', ['id' => $torrent->id]) }}"
	        							  class="form-inline">
	        							@csrf
	        							<div class="form-group">
	        								<span class="text-green text-bold">@lang('torrent.define-tip-amount')</span>
	        								<label>
	        									<input type="number" name="tip" value="0" placeholder="0" class="form-control"
	        										   style="width: 80%;">
	        								</label>
	        								<button type="submit"
	        										class="btn btn-primary">@lang('torrent.leave-tip')</button>
	        							</div>
	        							<br>
	        							<span class="text-green text-bold">@lang('torrent.quick-tip')</span>
	        							<br>
	        							<button type="submit" value="1000" name="tip" class="btn">1,000</button>
	        							<button type="submit" value="2000" name="tip" class="btn">2,000</button>
	        							<button type="submit" value="5000" name="tip" class="btn">5,000</button>
	        							<button type="submit" value="10000" name="tip" class="btn">10,000</button>
	        							<button type="submit" value="20000" name="tip" class="btn">20,000</button>
	        							<button type="submit" value="50000" name="tip" class="btn">50,000</button>
	        							<button type="submit" value="100000" name="tip" class="btn">100,000</button>
	        						</form>
	        					</div>
	        					<div class="col-md-5">
	        						<div class="well" style="box-shadow: none !important;">
	        							<h4>{!! trans('torrent.torrent-tips', ['total' => $total_tips, 'user' => $user_tips]) !!}
	        								.</h4>
	        							<span class="text-red text-bold">(@lang('torrent.torrent-tips-desc'))</span>
	        						</div>
	        					</div>
	        				</td>
	        			</tr>
	        			</tbody>
	        		</table>
	        	</div>
	        </div>

	        @if (! empty($meta->recommendations))
		        <div class="panel panel-chat shoutbox torrent-recommendations">
		    	    <div class="panel-heading">
		    		    <h4><i class="{{ config("other.font-awesome") }} fa-exclamation"></i> Recommended</h4>
		    	    </div>
		    	    <div class="panel-body" style="padding: 5px;">
		    		    <section class="recommendations">
		    			    <div class="scroller" style="padding-bottom: 10px;">
		    				    @foreach($meta->recommendations as $recommendation)
		    					    <div class="item mini backdrop mini_card">
		    						    <div class="image_content">
		    							    <a href="{{ route('torrents.similar', ['category_id' => $torrent->category_id, 'tmdb' => $recommendation->recommendation_movie_id ?? $recommendation->recommendation_tv_id]) }}">
		    								    <div>
		    									    @if(isset($recommendation->poster))
		    										    <img class="backdrop" src="{{ \tmdb_image('poster_big', $recommendation->poster) }}">
		    									    @else
		    										    <div class="no_image_holder w300_and_h450 backdrop"></div>
		    									    @endif
		    								    </div>
		    								    <div style=" margin-top: 8px">
		    									    <span class="badge-extra">
		    										    <i class="fas fa-clock"></i> @lang('common.year'):
		    										    @if(isset($recommendation->release_date))
		    											    {{ substr($recommendation->release_date, 0, 4) }}
		    										    @elseif(isset($recommendation->first_air_date))
		    											    {{ substr($recommendation->first_air_date, 0, 4) }}
		    										    @else
		    											    @lang('common.unknown')
		    										    @endif
		    									    </span>
		    									    <span class="badge-extra {{ \rating_color($recommendation->vote_average ?? 'text-white') }}">
		    										    <i class="{{ config('other.font-awesome') }} fa-star-half-alt"></i> {{ $recommendation->vote_average ?? 0 }}/10
		    									    </span>
		    								    </div>
		    							    </a>
		    						    </div>
		    					    </div>
		    				    @endforeach
		    			    </div>
		    		    </section>
		    	    </div>
		        </div>
            @endif
	    </div>

        <div class="torrent box container" id="comments">
        	<div class="clearfix"></div>
        	<div class="row ">
        		<div class="col-md-12 col-sm-12">
        			<div class="panel panel-chat shoutbox">
        				<div class="panel-heading">
        					<h4>
        						<i class="{{ config('other.font-awesome') }} fa-comment"></i> @lang('common.comments')
        					</h4>
        				</div>
        				<div class="panel-body no-padding">
        					<ul class="media-list comments-list">
        						@if (count($comments) == 0)
        							<div class="text-center"><h4 class="text-bold text-danger"><i
        											class="{{ config('other.font-awesome') }} fa-frown"></i> @lang('common.no-comments')
        									!</h4>
        							</div>
        						@else
        							@foreach ($comments as $comment)
        								<li class="media" style="border-left: 5px solid rgb(1,188,140);">
        									<div class="media-body">
        										@if ($comment->anon == 1)
        											<a href="#" class="pull-left" style="padding-right: 10px;">
        												<img src="{{ url('img/profile.png') }}" class="img-avatar-48">
        												<strong>{{ strtoupper(trans('common.anonymous')) }}</strong></a> @if (auth()->user()->id == $comment->user->id || auth()->user()->group->is_modo)
        												<a href="{{ route('users.show', ['username' => $comment->user->username]) }}"
        												   style="color:{{ $comment->user->group->color }};">(<span><i
        																class="{{ $comment->user->group->icon }}"></i> {{ $comment->user->username }}</span>)</a> @endif
        										@else
        											<a href="{{ route('users.show', ['username' => $comment->user->username]) }}"
        											   class="pull-left" style="padding-right: 10px;">
        												@if ($comment->user->image != null)
        													<img src="{{ url('files/img/' . $comment->user->image) }}"
        														 alt="{{ $comment->user->username }}" class="img-avatar-48"></a>
        											@else
        												<img src="{{ url('img/profile.png') }}"
        													 alt="{{ $comment->user->username }}" class="img-avatar-48"></a>
        											@endif
        											<strong><a
        														href="{{ route('users.show', ['username' => $comment->user->username]) }}"
        														style="color:{{ $comment->user->group->color }};"><span><i
        																class="{{ $comment->user->group->icon }}"></i> {{ $comment->user->username }}</span></a></strong> @endif
        										<span class="text-muted"><small><em>{{ $comment->created_at->toDayDateTimeString() }} ({{ $comment->created_at->diffForHumans() }})</em></small></span>
        										@if ($comment->user_id == auth()->id() || auth()->user()->group->is_modo)
        											<a title="@lang('common.delete-comment')"
        											   href="{{route('comment_delete',['comment_id'=>$comment->id])}}"><i
        														class="pull-right {{ config('other.font-awesome') }} fa fa-times"
        														aria-hidden="true"></i></a>
        											<a title="@lang('common.edit-comment')" data-toggle="modal"
        											   data-target="#modal-comment-edit-{{ $comment->id }}"><i
        														class="pull-right {{ config('other.font-awesome') }} fa-pencil"
        														aria-hidden="true"></i></a>
        										@endif
        										<div class="pt-5">
        											@joypixels($comment->getContentHtml())
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


		        <div class="clearfix"></div>
		            <div class="col-md-12 home-pagination">
		            	<div class="text-center">{{ $comments->links() }}</div>
		            </div>
		        <br>

		        <div class="col-md-12">
		        	<form role="form" method="POST"
		        		  action="{{ route('comment_torrent', ['id' => $torrent->id]) }}">
		        		@csrf
		        		<div class="form-group">
		        			<label for="content">@lang('common.your-comment'):</label><span class="badge-extra">@lang('common.type-verb')
		        			<strong>":"</strong> @lang('common.for') emoji</span> <span
		        					class="badge-extra">BBCode @lang('common.is-allowed')</span>
		        			<textarea id="content" name="content" cols="30" rows="5" class="form-control"></textarea>
		        		</div>
		        		<button type="submit" class="btn btn-danger">@lang('common.submit')</button>
		        		<label class="radio-inline"><strong>@lang('common.anonymous') @lang('common.comment')
		        				:</strong></label>
		        		<label>
		        			<input type="radio" value="1" name="anonymous">
		        		</label> @lang('common.yes')
		        		<label>
		        			<input type="radio" value="0" checked="checked" name="anonymous">
		        		</label> @lang('common.no')
		        	</form>
		        </div>
	        </div>
        </div>
        @include('torrent.torrent_modals', ['user' => $user, 'torrent' => $torrent])
    </div>
@endsection

@section('javascripts')
<script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
  $(document).ready(function () {
	$('#content').wysibb({});
  })
</script>

@if (isset($trailer))
	<script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
      $('.show-trailer').each(function () {
        $(this).off('click');
        $(this).on('click', function (e) {
          e.preventDefault();
          Swal.fire({
            showConfirmButton: false,
            showCloseButton: true,
            background: 'rgb(35,35,35)',
            width: 970,
            html: '<iframe width="930" height="523" src="{{ $trailer }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>',
            title: '<i style="color: #a5a5a5;">Trailer</i>',
            text: ''
          });
        });
      });
	</script>
@endif
@endsection
