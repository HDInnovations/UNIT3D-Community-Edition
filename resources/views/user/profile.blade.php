@extends('layout.default')

@section('title')
    <title>{{ $user->username }} - {{ __('common.members') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description"
          content="{{ __('user.profile-desc', ['user' => $user->username, 'title' => config('other.title')]) }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('users.show', [ 'username' => $user->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        @if (!auth()->user()->isAllowed($user))
            <div class="container pl-0 text-center">
                <div class="jumbotron shadowed">
                    <div class="container">
                        <h1 class="mt-5 text-center">
                            <i class="{{ config('other.font-awesome') }} fa-times text-danger"></i>{{ __('user.private-profile') }}
                        </h1>
                        <div class="separator"></div>
                        <p class="text-center">{{ __('user.not-authorized') }}</p>
                    </div>
                </div>
            </div>
        @else
            <div class="block">
                @if (auth()->user()->id == $user->id || auth()->user()->group->is_modo)
                    @include('user.buttons.profile')
                @else
                    @include('user.buttons.public')
                @endif
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
                                        <i class="{{ config('other.font-awesome') }} fa-circle text-green"
                                           data-toggle="tooltip" title=""
                                           data-original-title="{{ __('user.online') }}"></i>
                                    @else
                                        <i class="{{ config('other.font-awesome') }} fa-circle text-red"
                                           data-toggle="tooltip" title=""
                                           data-original-title="{{ __('user.offline') }}"></i>
                                    @endif
                                    <a href="{{ route('create', ['receiver_id' => $user->id, 'username' => $user->username]) }}">
                                        <i class="{{ config('other.font-awesome') }} fa-envelope text-info"></i>
                                    </a>
                                    <a href="#modal_user_gift" data-toggle="modal"
                                       data-target="#modal_user_gift"><i
                                                class="{{ config('other.font-awesome') }} fa-gift text-info"></i></a>
                                    @if ($user->getWarning() > 0)
                                        <i class="{{ config('other.font-awesome') }} fa-exclamation-circle text-orange"
                                           aria-hidden="true"
                                           data-toggle="tooltip" title=""
                                           data-original-title="{{ __('user.active-warning') }}">
                                        </i>
                                    @endif
                                    @if ($user->notes->count() > 0 && auth()->user()->group->is_modo)
                                        <a href="{{ route('user_setting', ['username' => $user->username]) }}"
                                           class="edit">
                                            <i class="{{ config('other.font-awesome') }} fa-comment fa-beat text-danger"
                                               aria-hidden="true" data-toggle="tooltip"
                                               title="" data-original-title="{{ __('user.staff-noted') }}">
                                            </i>
                                        </a>
                                    @endif
                                    @php $watched = App\Models\Watchlist::whereUserId($user->id)->first(); @endphp
                                    @if ($watched && auth()->user()->group->is_modo)
                                        <i class="{{ config('other.font-awesome') }} fa-eye fa-beat text-danger"
                                           aria-hidden="true" data-toggle="tooltip"
                                           title="" data-original-title="User is being watched!">
                                        </i>
                                    @endif
                                </h2>
                                <h4>{{ __('common.group') }}: <span class="badge-user text-bold"
                                                                 style="color:{{ $user->group->color }}; background-image:{{ $user->group->effect }};"><i
                                                class="{{ $user->group->icon }}" data-toggle="tooltip" title=""
                                                data-original-title="{{ $user->group->name }}"></i> {{ $user->group->name }}</span>
                                </h4>
                                <h4>{{ __('user.registration-date') }} {{ $user->created_at === null ? "N/A" : date('M d Y', $user->created_at->getTimestamp()) }}</h4>
                                @if (auth()->user()->id != $user->id)
                                    <span style="float:right;">
                                        @if (auth()->user()->group->is_modo)
                                            <button class="btn btn-xs btn-danger" data-toggle="modal"
                                                    data-target="#modal_warn_user">
                                            <span class="{{ config('other.font-awesome') }} fa-radiation-alt"></span> Warn User
                                        </button>
                                            <button class="btn btn-xs btn-warning" data-toggle="modal"
                                                    data-target="#modal_user_note"><span
                                                        class="{{ config('other.font-awesome') }} fa-sticky-note"></span> {{ __('user.note') }} </button>
                                            @if(! $watched)
                                                <button class="btn btn-xs btn-danger" data-toggle="modal"
                                                        data-target="#modal_user_watch">
                                            <span class="{{ config('other.font-awesome') }} fa-eye"></span> Watch </button>
                                            @else
                                                <form style="display: inline;"
                                                      action="{{ route('staff.watchlist.destroy', ['id' => $watched->id]) }}"
                                                      method="POST">
							                    @csrf
                                                    @method('DELETE')
							                    <button class="btn btn-xs btn-warning" type="submit">
								                    <i class="{{ config('other.font-awesome') }} fa-eye-slash"></i> Unwatch
							                    </button>
						                    </form>
                                            @endif
                                            @if ($user->group->id == 5)
                                                <button class="btn btn-xs btn-warning" data-toggle="modal"
                                                        data-target="#modal_user_unban"><span
                                                            class="{{ config('other.font-awesome') }} fa-undo"></span> {{ __('user.unban') }} </button>
                                            @else
                                                <button class="btn btn-xs btn-danger" data-toggle="modal"
                                                        data-target="#modal_user_ban"><span
                                                            class="{{ config('other.font-awesome') }} fa-ban"></span> {{ __('user.ban') }}</button>
                                            @endif
                                            <a href="{{ route('user_setting', ['username' => $user->username]) }}"
                                               class="btn btn-xs btn-warning"><span
                                                        class="{{ config('other.font-awesome') }} fa-pencil"></span> {{ __('user.edit') }} </a>
                                            <button class="btn btn-xs btn-danger" data-toggle="modal"
                                                    data-target="#modal_user_delete"><span
                                                        class="{{ config('other.font-awesome') }} fa-trash"></span> {{ __('user.delete') }} </button>
                                        @endif
                                        </span>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>


                @if (auth()->user()->isAllowed($user,'profile','show_profile_torrent_count'))
                    <div class="button-holder some-padding">
                        <div class="text-center">
                            <span class="badge-user badge-float p-10"><i
                                        class="{{ config('other.font-awesome') }} fa-upload"></i> {{ __('user.total-uploads') }}
                                : <span class="text-green text-bold">{{ $user->torrents_count }}</span></span>
                            <span class="badge-user badge-float p-10"><i
                                        class="{{ config('other.font-awesome') }} fa-download"></i> {{ __('user.total-downloads') }}
                                        : <span
                                        class="text-red text-bold">{{ $history->where('actual_downloaded', '>', 0)->count() }}</span></span>
                            <span class="badge-user badge-float p-10"><i
                                        class="{{ config('other.font-awesome') }} fa-cloud-upload"></i> {{ __('user.total-seeding') }}
                                        : <span class="text-green text-bold">{{ $user->getSeeding() }}</span></span>
                            <span class="badge-user badge-float p-10"><i
                                        class="{{ config('other.font-awesome') }} fa-cloud-download"></i> {{ __('user.total-leeching') }}
                                        : <span class="text-red text-bold">{{ $user->getLeeching() }}</span></span>
                        </div>
                    </div>
                @endif

                <hr class="no-space">


                <h3><i class="{{ config('other.font-awesome') }} fa-unlock"></i> {{ __('user.public-info') }}</h3>
                <div style="word-wrap: break-word; display: table; width: 100%;">
                    <table class="table user-info table-condensed table-striped table-bordered">
                        <tbody>
                        <tr>
                            <td colspan="2" class="text-bold">
                                <div class="button-holder">
                                    <div class="button-left-small">{{ __('user.user') }} {{ __('user.information') }}:</div>
                                    <div class="button-right-large">

                                    </div>
                                </div>
                            </td>
                        </tr>
                        @if (auth()->user()->isAllowed($user,'profile','show_profile_title'))
                            <tr>
                                <td>{{ __('user.title') }}</td>
                                <td>
                                    <span class="badge-extra">{{ $user->title }}</span>
                                </td>
                            </tr>
                        @endif
                        @if (auth()->user()->isAllowed($user,'profile','show_profile_about'))
                            <tr>
                                <td>{{ __('user.about') }}</td>
                                <td>
                                    @joypixels($user->getAboutHtml())
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="2" class="text-bold">
                                <div class="button-holder">
                                    <div class="button-left-small">{{ __('torrent.torrent') }} {{ __('torrent.statistics') }}
                                        :
                                    </div>
                                    <div class="button-right-large">

                                    </div>
                                </div>
                            </td>
                        </tr>
                        @if (auth()->user()->isAllowed($user,'profile','show_profile_torrent_ratio'))
                            <tr>
                                <td class="col-md-2">{{ __('user.download-recorded') }}</td>
                                <td>
                    <span class="badge-extra text-blue" data-toggle="tooltip" title=""
                          data-original-title="{{ __('user.download-recorded') }}">{{ $realdownload }}</span> =
                                    <span class="badge-extra text-info" data-toggle="tooltip" title=""
                                          data-original-title="Default Starter Download">{{ App\Helpers\StringHelper::formatBytes($def_download , 2) }}</span>
                                    +
                                    <span class="badge-extra text-red" data-toggle="tooltip" title=""
                                          data-original-title="{{ __('user.download-true') }}">{{ App\Helpers\StringHelper::formatBytes($his_down , 2) }}</span>
                                    −
                                    <span class="badge-extra text-green" data-toggle="tooltip" title=""
                                          data-original-title="Freeleech Downloads">{{ App\Helpers\StringHelper::formatBytes($free_down , 2) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('user.upload-recorded') }}</td>
                                <td>
                    <span class="badge-extra text-blue" data-toggle="tooltip" title=""
                          data-original-title="{{ __('user.upload-recorded') }}">{{ $user->getUploaded() }}</span> =
                                    <span class="badge-extra text-info" data-toggle="tooltip" title=""
                                          data-original-title="Default Starter Upload">{{ App\Helpers\StringHelper::formatBytes($def_upload , 2) }}</span>
                                    +
                                    <span class="badge-extra text-green" data-toggle="tooltip" title=""
                                          data-original-title="{{ __('user.upload-true') }}">{{ App\Helpers\StringHelper::formatBytes($his_upl , 2) }}</span>
                                    +
                                    <span class="badge-extra text-info" data-toggle="tooltip" title=""
                                          data-original-title="Upload from Multipliers">{{ App\Helpers\StringHelper::formatBytes($multi_upload , 2) }}</span>
                                    +
                                    <span class="badge-extra text-orange" data-toggle="tooltip" title=""
                                          data-original-title="{{ __('user.upload-bon') }}">{{ App\Helpers\StringHelper::formatBytes($bonupload , 2) }}</span>
                                    +
                                    <span class="badge-extra text-pink" data-toggle="tooltip" title=""
                                          data-original-title="Manually Added or Misc">{{ App\Helpers\StringHelper::formatBytes($man_upload , 2) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('user.upload-true') }}</td>
                                <td>
                    <span class="badge-extra text-green" data-toggle="tooltip" title=""
                          data-original-title="{{ __('user.upload-true') }}">{{ App\Helpers\StringHelper::formatBytes($his_upl , 2) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('common.ratio') }}</td>
                                <td><span class="badge-user group-member">{{ $user->getRatioString() }}</span></td>
                            </tr>
                            <tr>
                                <td>{{ __('common.buffer') }}</td>
                                <td>
                                    <span class="badge-user group-member">{{ $user->untilRatio(config('other.ratio')) }}</span>
                                </td>
                            </tr>
                        @endif
                        @if (auth()->user()->isAllowed($user,'profile','show_profile_torrent_seed'))
                            <tr>
                                <td>{{ __('user.total-seedtime') }}</td>
                                <td>
                                    <span class="badge-user group-member">{{ App\Helpers\StringHelper::timeElapsed($history->sum('seedtime')) }}</span>
                                    <span>({{ __('user.all-torrents') }})</span>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('user.avg-seedtime') }}</td>
                                <td>
                                    <span class="badge-user group-member">{{ App\Helpers\StringHelper::timeElapsed(round($history->sum('seedtime') / max(1, $history->count()))) }}</span>
                                    <span>({{ __('user.per-torrent') }})</span>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('user.seeding-size') }}</td>
                                <td>
                                    <span class="badge-user group-member">{{ App\Helpers\StringHelper::formatBytes($user->getTotalSeedSize() , 2) }}</span>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="2" class="text-bold">
                                <div class="button-holder">
                                    <div class="button-left-small">{{ __('user.user') }} {{ __('user.statistics') }}:</div>
                                    <div class="button-right-large">

                                    </div>
                                </div>
                            </td>
                        </tr>
                        @if (auth()->user()->isAllowed($user,'profile','show_profile_bon_extra'))
                            <tr>
                                <td>{{ __('user.bon') }}</td>
                                <td>
                                    <ul class="list-inline mb-0">
                                        <li>
          <span class="badge-extra"><strong>{{ __('bon.bon') }}:</strong>
            <span class="text-green text-bold">{{ $user->getSeedbonus() }}</span>
          </span>
                                        </li>
                                        <li>
          <span class="badge-extra"><strong>{{ __('user.tips-received') }}:</strong>
            <span class="text-pink text-bold">{{ \number_format($user->bonReceived()->where('name', '=', 'tip')->sum('cost'), 0, '.', ' ') }} {{ __('bon.bon') }}</span>
          </span>
                                        </li>
                                        <li>
          <span class="badge-extra"><strong>{{ __('user.tips-given') }}:</strong>
            <span class="text-pink text-bold">{{ \number_format($user->bonGiven()->where('name', '=', 'tip')->sum('cost'), 0, '.', ' ') }} {{ __('bon.bon') }}</span>
          </span>
                                        </li>
                                        <li>
          <span class="badge-extra"><strong>{{ __('user.gift-received') }}:</strong>
            <span class="text-pink text-bold">{{ \number_format($user->bonReceived()->where('name', '=', 'gift')->sum('cost'), 0, '.', ' ') }} {{ __('bon.bon') }}</span>
          </span>
                                        </li>
                                        <li>
          <span class="badge-extra"><strong>{{ __('user.gift-given') }}:</strong>
            <span class="text-pink text-bold">{{ \number_format($user->bonGiven()->where('name', '=', 'gift')->sum('cost'), 0, '.', ' ') }} {{ __('bon.bon') }}</span>
          </span>
                                        </li>
                                        <li>
          <span class="badge-extra"><strong>{{ __('user.bounty-received') }}:</strong>
            <span class="text-pink text-bold">{{ \number_format($user->bonReceived()->where('name', '=', 'request')->sum('cost'), 0, '.', ' ') }} {{ __('bon.bon') }}</span>
          </span>
                                        </li>
                                        <li>
          <span class="badge-extra"><strong>{{ __('user.bounty-given') }}:</strong>
            <span class="text-pink text-bold">{{ \number_format($user->bonGiven()->where('name', '=', 'request')->sum('cost'), 0, '.', ' ') }} {{ __('bon.bon') }}</span>
          </span>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @endif
                        @if (auth()->user()->isAllowed($user,'profile','show_profile_torrent_extra'))
                            <tr>
                                <td>{{ __('user.torrents') }}</td>
                                <td>
                                    <ul class="list-inline mb-0">
                                        <li>
          <span class="badge-extra"><strong>{{ __('common.fl_tokens') }}:</strong>
            <span class="text-green text-bold">{{ $user->fl_tokens }}</span>
          </span>
                                        </li>
                                        <li>
          <span class="badge-extra"><strong>{{ __('user.thanks-received') }}:</strong>
            <span class="text-pink text-bold">{{ $user->thanksReceived()->count() }}</span>
          </span>
                                        </li>
                                        <li>
          <span class="badge-extra"><strong>{{ __('user.thanks-given') }}:</strong>
            <span class="text-pink text-bold"> {{ $user->thanksGiven()->count() }}</span>
          </span>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @endif
                        @if (auth()->user()->isAllowed($user,'profile','show_profile_comment_extra'))
                            <tr>
                                <td>{{ __('user.comments') }}</td>
                                <td>
                                    <ul class="list-inline mb-0">
                                        <li>
          <span class="badge-extra"><strong>{{ __('user.article-comments') }}:</strong>
            <span class="text-green text-bold">{{ $user->comments()->where('article_id', '>', 0)->count() }}</span>
          </span>
                                        </li>
                                        <li>
          <span class="badge-extra"><strong>{{ __('user.torrent-comments') }}:</strong>
            <span class="text-green text-bold">{{ $user->comments()->where('torrent_id', '>', 0)->count() }}</span>
          </span>
                                        </li>
                                        <li>
          <span class="badge-extra"><strong>{{ __('user.request-comments') }}:</strong>
            <span class="text-green text-bold">{{ $user->comments()->where('requests_id', '>', 0)->count() }}</span>
          </span>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @endif
                        @if (auth()->user()->isAllowed($user,'profile','show_profile_forum_extra'))
                            <tr>
                                <td>{{ __('user.forums') }}</td>
                                <td>
                                    <ul class="list-inline mb-0">
                                        <li>
          <span class="badge-extra"><strong>{{ __('user.topics-started') }}:</strong>
            <span class="text-green text-bold">{{ $user->topics->count() }}</span>
          </span>
                                        </li>
                                        <li>
          <span class="badge-extra"><strong>{{ __('user.posts-posted') }}:</strong>
            <span class="text-green text-bold">{{ $user->posts->count() }}</span>
          </span>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @endif
                        @if (auth()->user()->isAllowed($user,'profile','show_profile_request_extra'))
                            <tr>
                                <td>{{ __('user.requests') }}</td>
                                <td>
                                    <ul class="list-inline mb-0">
                                        <li>
          <span class="badge-extra"><strong>{{ __('user.requested') }}:</strong>
            <span class="text-pink text-bold">{{ $requested }}</span>
          </span>
                                        </li>
                                        <li>
          <span class="badge-extra"><strong>{{ __('user.filled-request') }}:</strong>
            <span class="text-green text-bold">{{ $filled }}</span>
          </span>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="2" class="text-bold">{{ __('common.warnings') }}:</td>
                        </tr>
                        @if (auth()->user()->isAllowed($user,'profile','show_profile_warning'))
                            <tr>
                                <td colspan="2" class="text-right">

                                    <div class="progress">
                                        <div class="progress-bar progress-bar-danger progress-bar-striped active"
                                             role="progressbar"
                                             style="width:0; border-bottom-color: #8c0408;">
                                        </div>
                                        @foreach ($warnings as $warning)
                                            <div class="progress-bar progress-bar-danger progress-bar-striped active"
                                                 role="progressbar"
                                                 style="min-width: 80px; margin: 0 0 5px 5px; border-bottom-color: #8c0408;">
                                                {{ strtoupper(__('user.warning')) }}
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="some-padding">
                    <span class="badge-user text-red text-bold">

                        <strong>{{ __('user.active-warnings') }}
                            : {{ $warnings->count() }} / {!! config('hitrun.max_warnings') !!}</strong></span>
                                        <span class="badge-user"><strong>{{ __('user.hit-n-runs-count') }}:</strong>
            <span class="{{ $user->hitandruns > 0 ? 'text-red' : 'text-green' }} text-bold">{{ $user->hitandruns }}</span>
          </span>
                                        @if (auth()->user()->group->is_modo)
                                            <a href="{{ route('warnings.show', ['username' => $user->username]) }}"><span
                                                        class="badge-user text-bold"><strong>{{ __('user.warning-log') }}</strong></span></a>
                                            <a href="{{ route('banlog', ['username' => $user->username]) }}"><span
                                                        class="badge-user text-bold"><strong>{{ __('user.ban-log') }}</strong></span></a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>


                    @if(auth()->user()->id == $user->id)
                        @include('user.buttons.user')
                    @elseif(auth()->user()->group && auth()->user()->group->is_modo == 1)
                        @include('user.buttons.staff')
                    @endif


                </div>
            </div>

            <div class="well">
                <div class="row">
                    <div class="col-md-12 profile-footer">
                        {{ __('user.badges') }}
                        <i class="{{ config('other.font-awesome') }} fa-badge text-success"></i>
                        <span>:</span>
                        @if (auth()->user()->isAllowed($user,'profile','show_profile_badge'))
                            @if ($user->getSeeding() >= '150')
                                <span class="badge-user" style="background-color:#3fb618; color:rgb(255,255,255);"
                                      data-toggle="tooltip"
                                      title="" data-original-title="{{ __('user.certified-seeder-desc') }}"><i
                                            class="{{ config('other.font-awesome') }} fa-upload"></i> {{ __('user.certified-seeder') }}!</span>
                            @endif
                            @if ($history->where('actual_downloaded', '>', 0)->count() >= '100')
                                <span class="badge-user" style="background-color:#ff0039; color:rgb(255,255,255);"
                                      data-toggle="tooltip"
                                      title="" data-original-title="{{ __('user.certified-downloader-desc') }}"><i
                                            class="{{ config('other.font-awesome') }} fa-download"></i> {{ __('user.certified-downloader') }}!</span>
                            @endif
                            @if ($user->getSeedbonus() >= '50,000')
                                <span class="badge-user" style="background-color:#9400d3; color:rgb(255,255,255);"
                                      data-toggle="tooltip"
                                      title="" data-original-title="{{ __('user.certified-banker-desc') }}"><i
                                            class="{{ config('other.font-awesome') }} fa-coins"></i> {{ __('user.certified-banker') }}!</span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            <div class="well">
                <div class="row">
                    <div class="col-md-12 profile-footer">
                        {{ __('user.recent-achievements') }}
                        <i class="{{ config('other.font-awesome') }} fa-trophy text-success"></i>
                        <span>:</span>
                        @if (auth()->user()->isAllowed($user,'profile','show_profile_achievement'))
                            @php
                                $x=1;
                            @endphp
                            @foreach ($user->unlockedAchievements() as $a)
                                @php
                                    if($x > 25) { continue; }
                                @endphp
                                <img src="/img/badges/{{ $a->details->name }}.png" data-toggle="tooltip" title=""
                                     height="50px" data-original-title="{{ $a->details->name }}"
                                     alt="{{ $a->details->name }}">
                                @php
                                    $x++;
                                @endphp
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="well">
                <div class="row">
                    <div class="col-md-12 profile-footer followers">
                        {{ __('user.recent-followers') }}
                        <i class="{{ config('other.font-awesome') }} fa-users text-success"></i>
                        <span>:</span>
                        @if (auth()->user()->isAllowed($user,'profile','show_profile_follower'))
                            @foreach ($followers as $f)
                                @if ($f->user->image != null)
                                    <a href="{{ route('users.show', ['username' => $f->user->username]) }}">
                                        <img src="{{ url('files/img/' . $f->user->image) }}" data-toggle="tooltip"
                                             title="{{ $f->user->username }}" height="50px"
                                             data-original-title="{{ $f->user->username }}"
                                             alt="{{ $f->user->username }}">
                                    </a>
                                @else
                                    <a href="{{ route('users.show', ['username' => $f->user->username]) }}">
                                        <img src="{{ url('img/profile.png') }}" data-toggle="tooltip"
                                             title="{{ $f->user->username }}" height="50px"
                                             data-original-title="{{ $f->user->username }}"
                                             alt="{{ $f->user->username }}">
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            @if (auth()->user()->id == $user->id || auth()->user()->group->is_modo)
                <div class="block">
                    <h3><i class="{{ config('other.font-awesome') }} fa-broadcast-tower"></i> {{ __('user.client-list') }}
                    </h3>
                    <div style="word-wrap: break-word; display: table; width: 100%;">
                        <table class="table table-condensed table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>{{ __('torrent.client') }}</th>
                                <th>{{ __('common.ip') }}</th>
                                <th>{{ __('common.port') }}</th>
                                <th>{{ __('torrent.started') }}</th>
                                <th>{{ __('torrent.last-update') }}</th>
                                <th>{{ __('torrent.torrents') }}</th>
                                @if (\config('announce.connectable_check') == true)
                                    <th>Connectable</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @php $peer_array = []; @endphp
                            @foreach ($peers as $p)
                                @if (!in_array([$p->ip, $p->port], $peer_array))
                                    @php $count = App\Models\Peer::where('user_id', '=', $user->id)->where('ip', '=', $p->ip)->where('port', '=', $p->port)->count(); @endphp
                                    <tr>
                                        <td>
                                            <span class="badge-extra text-purple text-bold">{{ $p->agent }}</span>
                                        </td>
                                        <td><span class="badge-extra text-bold">{{ $p->ip }}</span></td>
                                        <td><span class="badge-extra text-bold">{{ $p->port }}</span></td>
                                        <td>{{ $p->created_at ? $p->created_at->diffForHumans() : 'N/A' }}</td>
                                        <td>{{ $p->updated_at ? $p->updated_at->diffForHumans() : 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('user_active_by_client', ['username' => $user->username, 'ip' => $p->ip, 'port' => $p->port]) }}"
                                               itemprop="url" class="l-breadcrumb-item-link">
                                                <span itemprop="title"
                                                      class="l-breadcrumb-item-link-title">{{ $count }}</span>
                                            </a>
                                        </td>
                                        @if (\config('announce.connectable_check') == true)
                                            @php
                                                $connectable = false;
                                                if (cache()->has('peers:connectable:'.$p->ip.'-'.$p->port.'-'.$p->agent)) {
                                                    $connectable = cache()->get('peers:connectable:'.$p->ip.'-'.$p->port.'-'.$p->agent);
                                                }
                                            @endphp
                                            <td>@choice('user.client-connectable-state', $connectable)</td>
                                        @endif
                                    </tr>
                                    @php array_push($peer_array, [$p->ip, $p->port]) @endphp
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="block">
                    <h3><i class="{{ config('other.font-awesome') }} fa-lock"></i> {{ __('user.private-info') }}</h3>
                    <div style="word-wrap: break-word; display: table; width: 100%;">
                        <table class="table user-info table-condensed table-striped table-bordered">
                            <tbody>
                            <tr>
                                <td colspan="2" class="text-bold">
                                    <div class="button-holder">
                                        <div class="button-left-small">{{ __('user.id-permissions') }}:</div>
                                        <div class="button-right-large">

                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('user.invited-by') }}</td>
                                <td>
                                    @if ($invitedBy)
                                        <a href="{{ route('users.show', ['username' => $invitedBy->sender->username]) }}">
                            <span class="text-bold" style="color:{{ $invitedBy->sender->group->color }}; ">
                                <i class="{{ $invitedBy->sender->group->icon }}"></i> {{ $invitedBy->sender->username }}
                            </span>
                                        </a>
                                    @else
                                        <span class="text-bold">{{ __('user.open-registration') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-2"> {{ __('user.passkey') }}</td>
                                <td>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-xxs btn-info collapsed"
                                                data-toggle="collapse"
                                                data-target="#pid_block"
                                                aria-expanded="false">{{ __('user.show-passkey') }}</button>
                                    </div>
                                    <div class="col-md-8">
                                        <div id="pid_block" class="collapse" aria-expanded="false" style="height: 0;">
                                            <span class="text-monospace">{{ $user->passkey }}</span>
                                            <br>
                                        </div>
                                        <span class="small text-red">{{ __('user.passkey-warning') }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td> {{ __('user.user-id') }}</td>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <td> {{ __('common.email') }}</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td> {{ __('user.last-login') }}</td>
                                <td>@if ($user->last_login != null){{ $user->last_login->toDayDateTimeString() }}
                                    ({{ $user->last_login->diffForHumans() }})@else N/A @endif</td>
                            </tr>
                            <tr>
                                <td> {{ __('user.can-upload') }}</td>
                                @if ($user->can_upload == 1)
                                    <td><i class="{{ config('other.font-awesome') }} fa-check text-green"></i></td>
                                @else
                                    <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                @endif
                            </tr>
                            <tr>
                                <td> {{ __('user.can-download') }}</td>
                                @if ($user->can_download == 1)
                                    <td><i class="{{ config('other.font-awesome') }} fa-check text-green"></i></td>
                                @else
                                    <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                @endif
                            </tr>
                            <tr>
                                <td> {{ __('user.can-comment') }}</td>
                                @if ($user->can_comment == 1)
                                    <td><i class="{{ config('other.font-awesome') }} fa-check text-green"></i></td>
                                @else
                                    <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                @endif
                            </tr>
                            <tr>
                                <td> {{ __('user.can-request') }}</td>
                                @if ($user->can_request == 1)
                                    <td><i class="{{ config('other.font-awesome') }} fa-check text-green"></i></td>
                                @else
                                    <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                @endif
                            </tr>
                            <tr>
                                <td> {{ __('user.can-chat') }}</td>
                                @if ($user->can_chat == 1)
                                    <td><i class="{{ config('other.font-awesome') }} fa-check text-green"></i></td>
                                @else
                                    <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                @endif
                            </tr>
                            <tr>
                                <td> {{ __('user.can-invite') }}</td>
                                @if ($user->can_invite == 1)
                                    <td><i class="{{ config('other.font-awesome') }} fa-check text-green"></i></td>
                                @else
                                    <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                                @endif
                            </tr>
                            <tr>
                                <td> {{ __('user.invites') }}</td>
                                @if ($user->invites > 0)
                                    <td><span class="text-success text-bold"> {{ $user->invites }}</span>
                                    </td>
                                @else
                                    <td><span class="text-danger text-bold"> {{ $user->invites }}</span>
                                    </td>
                                @endif
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                </div>







                <div class="block">
                    <h3><i class="{{ config('other.font-awesome') }} fa-bell"></i> {{ __('user.important-info') }}</h3>
                    <div class="table-responsive">
                        <table class="table user-info table-condensed table-striped table-bordered">
                            <thead>
                            <tr>
                                <th colspan="4" class="text-bold">
                                    <div class="button-holder">
                                        <div class="button-left-small">Hit & Runs</div>
                                        <div class="button-right-large">

                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </thead>

                            <thead>
                            <th>{{ __('torrent.torrent') }}</th>
                            <th>{{ __('user.warned-on') }}</th>
                            <th>{{ __('user.expires-on') }}</th>
                            <th>{{ __('user.active') }}</th>
                            </thead>

                            <tbody>
                            @foreach ($hitrun as $hr)
                                <tr>
                                    <td>
                                        <a class="text-bold"
                                           href="{{ route('torrent', ['id' => $hr->torrenttitle->id]) }}">
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
                                            <span class='label label-success'>{{ __('common.yes') }}</span>
                                        @else
                                            <span class='label label-danger'>{{ __('user.expired') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $hitrun->links() }}
                    </div>
                </div>







            @endif
        @endif
    </div>

    @include('user.user_modals', ['user' => $user])
@endsection
