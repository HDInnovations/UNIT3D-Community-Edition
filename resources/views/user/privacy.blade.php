@extends('layout.default')

@section('title')
    <title>{{ $user->username }} - Privacy - @lang('common.members') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('profile', ['slug' => $user->slug, 'id' => $user->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user_privacy', ['slug' => $user->slug, 'id' => $user->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }} @lang('user.privacy') @lang('user.settings')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block">
            @include('user.buttons.settings')
            <div class="header gradient red">
                <div class="inner_content">
                    <h1>{{ $user->username }} @lang('user.privacy-settings')</h1>
                </div>
            </div>
            <div class="container-fluid p-0 some-padding">
                <ul class="nav nav-tabs" role="tablist" id="basetabs">
                    <li class="active"><a href="#profile" data-toggle="tab">Profile</a></li>
                    <li><a href="#achievement" data-toggle="tab">Achievements</a></li>
                    <li><a href="#follower" data-toggle="tab">Followers</a></li>
                    <li><a href="#forum" data-toggle="tab">Forums</a></li>
                    <li><a href="#request" data-toggle="tab">Requests</a></li>
                    <li><a href="#torrent" data-toggle="tab">Torrents</a></li>
                    <li><a href="#other" data-toggle="tab">Other</a></li>
                </ul>
                <br>
                <div class="tab-content">


                    <div role="tabpanel" class="tab-pane" id="other">
                        <form role="form" method="POST" action="{{ route('privacy_other', ['username' => $user->slug, 'id' => $user->id]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="well">
                                <h3>@lang('user.other-privacy'):</h3>
                                <div class="help-block">@lang('user.other-help').</div>
                                <hr>
                                <div class="form-group">
                                    <div class="button-holder">
                                        <div class="button-left">
                                            @lang('user.other-privacy-online').
                                        </div>
                                        <div class="button-right">
                                            @if(!$user->privacy || ($user->privacy && $user->privacy->show_online == 1))
                                                <input type="checkbox" name="show_online" value="1" CHECKED />
                                            @else
                                                <input type="checkbox" name="show_online" value="1" />
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="some-padding">
                                    <h3>@lang('user.visible-to-other'):</h3>
                                    <div class="help-block">@lang('user.visible-to-other-help').</div>
                                    <hr>
                                    <div class="form-group">
                                        @foreach($groups as $group)
                                            @if($group->is_modo || $group->is_admin)
                                            @else
                                                <div class="button-holder">
                                                    <div class="button-left">
                                                        {{ $group->name }}
                                                    </div>
                                                    <div class="button-right">
                                                        @if(!$user->privacy || !$user->privacy->json_other_groups || $group->isAllowed($user->privacy->json_other_groups,$group->id))
                                                            <input type="checkbox" name="approved[]" value="{{ $group->id }}" CHECKED />
                                                        @else
                                                            <input type="checkbox" name="approved[]" value="{{ $group->id }}" />
                                                        @endif
                                                    </div>
                                                </div>
                                                <hr class="some-padding">
                                            @endif
                                        @endforeach
                                    </div>
                                    <hr class="some-padding">
                                </div>
                            </div>
                            <div class="well text-center">
                                <button type="submit" class="btn btn-primary">@lang('common.save') @lang('user.other-privacy')</button>
                            </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="request">
                        <form role="form" method="POST" action="{{ route('privacy_request', ['username' => $user->slug, 'id' => $user->id]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="well">
                                <h3>@lang('user.request-privacy'):</h3>
                                <div class="help-block">@lang('user.request-help').</div>
                                <hr>
                                <div class="form-group">
                                    <div class="button-holder">
                                        <div class="button-left">
                                            @lang('user.request-privacy-requested').
                                        </div>
                                        <div class="button-right">
                                            @if(!$user->privacy || ($user->privacy && $user->privacy->show_requested == 1))
                                                <input type="checkbox" name="show_requested" value="1" CHECKED />
                                            @else
                                                <input type="checkbox" name="show_requested" value="1" />
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="some-padding">
                                    <h3>@lang('user.visible-to-request'):</h3>
                                    <div class="help-block">@lang('user.visible-to-request-help').</div>
                                    <hr>
                                    <div class="form-group">
                                        @foreach($groups as $group)
                                            @if($group->is_modo || $group->is_admin)
                                            @else
                                                <div class="button-holder">
                                                    <div class="button-left">
                                                        {{ $group->name }}
                                                    </div>
                                                    <div class="button-right">
                                                        @if(!$user->privacy || !$user->privacy->json_request_groups || $group->isAllowed($user->privacy->json_request_groups,$group->id))
                                                            <input type="checkbox" name="approved[]" value="{{ $group->id }}" CHECKED />
                                                        @else
                                                            <input type="checkbox" name="approved[]" value="{{ $group->id }}" />
                                                        @endif
                                                    </div>
                                                </div>
                                                <hr class="some-padding">
                                            @endif
                                        @endforeach
                                    </div>
                                    <hr class="some-padding">
                                </div>
                            </div>
                            <div class="well text-center">
                                <button type="submit" class="btn btn-primary">@lang('common.save') @lang('user.request-privacy')</button>
                            </div>
                        </form>
                    </div>
                <div role="tabpanel" class="tab-pane" id="torrent">
                    <form role="form" method="POST" action="{{ route('privacy_torrent', ['username' => $user->slug, 'id' => $user->id]) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="well">
                            <h3>@lang('user.torrent-privacy'):</h3>
                            <div class="help-block">@lang('user.torrent-help').</div>
                            <hr>
                            <div class="form-group">
                                <div class="button-holder">
                                    <div class="button-left">
                                        @lang('user.torrent-privacy-upload').
                                    </div>
                                    <div class="button-right">
                                        @if(!$user->privacy || ($user->privacy && $user->privacy->show_upload == 1))
                                            <input type="checkbox" name="show_upload" value="1" CHECKED />
                                        @else
                                            <input type="checkbox" name="show_upload" value="1" />
                                        @endif
                                    </div>
                                </div>
                                <hr class="some-padding">
                                <div class="button-holder">
                                    <div class="button-left">
                                        @lang('user.torrent-privacy-download').
                                    </div>
                                    <div class="button-right">
                                        @if(!$user->privacy || ($user->privacy && $user->privacy->show_download == 1))
                                            <input type="checkbox" name="show_download" value="1" CHECKED />
                                        @else
                                            <input type="checkbox" name="show_download" value="1" />
                                        @endif
                                    </div>
                                </div>
                                <hr class="some-padding">
                                <div class="button-holder">
                                    <div class="button-left">
                                        @lang('user.torrent-privacy-peer').
                                    </div>
                                    <div class="button-right">
                                        @if(!$user->privacy || ($user->privacy && $user->privacy->show_peer == 1))
                                            <input type="checkbox" name="show_peer" value="1" CHECKED />
                                        @else
                                            <input type="checkbox" name="show_peer" value="1" />
                                        @endif
                                    </div>
                                </div>
                                <hr class="some-padding">
                                <h3>@lang('user.visible-to-torrent'):</h3>
                                <div class="help-block">@lang('user.visible-to-torrent-help').</div>
                                <hr>
                                <div class="form-group">
                                    @foreach($groups as $group)
                                        @if($group->is_modo || $group->is_admin)
                                        @else
                                            <div class="button-holder">
                                                <div class="button-left">
                                                    {{ $group->name }}
                                                </div>
                                                <div class="button-right">
                                                    @if(!$user->privacy || !$user->privacy->json_torrent_groups || $group->isAllowed($user->privacy->json_torrent_groups,$group->id))
                                                        <input type="checkbox" name="approved[]" value="{{ $group->id }}" CHECKED />
                                                    @else
                                                        <input type="checkbox" name="approved[]" value="{{ $group->id }}" />
                                                    @endif
                                                </div>
                                            </div>
                                            <hr class="some-padding">
                                        @endif
                                    @endforeach
                                </div>
                                <hr class="some-padding">
                            </div>
                        </div>
                        <div class="well text-center">
                            <button type="submit" class="btn btn-primary">@lang('common.save') @lang('user.torrent-privacy')</button>
                        </div>
                    </form>
                </div>
                    <div role="tabpanel" class="tab-pane active" id="profile">
                        <form role="form" method="POST" action="{{ route('privacy_profile', ['username' => $user->slug, 'id' => $user->id]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="well">
                                <h3>@lang('user.profile-privacy'):</h3>
                                <div class="help-block">@lang('user.profile-privacy-help').</div>
                                <hr>
                                <div class="form-group">
                                    <div class="button-holder">
                                        <div class="button-left">
                                            @lang('user.profile-privacy-torrent-count').
                                        </div>
                                        <div class="button-right">
                                            @if(!$user->privacy || ($user->privacy && $user->privacy->show_profile_torrent_count == 1))
                                                <input type="checkbox" name="show_profile_torrent_count" value="1" CHECKED />
                                            @else
                                                <input type="checkbox" name="show_profile_torrent_count" value="1" />
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="some-padding">
                                    <div class="button-holder">
                                        <div class="button-left">
                                            @lang('user.profile-privacy-title').
                                        </div>
                                        <div class="button-right">
                                            @if(!$user->privacy || ($user->privacy && $user->privacy->show_profile_title == 1))
                                                <input type="checkbox" name="show_profile_title" value="1" CHECKED />
                                            @else
                                                <input type="checkbox" name="show_profile_title" value="1" />
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="some-padding">
                                    <div class="button-holder">
                                        <div class="button-left">
                                            @lang('user.profile-privacy-about').
                                        </div>
                                        <div class="button-right">
                                            @if(!$user->privacy || ($user->privacy && $user->privacy->show_profile_about == 1))
                                                <input type="checkbox" name="show_profile_about" value="1" CHECKED />
                                            @else
                                                <input type="checkbox" name="show_profile_about" value="1" />
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="some-padding">
                                    <div class="button-holder">
                                        <div class="button-left">
                                            @lang('user.profile-privacy-torrent-ratio').
                                        </div>
                                        <div class="button-right">
                                            @if(!$user->privacy || ($user->privacy && $user->privacy->show_profile_torrent_ratio == 1))
                                                <input type="checkbox" name="show_profile_torrent_ratio" value="1" CHECKED />
                                            @else
                                                <input type="checkbox" name="show_profile_torrent_ratio" value="1" />
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="some-padding">
                                    <div class="button-holder">
                                        <div class="button-left">
                                            @lang('user.profile-privacy-torrent-seed').
                                        </div>
                                        <div class="button-right">
                                            @if(!$user->privacy || ($user->privacy && $user->privacy->show_profile_torrent_seed == 1))
                                                <input type="checkbox" name="show_profile_torrent_seed" value="1" CHECKED />
                                            @else
                                                <input type="checkbox" name="show_profile_torrent_seed" value="1" />
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="some-padding">
                                    <div class="button-holder">
                                        <div class="button-left">
                                            @lang('user.profile-privacy-bon-extra').
                                        </div>
                                        <div class="button-right">
                                            @if(!$user->privacy || ($user->privacy && $user->privacy->show_profile_bon_extra == 1))
                                                <input type="checkbox" name="show_profile_bon_extra" value="1" CHECKED />
                                            @else
                                                <input type="checkbox" name="show_profile_bon_extra" value="1" />
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="some-padding">
                                    <div class="button-holder">
                                        <div class="button-left">
                                            @lang('user.profile-privacy-torrent-extra').
                                        </div>
                                        <div class="button-right">
                                            @if(!$user->privacy || ($user->privacy && $user->privacy->show_profile_torrent_extra == 1))
                                                <input type="checkbox" name="show_profile_torrent_extra" value="1" CHECKED />
                                            @else
                                                <input type="checkbox" name="show_profile_torrent_extra" value="1" />
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="some-padding">
                                    <div class="button-holder">
                                        <div class="button-left">
                                            @lang('user.profile-privacy-comment-extra').
                                        </div>
                                        <div class="button-right">
                                            @if(!$user->privacy || ($user->privacy && $user->privacy->show_profile_comment_extra == 1))
                                                <input type="checkbox" name="show_profile_comment_extra" value="1" CHECKED />
                                            @else
                                                <input type="checkbox" name="show_profile_comment_extra" value="1" />
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="some-padding">
                                    <div class="button-holder">
                                        <div class="button-left">
                                            @lang('user.profile-privacy-request-extra').
                                        </div>
                                        <div class="button-right">
                                            @if(!$user->privacy || ($user->privacy && $user->privacy->show_profile_request_extra == 1))
                                                <input type="checkbox" name="show_profile_request_extra" value="1" CHECKED />
                                            @else
                                                <input type="checkbox" name="show_profile_request_extra" value="1" />
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="some-padding">
                                    <div class="button-holder">
                                        <div class="button-left">
                                            @lang('user.profile-privacy-forum-extra').
                                        </div>
                                        <div class="button-right">
                                            @if(!$user->privacy || ($user->privacy && $user->privacy->show_profile_forum_extra == 1))
                                                <input type="checkbox" name="show_profile_forum_extra" value="1" CHECKED />
                                            @else
                                                <input type="checkbox" name="show_profile_forum_extra" value="1" />
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="some-padding">
                                    <div class="button-holder">
                                        <div class="button-left">
                                            @lang('user.profile-privacy-warning').
                                        </div>
                                        <div class="button-right">
                                            @if(!$user->privacy || ($user->privacy && $user->privacy->show_profile_warning == 1))
                                                <input type="checkbox" name="show_profile_warning" value="1" CHECKED />
                                            @else
                                                <input type="checkbox" name="show_profile_warning" value="1" />
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="some-padding">
                                    <div class="button-holder">
                                        <div class="button-left">
                                            @lang('user.profile-privacy-badge').
                                        </div>
                                        <div class="button-right">
                                            @if(!$user->privacy || ($user->privacy && $user->privacy->show_profile_badge == 1))
                                                <input type="checkbox" name="show_profile_badge" value="1" CHECKED />
                                            @else
                                                <input type="checkbox" name="show_profile_badge" value="1" />
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="some-padding">
                                    <div class="button-holder">
                                        <div class="button-left">
                                            @lang('user.profile-privacy-achievement').
                                        </div>
                                        <div class="button-right">
                                            @if(!$user->privacy || ($user->privacy && $user->privacy->show_profile_achievement == 1))
                                                <input type="checkbox" name="show_profile_achievement" value="1" CHECKED />
                                            @else
                                                <input type="checkbox" name="show_profile_achievement" value="1" />
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="some-padding">
                                    <div class="button-holder">
                                        <div class="button-left">
                                            @lang('user.profile-privacy-follower').
                                        </div>
                                        <div class="button-right">
                                            @if(!$user->privacy || ($user->privacy && $user->privacy->show_profile_follower == 1))
                                                <input type="checkbox" name="show_profile_follower" value="1" CHECKED />
                                            @else
                                                <input type="checkbox" name="show_profile_follower" value="1" />
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="some-padding">
                                </div>
                                <h3>@lang('user.visible-to-profile'):</h3>
                                <div class="help-block">@lang('user.visible-to-profile-help').</div>
                                <hr>
                                <div class="form-group">
                                    @foreach($groups as $group)
                                        @if($group->is_modo || $group->is_admin)
                                        @else
                                            <div class="button-holder">
                                                <div class="button-left">
                                                    {{ $group->name }}
                                                </div>
                                                <div class="button-right">
                                                    @if(!$user->privacy || !$user->privacy->json_profile_groups || $group->isAllowed($user->privacy->json_profile_groups,$group->id))
                                                        <input type="checkbox" name="approved[]" value="{{ $group->id }}" CHECKED />
                                                    @else
                                                        <input type="checkbox" name="approved[]" value="{{ $group->id }}" />
                                                    @endif
                                                </div>
                                            </div>
                                            <hr class="some-padding">
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="well text-center">
                                <button type="submit" class="btn btn-primary">@lang('common.save') @lang('user.profile-privacy')</button>
                            </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="forum">
                        <form role="form" method="POST" action="{{ route('privacy_forum', ['username' => $user->slug, 'id' => $user->id]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="well">
                                <h3>@lang('user.forum-privacy'):</h3>
                                <div class="help-block">@lang('user.forum-help').</div>
                                <hr>
                                <div class="form-group">
                                    <div class="button-holder">
                                        <div class="button-left">
                                            @lang('user.forum-privacy-topic').
                                        </div>
                                        <div class="button-right">
                                            @if(!$user->privacy || ($user->privacy && $user->privacy->show_topic == 1))
                                                <input type="checkbox" name="show_topic" value="1" CHECKED />
                                            @else
                                                <input type="checkbox" name="show_topic" value="1" />
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="some-padding">
                                    <div class="button-holder">
                                        <div class="button-left">
                                            @lang('user.forum-privacy-post').
                                        </div>
                                        <div class="button-right">
                                            @if(!$user->privacy || ($user->privacy && $user->privacy->show_post == 1))
                                                <input type="checkbox" name="show_post" value="1" CHECKED />
                                            @else
                                                <input type="checkbox" name="show_post" value="1" />
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="some-padding">
                                    <h3>@lang('user.visible-to-forum'):</h3>
                                    <div class="help-block">@lang('user.visible-to-forum-help').</div>
                                    <hr>
                                    <div class="form-group">
                                        @foreach($groups as $group)
                                            @if($group->is_modo || $group->is_admin)
                                            @else
                                                <div class="button-holder">
                                                    <div class="button-left">
                                                        {{ $group->name }}
                                                    </div>
                                                    <div class="button-right">
                                                        @if(!$user->privacy || !$user->privacy->json_forum_groups || $group->isAllowed($user->privacy->json_forum_groups,$group->id))
                                                            <input type="checkbox" name="approved[]" value="{{ $group->id }}" CHECKED />
                                                        @else
                                                            <input type="checkbox" name="approved[]" value="{{ $group->id }}" />
                                                        @endif
                                                    </div>
                                                </div>
                                                <hr class="some-padding">
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="well text-center">
                                <button type="submit" class="btn btn-primary">@lang('common.save') @lang('user.forum-privacy')</button>
                            </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="follower">
                        <form role="form" method="POST" action="{{ route('privacy_follower', ['slug' => $user->slug, 'id' => $user->id]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="well">
                                <h3>@lang('user.follower-privacy'):</h3>
                                <div class="help-block">@lang('user.follower-help').</div>
                                <hr>
                                <div class="form-group">
                                    <div class="button-holder">
                                        <div class="button-left">
                                            @lang('user.follower-privacy-list').
                                        </div>
                                        <div class="button-right">
                                            @if(!$user->privacy || ($user->privacy && $user->privacy->show_follower == 1))
                                                <input type="checkbox" name="show_follower" value="1" CHECKED />
                                            @else
                                                <input type="checkbox" name="show_follower" value="1" />
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="some-padding">
                                    <h3>@lang('user.visible-to-follower'):</h3>
                                    <div class="help-block">@lang('user.visible-to-follower-help').</div>
                                    <hr>
                                    <div class="form-group">
                                        @foreach($groups as $group)
                                            @if($group->is_modo || $group->is_admin)
                                            @else
                                                <div class="button-holder">
                                                    <div class="button-left">
                                                        {{ $group->name }}
                                                    </div>
                                                    <div class="button-right">
                                                        @if(!$user->privacy || !$user->privacy->json_follower_groups || $group->isAllowed($user->privacy->json_follower_groups,$group->id))
                                                            <input type="checkbox" name="approved[]" value="{{ $group->id }}" CHECKED />
                                                        @else
                                                            <input type="checkbox" name="approved[]" value="{{ $group->id }}" />
                                                        @endif
                                                    </div>
                                                </div>
                                                <hr class="some-padding">
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="well text-center">
                                <button type="submit" class="btn btn-primary">@lang('common.save') @lang('user.follower-privacy')</button>
                            </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="achievement">
                        <form role="form" method="POST" action="{{ route('privacy_achievement', ['slug' => $user->slug, 'id' => $user->id]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="well">
                                <h3>@lang('user.achievement-privacy'):</h3>
                                <div class="help-block">@lang('user.achievement-help').</div>
                                <hr>
                                <div class="form-group">
                                    <div class="button-holder">
                                        <div class="button-left">
                                            @lang('user.achievement-privacy-list').
                                        </div>
                                        <div class="button-right">
                                            @if(!$user->privacy || ($user->privacy && $user->privacy->show_achievement == 1))
                                                <input type="checkbox" name="show_achievement" value="1" CHECKED />
                                            @else
                                                <input type="checkbox" name="show_achievement" value="1" />
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="some-padding">
                                    <h3>@lang('user.visible-to-achievement'):</h3>
                                    <div class="help-block">@lang('user.visible-to-achievement-help').</div>
                                    <hr>
                                    <div class="form-group">
                                        @foreach($groups as $group)
                                            @if($group->is_modo || $group->is_admin)
                                            @else
                                                <div class="button-holder">
                                                    <div class="button-left">
                                                        {{ $group->name }}
                                                    </div>
                                                    <div class="button-right">
                                                        @if(!$user->privacy || !$user->privacy->json_achievement_groups || $group->isAllowed($user->privacy->json_achievement_groups,$group->id))
                                                            <input type="checkbox" name="approved[]" value="{{ $group->id }}" CHECKED />
                                                        @else
                                                            <input type="checkbox" name="approved[]" value="{{ $group->id }}" />
                                                        @endif
                                                    </div>
                                                </div>
                                                <hr class="some-padding">
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="well text-center">
                                <button type="submit" class="btn btn-primary">@lang('common.save') @lang('user.achievement-privacy')</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascripts')
    <script>
        $(window).on("load", function() { loadTab(); });
        function loadTab() {
            if(window.location.hash && window.location.hash == "#visible") {
                $('#basetabs a[href="#visible"]').tab('show');
            }
            if(window.location.hash && window.location.hash == "#torrent") {
                $('#basetabs a[href="#torrent"]').tab('show');
            }
            if(window.location.hash && window.location.hash == "#forum") {
                $('#basetabs a[href="#forum"]').tab('show');
            }
            if(window.location.hash && window.location.hash == "#profile") {
                $('#basetabs a[href="#profile"]').tab('show');
            }
            if(window.location.hash && window.location.hash == "#follower") {
                $('#basetabs a[href="#follower"]').tab('show');
            }
            if(window.location.hash && window.location.hash == "#achievement") {
                $('#basetabs a[href="#achievement"]').tab('show');
            }
            if(window.location.hash && window.location.hash == "#request") {
                $('#basetabs a[href="#request"]').tab('show');
            }
            if(window.location.hash && window.location.hash == "#other") {
                $('#basetabs a[href="#other"]').tab('show');
            }
        }
    </script>
@endsection