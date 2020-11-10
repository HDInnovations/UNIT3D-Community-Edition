@extends('layout.default')

@section('title')
    <title>{{ $user->username }} - Security - @lang('auth.title')</title>
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
    <li>
        <a href="/totp/2fa" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('auth.title')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel verification-form-panel">
                    <div class="panel-heading text-center" id="verification_status_title">
                        <h1 class="title">
                            @lang('auth.title')
                        </h1>
                    </div>
                    <div class="panel-body">
                        <p>Two step verification (aka <b>2FA</b>) strengthens access security by requiring two methods (also referred to as factors) to verify your identity. Two step verification protects against phishing, social engineering and password brute force attacks and secures your logins from attackers exploiting weak or stolen credentials.</p>
                        <br/>
                        <p>To Enable 2-Step Verification on your Account, you need to do the following steps</p>
                        <strong>
                            <ol>
                            <li>Download a TOTP App, like "Google Authentificator" for
                                <a target="_blank" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en&gl=US">Android</a> or
                                <a target="_blank" href="https://apps.apple.com/us/app/google-authenticator/id388497605">iOS</a></li>
                            <li>Generate a secret Key (Button below)</li>
                            <li>Scan the QR Code with your App</li>
                            <li>Verify the OTP from your Authentificator App</li>
                            </ol>
                        </strong>
                        <hr>

                        <p class="text-center">
                            <em>
                                @lang('auth.title')
                            </em>
                        </p>
                        <br>

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert" id="alert1" style="color:white;background-color:#3498db;text-align:center;">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($data['user']->passwordSecurity == null)
                            <form id="verification_form" class="form-horizontal" method="POST" action="{{ route('generate2faSecret') }}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Generate Secret Key to Enable 2FA
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @elseif(!$data['user']->passwordSecurity->google2fa_enable)
                            <strong>1. Scan this barcode with your Authenticator App:</strong><br><br>
                            <p class="text-center">
                                <img src="{{$data['google2fa_url'] }}" alt="" style="margin:auto;align:center;"><br>
                                Key: <code>{{ $data['user']->passwordSecurity->google2fa_secret }}</code>
                            </p>
                            <br>

                            <strong>2. Enter the 6-digit pin code shown in your App:</strong><br/><br/>
                            <form id="verification_form" class="form-horizontal" method="POST" action="{{ route('enable2fa') }}">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('verify-code') ? ' has-error' : '' }}">
                                    <label for="verify-code" class="col-md-4 control-label">Authenticator Code</label>

                                    <div class="col-md-6">
                                        <input id="verify-code" type="number" class="form-control" name="verify-code" required>
                                        @if ($errors->has('verify-code'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('verify-code') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Enable @lang('auth.title')
                                        </button>
                                    </div>
                                </div>
                            </form>

                        @elseif($data['user']->passwordSecurity->google2fa_enable)
                            <div class="alert" id="alert1" style="color:white;background-color:#00BC63;text-align:center;">
                                2FA is Currently <strong>Enabled</strong> for your account.
                            </div>
                            <p>If you are looking to disable Two Factor Authentication. Please confirm your password and Click Disable 2FA Button.</p>
                            <form class="form-horizontal" method="POST" action="{{ route('disable2fa') }}">
                                <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                                    <label for="change-password" class="col-md-4 control-label">Current Password</label>
                                    <div class="col-md-6">
                                        <input id="current-password" type="password" class="form-control" name="current-password" required>
                                        @if ($errors->has('current-password'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('current-password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-primary ">Disable 2FA</button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
