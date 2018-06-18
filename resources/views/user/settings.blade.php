@extends('layout.default')

@section('title')
    <title>{{ $user->username }} - {{ trans('common.members') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('profile', ['username' => $user->username, 'id' => $user->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user_settings_form', ['username' => $user->username, 'id' => $user->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Settings</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <h1 class="title"><i class="fa fa-gear"></i> Account Setting</h1>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#welcome" aria-controls="welcome" role="tab"
                                                      data-toggle="tab" aria-expanded="true">Account</a></li>
            <li role="presentation" class=""><a href="#password" aria-controls="password" role="tab" data-toggle="tab"
                                                aria-expanded="false">Password</a></li>
            <li role="presentation" class=""><a href="#email" aria-controls="email" role="tab" data-toggle="tab"
                                                aria-expanded="false">Email</a></li>
            <li role="presentation" class=""><a href="#pid" aria-controls="pid" role="tab" data-toggle="tab"
                                                aria-expanded="false">PID</a></li>
        </ul>

        <div class="tab-content block block-titled">
            <div role="tabpanel" class="tab-pane active" id="welcome">

                {{ Form::open(array('url' => '/{username}.{id}/settings','role' => 'form', 'class' => 'login-frm')) }}
                <br>
                <div class="well">
                <h2 class="text-bold">General Settings</h2>
                <hr>
                <label for="hidden" class="control-label">Language Censor Chat?</label>
                <div class="radio-inline">
                    <label><input type="radio" name="censor" @if($user->censor == 1) checked
                                  @endif value="1">YES</label>
                </div>
                <div class="radio-inline">
                    <label><input type="radio" name="censor" @if($user->censor == 0) checked @endif value="0">NO</label>
                </div>
                <br>
                <br>
                <label for="hidden" class="control-label">Hide Chat?</label>
                <div class="radio-inline">
                    <label><input type="radio" name="chat_hidden" @if($user->chat_hidden == 1) checked @endif value="1">YES</label>
                </div>
                <div class="radio-inline">
                    <label><input type="radio" name="chat_hidden" @if($user->chat_hidden == 0) checked @endif value="0">NO</label>
                </div>
                </div>

                <div class="well">
                <h2 class="text-bold">Style Settings</h2>
                <hr>
                <div class="form-group">
                    <label for="theme" class="control-label">Theme</label>
                    <select class="form-control" id="theme" name="theme">
                        <option @if($user->style == 0) selected @endif value="0">Light Theme</option>
                        <option @if($user->style == 1) selected @endif value="1">Dark Theme</option>
                        <option @if($user->style == 2) selected @endif value="2">Blur Theme</option>
                        <option @if($user->style == 3) selected @endif value="3">Galactic Theme</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="custom_css" class="control-label">External CSS Stylesheet</label>
                    <input type="text" name="custom_css" class="form-control"
                           value="@if($user->custom_css) {{ $user->custom_css }}@endif" placeholder="CSS URL">
                </div>
                <label for="sidenav" class="control-label">Side Navigation</label>
                <div class="radio-inline">
                    <label><input type="radio" name="sidenav" @if($user->nav == 1) checked
                                  @endif value="1">Expanded</label>
                </div>
                <div class="radio-inline">
                    <label><input type="radio" name="sidenav" @if($user->nav == 0) checked
                                  @endif value="0">Compact</label>
                </div>
                </div>

                <div class="well">
                <h2 class="text-bold">Privacy Settings</h2>
                <hr>
                <label for="hidden" class="control-label">Hidden From Online Block?</label>
                <div class="radio-inline">
                    <label><input type="radio" name="onlinehide" @if($user->hidden == 1) checked
                                  @endif value="1">YES</label>
                </div>
                <div class="radio-inline">
                    <label><input type="radio" name="onlinehide" @if($user->hidden == 0) checked
                                  @endif value="0">NO</label>
                </div>
                <br>
                <br>
                <label for="hidden" class="control-label">Hidden In Peers/History Table?</label>
                <div class="radio-inline">
                    <label><input type="radio" name="peer_hidden" @if($user->peer_hidden == 1) checked @endif value="1">YES</label>
                </div>
                <div class="radio-inline">
                    <label><input type="radio" name="peer_hidden" @if($user->peer_hidden == 0) checked @endif value="0">NO</label>
                </div>
                <br>
                <br>
                <label for="hidden" class="control-label">Private Profile?</label>
                <div class="radio-inline">
                    <label><input type="radio" name="private_profile" @if($user->private_profile == 1) checked
                                  @endif value="1">YES</label>
                </div>
                <div class="radio-inline">
                    <label><input type="radio" name="private_profile" @if($user->private_profile == 0) checked
                                  @endif value="0">NO</label>
                </div>
                </div>

                <div class="well">
                <h2 class="text-bold">Torrent Preferences</h2>
                <hr>
                <label for="torrent_layout" class="control-label">Default Torrent Layout?</label>
                <select class="form-control" id="torrent_layout" name="torrent_layout">
                    <option @if($user->torrent_layout == 0) selected @endif value="0">Torrent List</option>
                    <option @if($user->torrent_layout == 1) selected @endif value="1">Torrent Grouping</option>
                    <option @if($user->torrent_layout == 2) selected @endif value="2">Torrent Cards</option>
                </select>
                <br>
                <label for="poster" class="control-label">Show Posters On Torrent List View?</label>
                <div class="radio-inline">
                    <label><input type="radio" name="show_poster" @if($user->show_poster == 1) checked @endif value="1">YES</label>
                </div>
                <div class="radio-inline">
                    <label><input type="radio" name="show_poster" @if($user->show_poster == 0) checked @endif value="0">NO</label>
                </div>
                <br>
                <br>
                <label for="ratings" class="control-label">Ratings Source?</label>
                <div class="radio-inline">
                    <label><input type="radio" name="ratings" @if($user->ratings == 1) checked
                                  @endif value="1">IMDB</label>
                </div>
                <div class="radio-inline">
                    <label><input type="radio" name="ratings" @if($user->ratings == 0) checked
                                  @endif value="0">TMDB</label>
                </div>
                </div>

                <div class="well">
                <h2 class="text-bold">Security Preferences</h2>
                <hr>
                @if(config('auth.TwoStepEnabled') == true)
                    <label for="twostep" class="control-label">Use Two Step Auth?</label>
                    <div class="radio-inline">
                        <label><input type="radio" name="twostep" @if($user->twostep == 1) checked @endif value="1">YES</label>
                    </div>
                    <div class="radio-inline">
                        <label><input type="radio" name="twostep" @if($user->twostep == 0) checked
                                      @endif value="0">NO</label>
                    </div>
                    <br>
                @endif
                </div>
                <div class="form-group">
                    <div class="text-center"><input class="btn btn-primary" type="submit" value="Save"></div>
                </div>
                {{ Form::close() }}
            </div>

            <div role="tabpanel" class="tab-pane" id="password">
                <h3>Change Password. <span
                            class="small">You will have to login again, after you change your password.</span>
                </h3>
                <hr>
                {{ Form::open(array('url' => '/{username}.{id}/settings/change_password','role' => 'form', 'class' => 'login-frm')) }}
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" name="current_password" class="form-control" placeholder="Current Password">
                    <label for="new_password">New Password</label>
                    <input type="password" name="new_password" class="form-control" placeholder="New Password">
                    <label for="new_password">Repeat Password</label>
                    <input type="password" name="new_password_confirmation" class="form-control"
                           placeholder="New Password, again">
                </div>
                <br>
                <button type="submit" class="btn btn-primary">Make The Switch!</button>
                {{ Form::close() }}
            </div>

            <div role="tabpanel" class="tab-pane" id="email">
                <h3>Change Email Address. <span class="small">You will have to re-confirm your account, after you change your email.</span>
                </h3>
                <hr>
                {{ Form::open(array('url' => '/{username}.{id}/settings/change_email','role' => 'form', 'class' => 'login-frm')) }}
                <label for="current_email">Current Email</label>
                <p class="text-primary">{{ $user->email }}</p>
                <label for="current_password">Current Password</label>
                <input type="password" name="current_password" class="form-control" placeholder="Current Password">
                <label for="email">New Email</label>
                <input class="form-control" placeholder="New Email" name="new_email" type="text" id="new_email">
                <br>
                <button type="submit" class="btn btn-primary">Make The Switch!</button>
                {{ Form::close() }}
            </div>

            <div role="tabpanel" class="tab-pane" id="pid">
                <h3>Reset PID.
                    <span class="small">You will have to re-download/re-upload all your active torrents, after resetting the PID.</span>
                </h3>
                <hr>
                {{ Form::open(array('url' => '/{username}.{id}/settings/change_pid','role' => 'form', 'class' => 'login-frm')) }}
                <div class="form-group">
                    <label for="current_pid">Current pid</label>
                    <p class="form-control-static text-monospace current_pid">{{ $user->passkey }}</p>
                </div>
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" value="Reset PID">
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
