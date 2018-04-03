@extends('layout.default')

@section('title')
	<title>{{ trans('auth.title') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
	<meta name="description" content="{{ trans('auth.title') }} - {{ config('other.title') }}">
@endsection

@section('stylesheets')
<link rel="stylesheet" href="{{ url('css/main/twostep.css') }}">
@endsection

@section('breadcrumb')
<li>
    <a href="{{ route('verificationNeeded') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('auth.title') }}</span>
    </a>
</li>
@endsection

@php
switch ($remainingAttempts) {
    case 0:
    case 1:
        $remainingAttemptsClass = 'danger';
        break;

    case 2:
        $remainingAttemptsClass = 'warning';
        break;

    case 3:
        $remainingAttemptsClass = 'info';
        break;

    default:
        $remainingAttemptsClass = 'success';
        break;
}
@endphp

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel verification-form-panel">
                <div class="panel-heading text-center" id="verification_status_title">
                    <h3>
                        {{ trans('auth.title') }}
                    </h3>
                    <p class="text-center">
                        <em>
                            {{ trans('auth.subtitle') }}
                        </em>
                    </p>
                </div>
                <div class="panel-body">
                    <form id="verification_form" class="form-horizontal" method="POST" >
                        {{ csrf_field() }}
                        <div class="form-group margin-bottom-1 code-inputs">
                            <div class="col-xs-3">
                                <div class="{{ $errors->has('v_input_1') ? ' has-error' : '' }}">
                                    <label for="v_input_1" class="sr-only control-label">
                                        {{ trans('auth.inputAlt1') }}
                                    </label>
                                    <input type="text"  id="v_input_1" class="form-control text-center required" required name="v_input_1" value="" autofocus maxlength="1" minlength="1" tabindex="1" placeholder="•">
                                    @if ($errors->has('v_input_1'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('v_input_1') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="{{ $errors->has('v_input_2') ? ' has-error' : '' }}">
                                    <label for="v_input_2" class="sr-only control-label">
                                        {{ trans('auth.inputAlt2') }}
                                    </label>
                                    <input type="text"  id="v_input_2" class="form-control text-center required" required name="v_input_2" value="" maxlength="1" minlength="1" tabindex="2" placeholder="•">
                                    @if ($errors->has('v_input_2'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('v_input_2') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="{{ $errors->has('v_input_3') ? ' has-error' : '' }}">
                                    <label for="v_input_3" class="sr-only control-label">
                                        {{ trans('auth.inputAlt3') }}
                                    </label>
                                    <input type="text"  id="v_input_3" class="form-control text-center required" required name="v_input_3" value="" maxlength="1" minlength="1" tabindex="3" placeholder="•">
                                    @if ($errors->has('v_input_3'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('v_input_3') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="{{ $errors->has('v_input_4') ? ' has-error' : '' }}">
                                    <label for="v_input_4" class="sr-only control-label">
                                        {{ trans('auth.inputAlt4') }}
                                    </label>
                                    <input type="text"  id="v_input_4" class="form-control text-center required last-input " required name="v_input_4" value="" maxlength="1" minlength="1" tabindex="4" placeholder="•">
                                    @if ($errors->has('v_input_4'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('v_input_4') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-2 text-center submit-container">
                                    <button type="submit" class="btn btn-lg btn-{{ $remainingAttemptsClass }} btn-block" id="submit_verification" tabindex="5">
                                        <i class="glyphicon glyphicon-check" aria-hidden="true"></i>
                                        {{ trans('auth.verifyButton') }}
                                    </button>
                                </div>
                                <div class="col-xs-12 text-center">
                                    <p class="text-{{ $remainingAttemptsClass }}">
                                        <small>
                                            <span id="remaining_attempts">{{ $remainingAttempts }}</span> {{ trans_choice('auth.attemptsRemaining', $remainingAttempts) }}
                                        </small>
                                    </p>
                                </div>
                                </form>
                                <div class="col-xs-12 text-center">
                                    <a class="btn btn-link" id="resend_code_trigger" href="#" tabindex="6">
                                        {{ trans('auth.missingCode') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@php
    $minutesToExpire = config('auth.TwoStepExceededCountdownMinutes');
    $hoursToExpire = $minutesToExpire / 60;
@endphp
@endsection

@section('javascripts')
<script type="text/javascript" src="{{ url('js/twostep.js') }}"></script>
@endsection
