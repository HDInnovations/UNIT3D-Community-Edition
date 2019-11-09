@extends('layout.default')

@section('title')
    <title>{{ $user->username }} - Security - @lang('common.members') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('users.show', ['username' => $user->username]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user_security', ['username' => $user->username]) }}" itemprop="url"
            class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }} @lang('user.security')
                @lang('user.settings')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block">
            @include('user.buttons.settings')
            <div class="header gradient red">
                <div class="inner_content">
                    <h1>{{ $user->username }} @lang('user.security-settings')</h1>
                </div>
            </div>
            <div class="container-fluid p-0 some-padding">
                <ul class="nav nav-tabs" role="tablist" id="basetabs">
                    <li class="active"><a href="#password" data-toggle="tab">Password</a></li>
                    <li><a href="#email" data-toggle="tab">Email</a></li>
                    <li><a href="#pid" data-toggle="tab">Pass Key (PID)</a></li>
                    <li><a href="#rid" data-toggle="tab">RSS Key (RID)</a></li>
                    @if (config('auth.TwoStepEnabled') == true)
                        <li><a href="#twostep" data-toggle="tab">Two Step Auth</a></li>
                    @endif
                </ul>
                <div class="tab-content">
                    <br>
                    <div role="tabpanel" class="tab-pane active" id="password">
                        <form role="form" method="POST"
                            action="{{ route('change_password', ['username' => $user->username]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="well">
                                <h3>@lang('user.change-password').</h3>
                                <div class="help-block">@lang('user.change-password-help').</div>
                                <hr>
                                <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <label>
                                        <input type="password" name="current_password" class="form-control"
                                            placeholder="Current Password">
                                    </label>
                                    <label for="new_password">New Password</label>
                                    <label>
                                        <input type="password" name="new_password" class="form-control"
                                            placeholder="New Password">
                                    </label>
                                    <label for="new_password">Repeat Password</label>
                                    <label>
                                        <input type="password" name="new_password_confirmation" class="form-control"
                                            placeholder="New Password, again">
                                    </label>
                                </div>
                            </div>
                            <div class="well text-center">
                                <button type="submit" class="btn btn-primary">Change Password</button>
                            </div>
                        </form>
                    </div>
    
                    <div role="tabpanel" class="tab-pane" id="email">
                        <form role="form" method="POST"
                            action="{{ route('change_email', ['username' => $user->username]) }}">
                            @csrf
                            <div class="well">
                                <h3>@lang('user.change-email').</h3>
                                <div class="help-block">@lang('user.change-email-help').</div>
                                <hr>
                                <label for="current_email">Current Email</label>
                                <p class="text-primary">{{ $user->email }}</p>
                                <label for="email">New Email</label>
                                <label>
                                    <input class="form-control" placeholder="New Email" name="email" type="email">
                                </label>
                            </div>
                            <div class="well text-center">
                                <button type="submit" class="btn btn-primary">Change Email</button>
                            </div>
                        </form>
                    </div>
    
                    <div role="tabpanel" class="tab-pane" id="pid">
                        <form role="form" method="POST" action="{{ route('change_pid', ['username' => $user->username]) }}">
                            @csrf
                            <div class="well">
                                <h3>@lang('user.reset-passkey').</h3>
                                <div class="help-block">@lang('user.reset-passkey-help').</div>
                                </h3>
                                <hr>
    
                                <div class="form-group">
                                    <label for="current_pid">Current PID</label>
                                    <p class="form-control-static text-monospace current_pid">{{ $user->passkey }}</p>
                                </div>
                            </div>
                            <div class="well text-center">
                                <button type="submit" class="btn btn-primary">Reset PID</button>
                            </div>
                        </form>
                    </div>
    
                    <div role="tabpanel" class="tab-pane" id="rid">
                        <form role="form" method="POST" action="{{ route('change_rid', ['username' => $user->username]) }}">
                            @csrf
                            <div class="well">
                                <h3>@lang('user.reset-rss').</h3>
                                <div class="help-block">@lang('user.reset-rss-help').</div>
                                </h3>
                                <hr>
    
                                <div class="form-group">
                                    <label for="current_rid">Current RID</label>
                                    <p class="form-control-static text-monospace current_pid">{{ $user->rsskey }}</p>
                                </div>
                            </div>
                            <div class="well text-center">
                                <button type="submit" class="btn btn-primary">Reset RID</button>
                            </div>
                        </form>
                    </div>
    
    
                    @if (config('auth.TwoStepEnabled') == true)
                        <div role="tabpanel" class="tab-pane" id="twostep">
                            <form role="form" method="POST"
                                action="{{ route('change_twostep', ['username' => $user->username]) }}">
                                @csrf
                                <div class="well">
                                    <h2 class="text-bold">Two Step Authentication</h2>
                                    <hr>
                                    <label for="twostep" class="control-label">Use Two Step Auth?</label>
                                    <div class="radio-inline">
                                <label><input type="radio" name="twostep" @if ($user->twostep == 1) checked @endif
                                            value="1">@lang('common.yes')</label>
                                    </div>
                                    <div class="radio-inline">
                                        <label><input type="radio" name="twostep" @if ($user->twostep == 0) checked
                                            @endif value="0">@lang('common.no')</label>
                                    </div>
                                    <br>
                                </div>
                                <div class="well text-center">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
    
            </div>
        </div>
    </div>
    </div>
@endsection
@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
        $(window).on("load", function() {
            loadTab();
        });
    
        function loadTab() {
            if (window.location.hash && window.location.hash == "#password") {
                $('#basetabs a[href="#password"]').tab('show');
            }
            if (window.location.hash && window.location.hash == "#email") {
                $('#basetabs a[href="#email"]').tab('show');
            }
            if (window.location.hash && window.location.hash == "#pid") {
                $('#basetabs a[href="#pid"]').tab('show');
            }
            if (window.location.hash && window.location.hash == "#rid") {
                $('#basetabs a[href="#rid"]').tab('show');
            }
        }
    
    </script>
@endsection
