@extends('layout.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="box container">
                <div class="panel panel-default">
                    <div class="panel-heading"><strong><i class="{{ config('other.font-awesome') }} fa-lock"></i> {{ __('auth.two-factor-authentication') }}</strong></div>
                        <div class="panel-body">
                            <div class="well">
                                <p>
                                    {{ __('auth.two-factor-text1') }}
                                </p>
                                <br>
                                <p>
                                    {{ __('auth.two-factor-text2') }}
                                </p>
                            </div>
                            <br/>
                            <p>
                                {{ __('auth.two-factor-howto-title') }}
                            </p>
                            <br/>
                            <strong>
                            <ol>
                                <li>{{ __('auth.two-factor-howto-step1') }}</li>
                                <li>{{ __('auth.two-factor-howto-step2') }}</li>
                                <li>{{ __('auth.two-factor-howto-step3') }}</li>
                            </ol>
                            </strong>
                            <br/>
                            
                            @if(! $user->passwordSecurity()->exists())
                               <form class="form-horizontal" method="POST" action="{{ route('generate2faSecret') }}">
                                   {{ csrf_field() }}
                                    <div class="form-group">
                                        <div style="text-align: center;">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('auth.generate-secret-key') }}
                                            </button>
                                        </div>
                                    </div>
                               </form>
                            @elseif(! $user->passwordSecurity->google2fa_enable)
                                <div class="alert alert-success" style="border-color: #1e88e5 !important; color: #fff;">
                                    2FA is currenty <strong style="color: #f00;">disabled</strong>.
                                </div>
                                <br/>
                                <strong>1. {{ __('auth.scan-qr-code') }}</strong>
                                <br/>
                                <br/>
                                <br/>
                                <div style="text-align: center;">
                                    {!! $google2fa_url !!}
                                    <br>
                                    <code style="background-color: transparent; white-space: unset; padding: unset;">
                                        Secret Key: {{ $google2fa_secret }}
                                        <br><br>
                                        <p style="color: red; font-size: 16px;">{{ __('auth.recovery-keys') }}<p>
                                        <p style="color: red;">{{ __('auth.recovery-keys-important') }}</p>
                                        <div class="well" style="margin-right: 41%; margin-left: 41%; margin-bottom: unset;">
                                            @php $counter = 1; @endphp
                                            @foreach($recovery as $re)
                                                {{ sprintf('%02d', $counter) }}. {{ \Crypt::decrypt($re) }}
                                                <br>
                                                @php $counter++ @endphp
                                            @endforeach
                                        </div>
                                    </code>
                                </div>
                                <br/>
                                <br/>
                                <strong>2. {{ __('auth.code-from-auth-app') }}</strong>
                                <br/>
                                <br/>
                                <br/>
                                <form class="form-horizontal" method="POST" action="{{ route('enable2fa') }}">
                                    {{ csrf_field() }}
                                    <div class="form-group{{ $errors->has('verify-code') ? ' has-error' : '' }}" >
                                        <div class="text-center">
                                            <label for="verify-code" class="col-md-4 control-label" style="width: 47.5%;">Code: </label>
                                            <div class="col-md-6">
                                                <input id="verify-code" type="password" class="form-control" name="verify-code" style="width: 100px;" required>

                                                @if ($errors->has('verify-code'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('verify-code') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <div style="text-align: center;">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('auth.enable-2fa') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @elseif($user->passwordSecurity->google2fa_enable)
                                <div class="alert alert-success" style="border-color: #1e88e5 !important; color: #fff;">
                                    2FA is currently <strong style="color: lime;">enabled</strong>.
                                </div>
                                <br>
                                <p>
                                    {{ __('auth.disable-2fa-title') }}
                                </p>
                                <br>
                                <form class="form-horizontal" method="POST" action="{{ route('disable2fa') }}">
                                    <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                                        <label for="change-password" class="col-md-4 control-label" style="width: 40%;">Current Password: </label>
                                        <div class="col-md-6">
                                            <input id="current-password" type="password" class="form-control" name="current-password" style="width: 50%;" required>
                                            @if ($errors->has('current-password'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('current-password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <br>
                                    <div style="text-align: center;">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-primary ">{{ __('auth.disable-2fa') }}</button>
                                    </div>
                                </form>

                                <br><br><br>
                                
                                <div class="alert alert-success" style="border-color: #1e88e5 !important; color: #fff;">
                                    <a style="color: red;">Recovery:</a> {{ count($recovery) }} Recovery Codes are active.
                                </div>
                                <br>
                                <p>{{ __('auth.genreate-new-codes-title') }}</p>
                                <br>
                                <form class="form-horizontal" method="POST" action="{{ route('generateNewRecoveryCodes') }}">
                                    <div class="form-group{{ $errors->has('verify-code') ? ' has-error' : '' }}">
                                        <label for="change-password" class="col-md-4 control-label" style="width: 40%;">2FA Token: </label>
                                        <div class="col-md-6">
                                            <input id="verify-code" type="password" class="form-control" name="verify-code" style="width: 50%;" required>
                                            @if ($errors->has('verify-code'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('verify-code') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($tempRecovery === true)
                                    <br>
                                    <code style="background-color: transparent; white-space: unset; padding: unset;">
                                        <div class="well" style="margin-right: 41%; margin-left: 41%; margin-bottom: unset;">
                                            @php $counter = 1; @endphp
                                            @foreach($recovery as $re)
                                                {{ sprintf('%02d', $counter) }}. {{ \Crypt::decrypt($re) }}
                                                <br>
                                                @php $counter++ @endphp
                                            @endforeach
                                        </div>
                                    </code>
                                    @endif
                                    <br>
                                    <div style="text-align: center;">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-primary ">{{ __('auth.generate-new-codes') }}</button>
                                    </div>
                                </form>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
