<div class="panel panel-chat shoutbox torrent-general" x-data="{ show: false }">
    <div class="panel-heading">
        <h4 style="cursor: pointer;" @click="show = !show">
            <i class="{{ config("other.font-awesome") }} fa-info"></i> {{ __('torrent.general') }}
            <i class="{{ config("other.font-awesome") }} fa-plus-circle fa-pull-right" x-show="!show"></i>
            <i class="{{ config("other.font-awesome") }} fa-minus-circle fa-pull-right" x-show="show"></i>
        </h4>
    </div>
    <div class="table-responsive" x-show="!show">
        <table class="table table-condensed table-bordered table-striped">
            <tbody>
            <tr style="border-bottom-width: 0">
                <td>
                    <div style="margin: 4px 0">
                        <div class="torrent-format" style="display: inline-block">
                            <span class="torrent-category badge-extra text-info text-bold" style="line-height: 14px">
                                {{ $torrent->category->name }}
                            </span>
                            <span class="torrent-resolution badge-extra text-info text-bold" style="line-height: 14px">
                                {{ $torrent->resolution->name ?? 'No Res' }}
                            </span>
                            <span class="torrent-type badge-extra text-info text-bold" style="line-height: 14px">
                                {{ $torrent->type->name }}
                            </span>
                            @if (isset($torrent->region_id))
                                <span class="torrent-region badge-extra text-info text-bold" data-toggle="tooltip"
                                      style="line-height: 14px" title="{{ $torrent->region->name }}"
                                >
                                    {{ $torrent->region->name }}
                                </span>
                            @endif
                            @if (isset($torrent->distributor_id))
                                <span class="torrent-distributor badge-extra text-info text-bold" data-toggle="tooltip"
                                      style="line-height: 14px" title="{{ $torrent->distributor->name }}"
                                >
                                    {{ $torrent->distributor->name }}
                                </span>
                            @endif
                            <span class="torrent-size badge-extra text-info text-bold" data-toggle="tooltip"
                                  style="line-height: 14px" title="{{ __('torrent.estimated-ratio') }}: {{ $user->ratioAfterSizeString($torrent->size, $torrent->isFreeleech(auth()->user())) }}"
                            >
                                {{ $torrent->getSize() }}
                            </span>
                        </div>
                        <div class="torrent-discounts" style="display: inline-block">
                            @if ($torrent->featured == '0')
                                @if ($freeleech_token || $user->group->is_freeleech == '1' || $personal_freeleech || $torrent->free > '1' || config('other.freeleech') == '1' || $torrent->doubleup == '1' || $user->group->is_double_upload == '1' || config('other.doubleup') == '1')
                                    @if ($freeleech_token || $user->group->is_freeleech == '1' || $personal_freeleech || config('other.freeleech') == '1')
                                        <span class="badge-extra" data-toggle="tooltip" data-html="true" title="
                                                @if ($freeleech_token)
                                                    <p>{{ __('common.fl_token') }}</p>
                                                @endif

                                                @if ($user->group->is_freeleech == '1')
                                                    <p>{{ __('common.special') }} {{ __('torrent.freeleech') }}</p>
                                                @endif

                                                @if ($personal_freeleech)
                                                    <p>{{ __('common.personal') }} {{ __('torrent.freeleech') }}</p>
                                                @endif

                                                @if (config('other.freeleech') == '1')
                                                    <p>{{ __('common.global') }} {{ __('torrent.freeleech') }}</p>
                                                @endif
                                                ">
                                            <i class="{{ config('other.font-awesome') }} fa-star text-gold"></i>
                                        </span>
                                    @elseif ($torrent->free > '1')
                                        @if ($torrent->free >= '90')
                                            <span class="badge-extra text-bold" data-toggle="tooltip" data-html="true"
                                                  title="<p>{{ $torrent->free }}% {{ __('common.free') }}</p>">
                                                <i class="{{ config('other.font-awesome') }} fa-star text-gold"></i>
                                                @if ($torrent->fl_until !== null) <span>{{ Illuminate\Support\Carbon::now()->diffForHumans($torrent->fl_until) }} Freeleech expires.</span> @endif
                                            </span>
                                        @elseif ($torrent->free < '90' && $torrent->free >= '30')
                                            <style>
                                                .star50 {
                                                    position: relative;
                                                }

                                                .star50:after {
                                                    content: "\f005";
                                                    position: absolute;
                                                    left: 0;
                                                    top: 0;
                                                    width: 50%;
                                                    overflow: hidden;
                                                    color: #FFB800;
                                                }
                                            </style>
                                            <span class="badge-extra text-bold" data-toggle="tooltip" data-html="true"
                                                  title="<p>{{ $torrent->free }}% {{ __('common.free') }}</p>">
                                                <i class="star50 {{ config('other.font-awesome') }} fa-star"></i>
                                                @if ($torrent->fl_until !== null) <span>{{ Illuminate\Support\Carbon::now()->diffForHumans($torrent->fl_until) }} Freeleech expires.</span> @endif
                                            </span>
                                        @elseif ($torrent->free < '30' && $torrent->free != '0')
                                            <style>
                                                .star30 {
                                                    position: relative;
                                                }

                                                .star30:after {
                                                    content: "\f005";
                                                    position: absolute;
                                                    left: 0;
                                                    top: 0;
                                                    width: 30%;
                                                    overflow: hidden;
                                                    color: #FFB800;
                                                }
                                            </style>
                                            <span class="badge-extra text-bold" data-toggle="tooltip" data-html="true"
                                                  title="<p>{{ $torrent->free }}% {{ __('common.free') }}</p>">
                                                <i class="star30 {{ config('other.font-awesome') }} fa-star"></i>
                                                @if ($torrent->fl_until !== null) <span>{{ Illuminate\Support\Carbon::now()->diffForHumans($torrent->fl_until) }} Freeleech expires.</span> @endif
                                            </span>
                                        @endif
                                    @endif

                                    @if ($torrent->doubleup == '1' || $user->group->is_double_upload == '1' || config('other.doubleup') == '1')
                                        <span class="badge-extra" data-toggle="tooltip" data-html="true" title="
                                                @if ($torrent->doubleup == '1')
                                                    <p>{{ __('torrent.double-upload') }}</p>
                                                @endif

                                                @if ($user->group->is_double_upload == '1')
                                                    <p>{{ __('common.special') }} {{ __('torrent.double-upload') }}</p>
                                                @endif

                                                @if (config('other.doubleup') == '1')
                                                    <p>{{ __('common.global') }} {{ strtolower(__('torrent.double-upload')) }}</p>
                                                @endif
                                                ">
                                            <i class="{{ config('other.font-awesome') }} fa-gem text-green"></i>
                                            @if ($torrent->du_until !== null) <span>{{ Illuminate\Support\Carbon::now()->diffForHumans($torrent->du_until) }} Double Upload expires.</span> @endif
                                        </span>
                                    @endif
                                @else
                                    <span class="text-danger badge-extra" data-toggle="tooltip"
                                          title="{{ __('torrent.no-discounts') }}">
                                        <i class="{{ config('other.font-awesome') }} fa-frown"></i>
                                    </span>
                                @endif
                            @else
                                <span class="badge-extra" data-toggle="tooltip" data-html="true"
                                      title='{{ __("torrent.featured-until") }} {{ $featured->created_at->addDay(7)->toFormattedDateString() }} ({{ $featured->created_at->addDay(7)->diffForHumans() }}!) {!! __("torrent.featured-desc") !!}'>
                                    <i class="{{ config('other.font-awesome') }} fa-certificate text-orange"></i>
                                </span>
                            @endif

                            @if ($torrent->internal == '1')
                                <span class="badge-extra" data-toggle="tooltip" title="{{ __('torrent.internal-release') }}">
                                    <i class="{{ config('other.font-awesome') }} fa-magic" style="color: #baaf92;"></i>
                                </span>
                            @endif

                            @if ($torrent->personal_release == '1')
                                <span class="badge-extra" data-toggle="tooltip" title="{{ __('torrent.personal-release') }}">
                                    <i class="{{ config('other.font-awesome') }} fa-user-plus text-green" style="color: #865be9"></i>
                                </span>
                            @endif

                            @if ($torrent->stream == '1')
                                <span class="badge-extra" data-toggle="tooltip" title="{{ __('torrent.stream-optimized') }}">
                                    <i class="{{ config('other.font-awesome') }} fa-play text-red"></i>
                                </span>
                            @endif

                            @if ($torrent->leechers >= 5)
                                <span class="badge-extra" data-toggle="tooltip" title="{{ __('common.hot') }}">
                                    <i class="{{ config('other.font-awesome') }} fa-fire text-orange"></i>
                                </span>
                            @endif

                            @if ($torrent->sticky == '1')
                                <span class="badge-extra" data-toggle="tooltip" title="{{ __('torrent.sticky') }}">
                                    <i class="{{ config('other.font-awesome') }} fa-thumbtack text-black"></i>
                                </span>
                            @endif

                            @if ($torrent->highspeed == '1')
                                <span class="badge-extra" data-toggle="tooltip" title="{{ __('common.high-speeds') }}">
                                    <i class="{{ config('other.font-awesome') }} fa-tachometer text-red"></i>
                                </span>
                            @endif

                            @if ($torrent->sd == '1')
                                <span class="badge-extra" data-toggle="tooltip" title="{{ __('torrent.sd-content') }}">
                                    <i class="{{ config('other.font-awesome') }} fa-ticket text-orange"></i>
                                </span>
                            @endif
                        </div>
                        <div class="torrent-uploaded pull-right">
                            {{ __('torrent.uploaded-by') }}
                            @if ($torrent->anon == 1)
                                <span class="badge-user text-orange text-bold">{{ strtoupper(__('common.anonymous')) }}
                                    @if (auth()->user()->id == $torrent->user->id || auth()->user()->group->is_modo)
                                        <a href="{{ route('users.show', ['username' => $torrent->user->username]) }}">
                                            ({{ $torrent->user->username }})
                                        </a>
                                    @endif
                                </span>
                            @else
                                <a href="{{ route('users.show', ['username' => $torrent->user->username]) }}">
                                    <span class="badge-user text-bold" style="color:{{ $torrent->user->group->color }}; background-image:{{ $torrent->user->group->effect }};">
                                        <i class="{{ $torrent->user->group->icon }}" data-toggle="tooltip"
                                           data-original-title="{{ $torrent->user->group->name }}"></i> {{ $torrent->user->username }}
                                    </span>
                                </a>
                            @endif

                            @if ($torrent->anon !== 1 && $torrent->user->private_profile !== 1)
                                @if ($torrent->user->followers()->where('users.id', '=', auth()->user()->id)->exists())
                                    <form class="form-inline" style="line-height: 0; display: inline-block;" role="form"
                                          action="{{ route('users.followers.destroy', ['user' => $torrent->user]) }}"
                                          style="display: inline-block;" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="form-group">
                                            <button type="submit" id="delete-follow-{{ $torrent->user->id }}"
                                                    class="btn btn-xs btn-info"
                                                    title="{{ __('user.unfollow') }}">
                                                {{ __('user.unfollow') }}
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    <form class="form-inline" style="line-height: 0; display: inline-block;" role="form"
                                          action="{{ route('users.followers.store', ['user' => $torrent->user]) }}"
                                          style="display: inline-block;" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <button type="submit" id="follow-user-{{ $torrent->user->id }}"
                                                    class="btn btn-xs btn-success"
                                                    title="{{ __('user.follow') }}">
                                                {{ __('user.follow') }}
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            @endif

                            <span class="torrent-uploaded-time" data-toggle="tooltip" title="{{ $torrent->created_at }}">
                                {{ $torrent->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div style="display: block">
                        <a
                                href="{{ route('peers', ['id' => $torrent->id]) }}"
                                class="badge-extra text-green"
                                data-toggle="tooltip"
                                title="{{ $torrent->seeders }} {{ strtolower(__('torrent.seeders')) }}"
                        >
                            <i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-up"></i> {{ $torrent->seeders }}
                        </a>
                        <a
                                href="{{ route('peers', ['id' => $torrent->id]) }}"
                                class="badge-extra text-red"
                                data-toggle="tooltip"
                                title="{{ $torrent->leechers }} {{ strtolower(__('torrent.leechers')) }}"
                        >
                            <i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-down"></i> {{ $torrent->leechers }}
                        </a>
                        <a
                                href="{{ route('history', ['id' => $torrent->id]) }}"
                                class="badge-extra text-info"
                                data-toggle="tooltip"
                                title="{{ $torrent->times_completed }} {{ strtolower(__('common.times')) }}"
                        >
                            <i class="{{ config('other.font-awesome') }} fa-fw fa-check"></i> {{ $torrent->times_completed }}
                        </a>

                        @if ($torrent->seeders == 0)
                            @if ($last_seed_activity)
                                <span class="badge-extra text-orange torrent-last-seed-activity">
                                    <i class="{{ config('other.font-awesome') }} fa-fw fa-clock"></i> {{ __('torrent.last-seed-activity') }}: {{ $last_seed_activity->updated_at->diffForHumans() }}
                                </span>
                            @else
                                <span class="badge-extra text-orange torrent-last-seed-activity">
                                    <i class="{{ config('other.font-awesome') }} fa-fw fa-clock"></i> {{ __('torrent.last-seed-activity') }}: {{ __('common.unknown') }}
                                </span>
                            @endif
                        @endif


                        <span class="torrent-info-hash badge-extra pull-right">
                            {{ __('torrent.info-hash') }}: {{ $torrent->info_hash }}
                        </span>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="table-responsive" x-show="show">
        <table class="table table-condensed table-bordered table-striped">
            <tbody>

            @if ($torrent->featured == '0')
                <tr class="success torrent-discounts">
                    <td>
                        <strong>{{ __('torrent.discounts') }}</strong>
                    </td>
                    <td>
                        @if ($torrent->doubleup == '1' || $torrent->free > '1' || config('other.freeleech') == '1' || config('other.doubleup') == '1' || $personal_freeleech || $user->group->is_freeleech == '1' || $freeleech_token)
                            @if ($freeleech_token)
                                <span class="badge-extra text-bold">
                                    <i class="{{ config('other.font-awesome') }} fa-coins text-bold"></i> {{ __('common.fl_token') }}
                                </span>
                            @endif

                            @if ($user->group->is_freeleech == '1')
                                <span class="badge-extra text-bold">
                                    <i class="{{ config('other.font-awesome') }} fa-trophy text-purple"></i> {{ __('common.special') }} {{ __('torrent.freeleech') }}
                                </span>
                            @endif

                            @if ($personal_freeleech)
                                <span class="badge-extra text-bold">
                                    <i class="{{ config('other.font-awesome') }} fa-id-badge text-orange"></i> {{ __('common.personal') }} {{ __('torrent.freeleech') }}
                                </span>
                            @endif

                            @if ($torrent->doubleup == '1')
                                <span class="badge-extra text-bold">
                                    <i class="{{ config('other.font-awesome') }} fa-gem text-green"></i> {{ __('torrent.double-upload') }}
                                </span>
                            @endif

                            @if ($user->group->is_double_upload == '1')
                                <span class="badge-extra text-bold">
                                    <i class="{{ config('other.font-awesome') }} fa-trophy text-purple"></i> {{ __('common.special') }} {{ __('torrent.double-upload') }}
                                </span>
                            @endif

                            @if ($torrent->free >= '90')
                                <span class="badge-extra text-bold">
                                    <i class="{{ config('other.font-awesome') }} fa-star text-gold"></i> {{ $torrent->free }}% {{ __('common.free') }}
                                    @if ($torrent->fl_until !== null) <span>{{ Illuminate\Support\Carbon::now()->diffForHumans($torrent->fl_until) }} Freeleech expires.</span> @endif
                                </span>
                            @elseif ($torrent->free < '90' && $torrent->free >= '30')
                                <style>
                                    .star50 {
                                        position: relative;
                                    }

                                    .star50:after {
                                        content: "\f005";
                                        position: absolute;
                                        left: 0;
                                        top: 0;
                                        width: 50%;
                                        overflow: hidden;
                                        color: #FFB800;
                                    }
                                </style>
                                <span class="badge-extra text-bold">
                                    <i class="star50 {{ config('other.font-awesome') }} fa-star"></i> {{ $torrent->free }}% {{ __('common.free') }}
                                    @if ($torrent->fl_until !== null) <span>{{ Illuminate\Support\Carbon::now()->diffForHumans($torrent->fl_until) }} Freeleech expires.</span> @endif
                                </span>
                            @elseif ($torrent->free < '30' && $torrent->free != '0')
                                <style>
                                    .star30 {
                                        position: relative;
                                    }

                                    .star30:after {
                                        content: "\f005";
                                        position: absolute;
                                        left: 0;
                                        top: 0;
                                        width: 30%;
                                        overflow: hidden;
                                        color: #FFB800;
                                    }
                                </style>
                                <span class="badge-extra text-bold">
                                    <i class="star30 {{ config('other.font-awesome') }} fa-star"></i> {{ $torrent->free }}% {{ __('common.free') }}
                                    @if ($torrent->fl_until !== null) <span>{{ Illuminate\Support\Carbon::now()->diffForHumans($torrent->fl_until) }} Freeleech expires.</span> @endif
                                </span>
                            @endif

                            @if (config('other.freeleech') == '1')
                                <span class="badge-extra text-bold">
                                    <i class="{{ config('other.font-awesome') }} fa-globe text-blue"></i> {{ __('common.global') }} {{ __('torrent.freeleech') }}
                                </span>
                            @endif

                            @if (config('other.doubleup') == '1')
                                <span class="badge-extra text-bold">
                                    <i class="{{ config('other.font-awesome') }} fa-globe text-green"></i> {{ __('common.global') }} {{ strtolower(__('torrent.double-upload')) }}
                                </span>
                            @endif
                        @else
                            <span class="text-bold text-danger">
                                <i class="{{ config('other.font-awesome') }} fa-frown"></i> {{ __('torrent.no-discounts') }}
                            </span>
                        @endif
                    </td>
                </tr>
            @endif

            @if ($torrent->featured == 1)
                <tr class="info torrent-featured">
                    <td>
                        <strong>{{ __('torrent.featured') }}</strong>
                    </td>
                    <td>
                        <span class="badge-user text-bold text-pink" style="background-image:url(https://i.imgur.com/F0UCb7A.gif);">
                            {{ __('torrent.featured-until') }} {{ $featured->created_at->addDay(7)->toFormattedDateString() }} ({{ $featured->created_at->addDay(7)->diffForHumans() }}!)
                        </span>
                        <span class="small">
                            <em>{!! __('torrent.featured-desc') !!}</em>
                        </span>
                    </td>
                </tr>
            @endif

            <tr class="torrent-name">
                <td class="col-sm-2">
                    <strong>{{ __('torrent.name') }}</strong>
                </td>
                <td>{{ $torrent->name }}</td>
            </tr>

            <tr class="torrent-uploader">
                <td class="col-sm-2"><strong>{{ __('torrent.uploader') }}</strong></td>
                <td>
                    @if ($torrent->anon == 1)
                        <span class="badge-user text-orange text-bold">{{ strtoupper(__('common.anonymous')) }}
                            @if (auth()->user()->id == $torrent->user->id || auth()->user()->group->is_modo)
                                <a href="{{ route('users.show', ['username' => $torrent->user->username]) }}">
                                    ({{ $torrent->user->username }})
                                </a>
                            @endif
                        </span>
                    @else
                        <a href="{{ route('users.show', ['username' => $torrent->user->username]) }}">
                            <span class="badge-user text-bold" style="color:{{ $torrent->user->group->color }}; background-image:{{ $torrent->user->group->effect }};">
                                <i class="{{ $torrent->user->group->icon }}" data-toggle="tooltip"
                                   data-original-title="{{ $torrent->user->group->name }}"></i> {{ $torrent->user->username }}
                            </span>
                        </a>
                    @endif

                    @if ($torrent->anon !== 1 && $torrent->user->private_profile !== 1)
                        @if ($torrent->user->followers()->where('users.id', '=', auth()->user()->id)->exists())
                            <form class="form-inline" role="form"
                                  action="{{ route('users.followers.destroy', ['user' => $torrent->user]) }}"
                                  style="display: inline-block;" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="form-group">
                                    <button type="submit" id="delete-follow-{{ $torrent->user->id }}"
                                            class="btn btn-xs btn-info"
                                            title="{{ __('user.unfollow') }}">
                                        <i class="{{ config('other.font-awesome') }} fa-user"></i> {{ __('user.unfollow') }}
                                    </button>
                                </div>
                            </form>
                        @else
                            <form class="form-inline" role="form"
                                  action="{{ route('users.followers.store', ['user' => $torrent->user]) }}"
                                  style="display: inline-block;" method="POST">
                                @csrf
                                <div class="form-group">
                                    <button type="submit" id="follow-user-{{ $torrent->user->id }}"
                                            class="btn btn-xs btn-success"
                                            title="{{ __('user.follow') }}">
                                        <i class="{{ config('other.font-awesome') }} fa-user"></i> {{ __('user.follow') }}
                                    </button>
                                </div>
                            </form>
                        @endif
                    @endif
                </td>
            </tr>

            <tr class="torrent-uploaded-time">
                <td class="col-sm-2"><strong>{{ __('torrent.uploaded') }}</strong></td>
                <td>{{ $torrent->created_at }} ({{ $torrent->created_at->diffForHumans() }})</td>
            </tr>

            <tr class="torrent-size">
                <td class="col-sm-2"><strong>{{ __('torrent.size') }}</strong></td>
                <td>{{ $torrent->getSize() }}</td>
            </tr>

            <tr class="torrent-estimated-ratio">
                <td class="col-sm-2"><strong>{{ __('torrent.estimated-ratio') }}</strong></td>
                <td>{{ $user->ratioAfterSizeString($torrent->size, $torrent->isFreeleech(auth()->user())) }}</td>
            </tr>

            <tr class="torrent-category">
                <td class="col-sm-2"><strong>{{ __('torrent.category') }}</strong></td>
                <td><i class="{{ $torrent->category->icon }} torrent-icon torrent-icon-small"
                       data-toggle="tooltip"
                       data-original-title="{{ $torrent->category->name }} {{ __('torrent.torrent') }}"></i>
                    {{ $torrent->category->name }}
                </td>
            </tr>

            <tr class="torrent-resolution">
                <td class="col-sm-2"><strong>{{ __('torrent.resolution') }}</strong></td>
                <td>{{ $torrent->resolution->name ?? 'No Res' }}</td>
            </tr>

            <tr class="torrent-type">
                <td class="col-sm-2"><strong>{{ __('torrent.type') }}</strong></td>
                <td>{{ $torrent->type->name }}</td>
            </tr>

            @if (isset($torrent->region_id))
                <tr class="torrent-region">
                    <td class="col-sm-2"><strong>{{ __('torrent.region') }}</strong></td>
                    <td>{{ $torrent->region->name }}</td>
                </tr>
            @endif

            @if (isset($torrent->distributor_id))
                <tr class="torrent-distributor">
                    <td class="col-sm-2"><strong>{{ __('torrent.distributor') }}</strong></td>
                    <td>{{ $torrent->distributor->name }}</td>
                </tr>
            @endif

            <tr class="torrent-stream-optimized">
                <td class="col-sm-2"><strong>{{ __('torrent.stream-optimized') }}?</strong></td>
                <td>
                    @if ($torrent->stream == "1") {{ __('common.yes') }} @else {{ __('common.no') }} @endif
                </td>
            </tr>

            <tr class="torrent-internal">
                <td class="col-sm-2"><strong>{{ __('torrent.internal-release') }}?</strong></td>
                <td>
                    @if ($torrent->internal == "1") {{ __('common.yes') }} @else {{ __('common.no') }} @endif
                </td>
            </tr>

            <tr class="torrent-personal-release">
                <td class="col-sm-2"><strong>Personal Release?</strong></td>
                <td>
                    @if ($torrent->personal_release == "1") {{ __('common.yes') }} @else {{ __('common.no') }} @endif
                </td>
            </tr>

            <tr class="torrent-highspeed">
                <td class="col-sm-2"><strong>{{ __('common.high-speeds') }}?</strong></td>
                <td>
                    @if ($torrent->highspeed == '1') {{ __('common.yes') }} @else {{ __('common.no') }} @endif
                </td>
            </tr>

            <tr class="torrent-sd">
                <td class="col-sm-2"><strong>{{ __('torrent.sd-content') }}?</strong></td>
                <td>
                    @if ($torrent->sd == '1') {{ __('common.yes') }} @else {{ __('common.no') }} @endif
                </td>
            </tr>

            <tr class="torrent-info-hash">
                <td class="col-sm-2"><strong>{{ __('torrent.info-hash') }}</strong></td>
                <td>{{ $torrent->info_hash }}</td>
            </tr>

            <tr class="torrent-peers">
                <td class="col-sm-2"><strong>{{ __('torrent.peers') }}</strong></td>
                <td>
                    <span class="badge-extra text-green">
                        <i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-up"></i> {{ $torrent->seeders }}
                    </span>
                    <span class="badge-extra text-red">
                        <i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-down"></i> {{ $torrent->leechers }}
                    </span>
                    <span class="badge-extra text-info">
                        <i class="{{ config('other.font-awesome') }} fa-fw fa-check"></i>{{ $torrent->times_completed }} {{ strtolower(__('common.times')) }}
                    </span>
                    <span class="badge-extra">
                        <a href="{{ route('peers', ['id' => $torrent->id]) }}"
                           title="{{ __('common.view') }} {{ __('torrent.peers') }}">{{ __('common.view') }} {{ __('torrent.peers') }}
                        </a>
                    </span>
                    <span class="badge-extra">
                        <a href="{{ route('history', ['id' => $torrent->id]) }}"
                           title="{{ __('common.view') }} {{ __('torrent.history') }}">{{ __('common.view') }} {{ __('torrent.history') }}
                        </a>
                    </span>
                </td>
            </tr>

            @if ($torrent->seeders == 0)
                <tr class="torrent-last-seed-activity">
                    <td class="col-sm-2"><strong>{{ __('torrent.last-seed-activity') }}</strong></td>
                    <td>
                        @if ($last_seed_activity)
                            <span class="badge-extra text-orange">
                                <i class="{{ config('other.font-awesome') }} fa-fw fa-clock"></i> {{ $last_seed_activity->updated_at->diffForHumans() }}
                            </span>
                        @else
                            <span class="badge-extra text-orange">
                                <i class="{{ config('other.font-awesome') }} fa-fw fa-clock"></i> {{ __('common.unknown') }}
                            </span>
                        @endif
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>