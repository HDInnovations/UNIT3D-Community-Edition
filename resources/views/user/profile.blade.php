@extends('layout.default')

@section('title')
    <title>{{ $user->username }} - @lang('common.members') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description"
          content="@lang('user.profile-desc', ['user' => $user->username, 'title' => config('other.title')])">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('profile', ['username' => $user->username, 'id' => $user->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        @if ( $user->private_profile == 1 && auth()->user()->id != $user->id && !auth()->user()->group->is_modo )
            <div class="container">
                <div class="jumbotron shadowed">
                    <div class="container">
                        <h1 class="mt-5 text-center">
                            <i class="{{ config('other.font-awesome') }} fa-times text-danger"></i>@lang('user.private-profile')
                        </h1>
                        <div class="separator"></div>
                        <p class="text-center">@lang('user.not-authorized')</p>
                    </div>
                </div>
            </div>
        @else
            <div class="well">
                <div class="row">
                    <div class="col-md-12 profile-footer">
                        {{ $user->username }} - @lang('user.recent-achievements')
                        <i class="{{ config('other.font-awesome') }} fa-trophy text-success"></i>
                        <span>{{ $user->unlockedAchievements()->count() }} :</span>
                        @foreach ($user->unlockedAchievements() as $a)
                            <img src="/img/badges/{{ $a->details->name }}.png" data-toggle="tooltip" title=""
                                 height="50px" data-original-title="{{ $a->details->name }}">
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="well">
                <div class="row">
                    <div class="col-md-12 profile-footer followers">
                        {{ $user->username }} - @lang('user.followers')
                        <i class="{{ config('other.font-awesome') }} fa-users text-success"></i>
                        <span>{{ $followers->count() }} :</span>
                        @foreach ($followers as $f)
                            @if ($f->user->image != null)
                                <a href="{{ route('profile', ['username' => $f->user->username, 'id' => $f->user_id]) }}">
                                    <img src="{{ url('files/img/' . $f->user->image) }}" data-toggle="tooltip"
                                         title="{{ $f->user->username }}" height="50px"
                                         data-original-title="{{ $f->user->username }}">
                                </a>
                            @else
                                <a href="{{ route('profile', ['username' => $f->user->username, 'id' => $f->user_id]) }}">
                                    <img src="{{ url('img/profile.png') }}" data-toggle="tooltip"
                                         title="{{ $f->user->username }}" height="50px"
                                         data-original-title="{{ $f->user->username }}">
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="block">
                <div class="header gradient blue">
                    <div class="inner_content">
                        <div class="content">
                            <div class="col-md-2">
                                @if ($user->image != null)
                                    <img src="{{ url('files/img/' . $user->image) }}" alt="{{ $user->username }}"
                                         class="img-circle">
                                @else
                                    <img src="{{ url('img/profile.png') }}" alt="{{ $user->username }}"
                                         class="img-circle">
                                @endif
                            </div>
                            <div class="col-lg-10">
                                <h2>{{ $user->username }}
                                    @if ($user->isOnline())
                                        <i class="{{ config('other.font-awesome') }} fa-circle text-green" data-toggle="tooltip" title=""
                                           data-original-title="@lang('user.online')"></i>
                                    @else
                                        <i class="{{ config('other.font-awesome') }} fa-circle text-red" data-toggle="tooltip" title=""
                                           data-original-title="@lang('user.offline')"></i>
                                    @endif
                                    <a href="{{ route('create', ['receiver_id' => $user->id, 'username' => $user->username]) }}">
                                        <i class="{{ config('other.font-awesome') }} fa-envelope text-info"></i>
                                    </a>
                                    <a href="{{ route('bonus', ['username' => $user->username]) }}">
                                        <i class="{{ config('other.font-awesome') }} fa-gift text-info"></i>
                                    </a>
                                    @if ($user->getWarning() > 0)
                                        <i class="{{ config('other.font-awesome') }} fa-exclamation-circle text-orange" aria-hidden="true"
                                           data-toggle="tooltip" title="" data-original-title="@lang('user.active-warning')">
                                        </i>
                                    @endif
                                    @if ($user->notes->count() > 0 && auth()->user()->group->is_modo)
                                        <a href="{{ route('user_setting', ['username' => $user->username, 'id' => $user->id]) }}"
                                           class="edit">
                                        <i class="{{ config('other.font-awesome') }} fa-comment fa-beat text-danger" aria-hidden="true" data-toggle="tooltip"
                                            title="" data-original-title="@lang('user.staff-noted')">
                                        </i>
                                        </a>
                                    @endif
                                </h2>
                                <h4>@lang('common.group'): <span class="badge-user text-bold"
                                                                       style="color:{{ $user->group->color }}; background-image:{{ $user->group->effect }};"><i
                                                class="{{ $user->group->icon }}" data-toggle="tooltip" title=""
                                                data-original-title="{{ $user->group->name }}"></i> {{ $user->group->name }}</span>
                                </h4>
                                <h4>@lang('user.registration-date') {{ $user->created_at === null ? "N/A" : date('M d Y', $user->created_at->getTimestamp()) }}</h4>
                                <span style="float:left;">
        @if (auth()->user()->id != $user->id)
                                        @if (auth()->user()->isFollowing($user->id))
                                            <a href="{{ route('unfollow', ['user' => $user->id]) }}"
                                               id="delete-follow-{{ $user->target_id }}" class="btn btn-xs btn-info"
                                               title="@lang('user.unfollow')"><i
                                                        class="{{ config('other.font-awesome') }} fa-user"></i> @lang('user.unfollow') {{ $user->username }}</a>
                                        @else
                                            <a href="{{ route('follow', ['user' => $user->id]) }}"
                                               id="follow-user-{{ $user->id }}" class="btn btn-xs btn-success"
                                               title="@lang('user.follow')"><i
                                                        class="{{ config('other.font-awesome') }} fa-user"></i> @lang('user.follow') {{ $user->username }}</a>
                                        @endif
                                        <button class="btn btn-xs btn-danger" data-toggle="modal"
                                                data-target="#modal_user_report"><i
                                                    class="{{ config('other.font-awesome') }} fa-eye"></i> @lang('user.report')</button>
        </span>
                                <span style="float:right;">
        @if (auth()->check() && auth()->user()->group->is_modo)
                                        @if ($user->group->id == 5)
                                            <button class="btn btn-xs btn-warning" data-toggle="modal"
                                                    data-target="#modal_user_unban"><span
                                                        class="{{ config('other.font-awesome') }} fa-undo"></span> @lang('user.unban') </button>
                                        @else
                                            <button class="btn btn-xs btn-danger" data-toggle="modal"
                                                    data-target="#modal_user_ban"><span
                                                        class="{{ config('other.font-awesome') }} fa-ban"></span> @lang('user.ban')</button>
                                        @endif
                                        <a href="{{ route('user_setting', ['username' => $user->username, 'id' => $user->id]) }}"
                                           class="btn btn-xs btn-warning"><span
                                                    class="{{ config('other.font-awesome') }} fa-pencil"></span> @lang('user.edit') </a>
                                        <button class="btn btn-xs btn-danger" data-toggle="modal"
                                                data-target="#modal_user_delete"><span
                                                    class="{{ config('other.font-awesome') }} fa-trash"></span> @lang('user.delete') </button>
                                    @endif
                                    @endif
        </span>
                            </div>
                        </div>
                    </div>
                </div>

                <h3><i class="{{ config('other.font-awesome') }} fa-unlock"></i> @lang('user.public-info')</h3>
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered">
                    <tbody>
                    <tr>
                        <td colspan="2">
                            <ul class="list-inline mb-0">
                                <li>
                                    <span class="badge-extra text-green text-bold"><i
                                                class="{{ config('other.font-awesome') }} fa-upload"></i> @lang('user.total-uploads')
                                        : {{ $user->torrents->count() }}</span>
                                </li>
                                <li>
                                    <span class="badge-extra text-red text-bold"><i
                                                class="{{ config('other.font-awesome') }} fa-download"></i> @lang('user.total-downloads')
                                        : {{ $history->where('actual_downloaded', '>', 0)->count() }}</span>
                                </li>
                                <li>
                                    <span class="badge-extra text-green text-bold"><i
                                                class="{{ config('other.font-awesome') }} fa-cloud-upload"></i> @lang('user.total-seeding')
                                        : {{ $user->getSeeding() }}</span>
                                </li>
                                <li>
                                    <span class="badge-extra text-red text-bold"><i
                                                class="{{ config('other.font-awesome') }} fa-cloud-download"></i> @lang('user.total-leeching')
                                        : {{ $user->getLeeching() }}</span>
                                </li>
                            </ul>
                        </td>
                    </tr>
            <tr>
                <td class="col-md-2">@lang('torrent.downloaded')</td>
                <td>
                    <span class="badge-extra text-red" data-toggle="tooltip" title=""
                          data-original-title="@lang('user.download-recorded')">{{ $user->getDownloaded() }}</span>
                    +
                    <span class="badge-extra text-orange" data-toggle="tooltip" title=""
                          data-original-title="@lang('user.download-bon')">{{ App\Helpers\StringHelper::formatBytes($bondownload , 2) }}</span> =
                    <span class="badge-extra text-blue" data-toggle="tooltip" title=""
                          data-original-title="@lang('user.download-true')">{{ App\Helpers\StringHelper::formatBytes($realdownload , 2) }}</span></td>
            </tr>
            <tr>
                <td>@lang('torrent.uploaded')</td>
                <td>
                    <span class="badge-extra text-green" data-toggle="tooltip" title=""
                          data-original-title="@lang('user.upload-recorded')">{{ $user->getUploaded() }}</span> -
                    <span class="badge-extra text-orange" data-toggle="tooltip" title=""
                          data-original-title="@lang('user.upload-bon')">{{ App\Helpers\StringHelper::formatBytes($bonupload , 2) }}</span> =
                    <span class="badge-extra text-blue" data-toggle="tooltip" title=""
                          data-original-title="@lang('user.upload-true')">{{ App\Helpers\StringHelper::formatBytes($realupload , 2) }}</span></td>
            </tr>
            <tr>
                <td>@lang('common.ratio')</td>
                <td><span class="badge-user group-member">{{ $user->getRatioString() }}</span></td>
            </tr>
            <tr>
                <td>@lang('user.total-seedtime-all')</td>
                <td>
                    <span class="badge-user group-member">{{ App\Helpers\StringHelper::timeElapsed($history->sum('seedtime')) }}</span>
                </td>
            </tr>
            <tr>
                <td>@lang('user.avg-seedtime')</td>
                <td>
                    <span class="badge-user group-member">{{ App\Helpers\StringHelper::timeElapsed(round($history->sum('seedtime') / max(1, $history->count()))) }}</span>
                </td>
            </tr>
            <tr>
                <td>Seeding Size</td>
                <td>
                    <span class="badge-user group-member">{{ App\Helpers\StringHelper::formatBytes($user->getTotalSeedSize() , 2) }}</span>
                </td>
            </tr>
            <tr>
                <td>@lang('user.badges')</td>
                <td>
                    @if ($user->getSeeding() >= 150)
                        <span class="badge-user" style="background-color:#3fb618; color:white;" data-toggle="tooltip"
                              title="" data-original-title="@lang('user.certified-seeder-desc')"><i
                                    class="{{ config('other.font-awesome') }} fa-upload"></i> @lang('user.certified-seeder')!</span>
                    @endif
                    @if ($history->where('actual_downloaded', '>', 0)->count() >= 100)
                        <span class="badge-user" style="background-color:#ff0039; color:white;" data-toggle="tooltip"
                              title="" data-original-title="@lang('user.certified-downloader-desc')"><i
                                    class="{{ config('other.font-awesome') }} fa-download"></i> @lang('user.certified-downloader')!</span>
                    @endif
                    @if ($user->getSeedbonus() >= 50000)
                        <span class="badge-user" style="background-color:#9400d3; color:white;" data-toggle="tooltip"
                              title="" data-original-title="@lang('user.certified-banker-desc')"><i
                                    class="{{ config('other.font-awesome') }} fa-star"></i> @lang('user.certified-banker')!</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>@lang('user.title')</td>
                <td>
                    <span class="badge-extra">{{ $user->title }}</span>
                </td>
            </tr>
            <tr>
                <td>@lang('user.about-me')</td>
                <td>
                    <span class="badge-extra">@emojione($user->getAboutHtml())</span>
                </td>
            </tr>
                    <tr>
                        <td>@lang('user.extra')</td>
                        <td>
                            <ul class="list-inline mb-0">
                                <li>
          <span class="badge-extra"><strong>@lang('bon.bon'):</strong>
            <span class="text-green text-bold">{{ $user->getSeedbonus() }}</span>
          </span>
                                </li>
                                <li>
          <span class="badge-extra"><strong>@lang('common.fl_tokens'):</strong>
            <span class="text-green text-bold">{{ $user->fl_tokens }}</span>
          </span>
                                </li>
                                <li>
          <span class="badge-extra"><strong>@lang('user.thanks-received'):</strong>
            <span class="text-pink text-bold">{{ $user->thanksReceived()->count() }}</span>
          </span>
                                </li>
                                <li>
          <span class="badge-extra"><strong>@lang('user.thanks-given'):</strong>
            <span class="text-pink text-bold"> {{ $user->thanksGiven()->count() }}</span>
          </span>
                                </li>
                                <li>
          <span class="badge-extra"><strong>@lang('user.tips-received'):</strong>
            <span class="text-pink text-bold">{{ number_format($user->bonReceived()->where('name', '=', 'tip')->sum('cost'), 2) }} @lang('bon.bon')</span>
          </span>
                                </li>
                                <li>
          <span class="badge-extra"><strong>@lang('user.tips-given'):</strong>
            <span class="text-pink text-bold">{{ number_format($user->bonGiven()->where('name', '=', 'tip')->sum('cost'), 2) }} @lang('bon.bon')</span>
          </span>
                                </li>
                                <li>
          <span class="badge-extra"><strong>@lang('user.gift-received'):</strong>
            <span class="text-pink text-bold">{{ number_format($user->bonReceived()->where('name', '=', 'gift')->sum('cost'), 2) }} @lang('bon.bon')</span>
          </span>
                                </li>
                                <li>
          <span class="badge-extra"><strong>@lang('user.gift-given'):</strong>
            <span class="text-pink text-bold">{{ number_format($user->bonGiven()->where('name', '=', 'gift')->sum('cost'), 2) }} @lang('bon.bon')</span>
          </span>
                                </li>
                                <li>
          <span class="badge-extra"><strong>@lang('user.bounty-received'):</strong>
            <span class="text-pink text-bold">{{ number_format($user->bonReceived()->where('name', '=', 'request')->sum('cost'), 2) }} @lang('bon.bon')</span>
          </span>
                                </li>
                                <li>
          <span class="badge-extra"><strong>@lang('user.bounty-given'):</strong>
            <span class="text-pink text-bold">{{ number_format($user->bonGiven()->where('name', '=', 'request')->sum('cost'), 2) }} @lang('bon.bon')</span>
          </span>
                                </li>
                                <li>
          <span class="badge-extra"><strong>@lang('user.article-comments'):</strong>
            <span class="text-green text-bold">{{ $user->comments()->where('article_id', '>', 0)->count() }}</span>
          </span>
                                </li>
                                <li>
          <span class="badge-extra"><strong>@lang('user.torrent-comments'):</strong>
            <span class="text-green text-bold">{{ $user->comments()->where('torrent_id', '>', 0)->count() }}</span>
          </span>
                                </li>
                                <li>
          <span class="badge-extra"><strong>@lang('user.request-comments'):</strong>
            <span class="text-green text-bold">{{ $user->comments()->where('requests_id', '>', 0)->count() }}</span>
          </span>
                                </li>
                                <li>
          <span class="badge-extra"><strong>@lang('user.topics'):</strong>
            <span class="text-green text-bold">{{ $user->topics->count() }}</span>
          </span>
                                </li>
                                <li>
          <span class="badge-extra"><strong>@lang('user.posts'):</strong>
            <span class="text-green text-bold">{{ $user->posts->count() }}</span>
          </span>
                                </li>
                            </ul>
                        </td>
                    </tr>
            <tr>
                <td>Warnings</td>
                <td>
                    <span class="badge-extra text-red text-bold"><strong>@lang('user.active-warnings')
                            : {{ $warnings->count() }} / {!! config('hitrun.max_warnings') !!}</strong></span>
          <span class="badge-extra"><strong>@lang('user.hit-n-runs-count'):</strong>
            <span class="{{ $user->hitandruns > 0 ? 'text-red' : 'text-green' }} text-bold">{{ $user->hitandruns }}</span>
          </span>
                    @if (auth()->check() && auth()->user()->group->is_modo)
                        <a href="{{ route('warninglog', ['username' => $user->username, 'id' => $user->id]) }}"><span
                                    class="badge-extra text-bold"><strong>@lang('user.warning-log')</strong></span></a>
                        <a href="{{ route('banlog', ['username' => $user->username, 'id' => $user->id]) }}"><span
                                    class="badge-extra text-bold"><strong>@lang('user.ban-log')</strong></span></a>
                    @endif
                    <div class="progress">
                        <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar"
                             style="width:.1%; border-bottom-color: #8c0408">
                        </div>
                        @php $percent = 100 / config('hitrun.max_warnings'); @endphp
                        @foreach ($warnings as $warning)
                            <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar"
                                 style="width: {{ $percent }}%; border-bottom-color: #8c0408">
                                {{ strtoupper(trans('user.warning')) }}
                            </div>
                        @endforeach
                    </div>
                </td>
            </tr>
            </tbody>
            </table>
            </div>
    </div>

    @if (auth()->check() && (auth()->user()->id == $user->id || auth()->user()->group->is_modo))
        <div class="block">
            <h3><i class="{{ config('other.font-awesome') }} fa-lock"></i> @lang('user.private-info')</h3>
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                <tbody>
                <tr>
                    <td class="col-md-2"> @lang('user.passkey')</td>
                    <td>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-xxs btn-info collapsed" data-toggle="collapse"
                                    data-target="#pid_block"
                                    aria-expanded="false">@lang('user.show-passkey')</button>
                        </div>
                        <div class="col-md-8">
                            <div id="pid_block" class="collapse" aria-expanded="false" style="height: 0px;">
                                <span class="text-monospace">{{ $user->passkey }}</span>
                                <br>
                            </div>
                            <span class="small text-red">@lang('user.passkey-warning')</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td> @lang('user.user-id')</td>
                    <td>{{ $user->id }}</td>
                </tr>
                <tr>
                    <td> @lang('common.email')</td>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <td> @lang('user.last-login')</td>
                    <td>@if ($user->last_login != null){{ $user->last_login->toDayDateTimeString() }}
                        ({{ $user->last_login->diffForHumans() }})@else N/A @endif</td>
                </tr>
                <tr>
                    <td> @lang('user.can-upload')</td>
                    @if ($user->can_upload == 1)
                        <td><i class="{{ config('other.font-awesome') }} fa-check text-green"></i></td>
                    @else
                        <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                    @endif
                </tr>
                <tr>
                    <td> @lang('user.can-download')</td>
                    @if ($user->can_download == 1)
                        <td><i class="{{ config('other.font-awesome') }} fa-check text-green"></i></td>
                    @else
                        <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                    @endif
                </tr>
                <tr>
                    <td> @lang('user.can-comment')</td>
                    @if ($user->can_comment == 1)
                        <td><i class="{{ config('other.font-awesome') }} fa-check text-green"></i></td>
                    @else
                        <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                    @endif
                </tr>
                <tr>
                    <td> @lang('user.can-request')</td>
                    @if ($user->can_request == 1)
                        <td><i class="{{ config('other.font-awesome') }} fa-check text-green"></i></td>
                    @else
                        <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                    @endif
                </tr>
                <tr>
                    <td> @lang('user.can-chat')</td>
                    @if ($user->can_chat == 1)
                        <td><i class="{{ config('other.font-awesome') }} fa-check text-green"></i></td>
                    @else
                        <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                    @endif
                </tr>
                <tr>
                    <td> @lang('user.can-invite')</td>
                    @if ($user->can_invite == 1)
                        <td><i class="{{ config('other.font-awesome') }} fa-check text-green"></i></td>
                    @else
                        <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                    @endif
                </tr>
                <tr>
                    <td> @lang('user.invites')</td>
                    @if ($user->invites > 0)
                        <td><span class="text-success text-bold"> {{ $user->invites }}</span><a
                                    href="{{ route('inviteTree', ['username' => $user->username, 'id' => $user->id]) }}"><span
                                        class="badge-extra text-bold"><strong>@lang('user.invite-tree')</strong></span></a>
                        </td>
                    @else
                        <td><span class="text-danger text-bold"> {{ $user->invites }}</span><a
                                    href="{{ route('inviteTree', ['username' => $user->username, 'id' => $user->id]) }}"><span
                                        class="badge-extra text-bold"><strong>@lang('user.invite-tree')</strong></span></a>
                        </td>
                    @endif
                </tr>
                <tr>
                    <td>Invited By</td>
                    <td>
                    @if ($invitedBy)
                        <a href="{{ route('profile', ['username' => $invitedBy->sender->username, 'id' => $invitedBy->sender->id]) }}">
                            <span class="text-bold" style="color: {{ $invitedBy->sender->group->color }}">
                                <i class="{{ $invitedBy->sender->group->icon }}"></i> {{ $invitedBy->sender->username }}
                            </span>
                        </a>
                    @else
                        <span class="text-bold"> Open Registration</span>
                    @endif
                    </td>
                </tr>
                </tbody>
            </table>
            </div>
            <br>
        </div>

        @if (auth()->check() && auth()->user()->id == $user->id)
            <div class="block">
                <div class="text-center">
                    <a href="{{ route('user_settings_form', ['username' => $user->username, 'id' => $user->id]) }}">
                        <button class="btn btn-primary"><span
                                    class="{{ config('other.font-awesome') }} fa-cog"></span> @lang('user.account-settings')</button>
                    </a>
                    <a href="{{ route('user_edit_profile_form', ['username' => $user->username, 'id' => $user->id]) }}">
                        <button class="btn btn-primary"><span
                                    class="{{ config('other.font-awesome') }} fa-user"></span> @lang('user.edit-profile')</button>
                    </a>
                    <a href="{{ route('invite') }}">
                        <button class="btn btn-primary"><span class="{{ config('other.font-awesome') }} fa-plus"></span> @lang('user.invites')
                        </button>
                    </a>
                    <a href="{{ route('user_clients', ['username' => $user->username, 'id' => $user->id]) }}">
                        <button class="btn btn-primary"><span
                                    class="{{ config('other.font-awesome') }} fa-server"></span> @lang('user.my-seedboxes')</button>
                    </a>
                    <a href="{{ route('wishlist', ['uid' => $user->id]) }}">
                        <button class="btn btn-primary"><span class="{{ config('other.font-awesome') }} fa-list"></span> @lang('user.my-wishlist')
                        </button>
                    </a>
                    <a href="{{ route('bookmarks') }}">
                        <button class="btn btn-primary"><span class="{{ config('other.font-awesome') }} fa-bookmark"></span> @lang('user.my-bookmarks')
                        </button>
                    </a>
                </div>
            </div>
        @endif

        @if (auth()->check() && (auth()->user()->id == $user->id || auth()->user()->group->is_modo))
            <div class="block">
                <div class="text-center">
                    <a href="{{ route('myuploads', ['username' => $user->username, 'id' => $user->id]) }}">
                        <button class="btn btn-success"><span
                                    class="{{ config('other.font-awesome') }} fa-upload"></span> @lang('user.uploads-table') </button>
                    </a>
                    <a href="{{ route('myactive', ['username' => $user->username, 'id' => $user->id]) }}">
                        <button class="btn btn-success"><span
                                    class="{{ config('other.font-awesome') }} fa-clock"></span> @lang('user.active-table') </button>
                    </a>
                    <a href="{{ route('myhistory', ['username' => $user->username, 'id' => $user->id]) }}">
                        <button class="btn btn-success"><span
                                    class="{{ config('other.font-awesome') }} fa-history"></span> @lang('user.history-table') </button>
                    </a>
                    <a href="{{ route('myResurrections', ['username' => $user->username, 'id' => $user->id]) }}">
                        <button class="btn btn-success"><span
                                    class="fab fa-snapchat-ghost"></span> My Resurrections</button>
                    </a>
                </div>
            </div>
        @endif

        <div class="block">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation"><a href="#hr" aria-controls="hitrun" role="tab"
                                           data-toggle="tab">@lang('user.hit-n-runs')</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <!-- HitRun -->
                <div role="tabpanel" class="tab-pane active" id="hr">
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered">
                            <div class="head"><strong>@lang('user.hit-n-runs-history')</strong></div>
                            <thead>
                            <th>@lang('torrent.torrent')</th>
                            <th>@lang('user.warned-on')</th>
                            <th>@lang('user.expires-on')</th>
                            <th>@lang('user.active')</th>
                            </thead>
                            <tbody>
                            @foreach ($hitrun as $hr)
                                <tr>
                                    <td>
                                        <a class="text-bold" href="{{ route('torrent', ['slug' => $hr->torrenttitle->slug, 'id' => $hr->torrenttitle->id]) }}">
                                            {{ $hr->torrenttitle->name }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $hr->created_at }}
                                    </td>
                                    <td>
                                        {{ $hr->expires_on }}
                                    </td>
                                    <td>
                                        @if ($hr->active == 1)
                                            <span class='label label-success'>@lang('common.yes')</span>
                                        @else
                                            <span class='label label-danger'>@lang('user.expired')</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $hitrun->links() }}
                    </div>
                </div>
                <!-- /HitRun -->
            </div>
        </div>
        @endif
        @endif
        </div>

        @include('user.user_modals', ['user' => $user])
@endsection
