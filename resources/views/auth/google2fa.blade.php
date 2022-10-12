<!DOCTYPE html>
<html lang="{{ auth()->user()->locale }}">

<head>
    @include('partials.head')
</head>

<body style="top: 25%;">
    <div class="container">
        <div class="row">
            <div class="box container">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong><i class="{{ config('other.font-awesome') }} fa-lock"></i> {{ __('auth.two-factor-authentication') }}</strong>
                    </div>
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
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success" style="border-color: #1e88e5; color: white;">
                                {{ session('success') }}
                            </div>
                        @endif
                        <br>
                        <strong>{{ __('auth.code-from-auth-app') }}</strong><br/><br/>
                        <br>
                        <form class="form-horizontal" action="{{ route('2faVerify') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('one_time_password-code') ? ' has-error' : '' }}">
                                <label for="one_time_password" class="col-md-4 control-label" style="width: 47.5%;">Code: </label>
                                <div class="col-md-6">
                                    <input name="one_time_password" class="form-control" type="password" style="width: 100px;"/>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <div style="text-align: center;">
                                    <button class="btn btn-primary" type="submit">{{ __('auth.authenticate') }}</button>
                                </div>
                            </div>
                        </form>

                        <details>
                            <summary style="cursor: pointer;">
                                Recovery <i class="{{ config('other.font-awesome') }} fa-angle-down"></i>
                            </summary>
                            <br><br>
                            <form class="form-horizontal" action="{{ route('recovery2fa') }}" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group{{ $errors->has('one_time_recovery-code') ? ' has-error' : '' }}">
                                    <label for="one_time_recovery" class="col-md-4 control-label" style="width: 47.5%;">Recovery Code: </label>
                                    <div class="col-md-6">
                                        <input name="one_time_recovery" class="form-control" type="text" style="width: 230px;" placeholder="XXXXXXXXXX-XXXXXXXXXX"/>
                                    </div>
                                </div>

                                <div class="captcha" style="margin-bottom: 80px;">
                                    <span class="col-md-4 control-label" style="width: 47.5%; height: 36px; padding-top: 0px;">{!! captcha_img('inverse') !!}</span>
                                    <div class="col-md-6">
                                        <input id="captcha" type="text" class="form-control" name="captcha" placeholder="Enter captcha" style="width: 230px;" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div style="text-align: center;">
                                        <button class="btn btn-primary" type="submit">{{ __('auth.disable-2fa') }}</button>
                                    </div>
                                </div>
                            </form>
                        </details>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>