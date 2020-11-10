@extends('layout.default')

@section('title')
    <title>@lang('auth.title') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('auth.title') - {{ config('other.title') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="#" itemprop="url" class="l-breadcrumb-item-link">
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
                        <p class="text-center">
                            <em>
                                @lang('auth.subtitle')
                            </em>
                        </p>
                    </div>
                    <div class="panel-body">
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

                        <form id="verification_form" class="form-horizontal" action="{{ route('faVerify') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('one_time_password-code') ? ' has-error' : '' }} margin-bottom-1 code-inputs">
                                <label for="one_time_password" class="col-md-4 control-label">Enter your One Time Password from your Authentificator App:</label>
                                <div class="col-md-6">
                                    <input type="number" name="one_time_password" class="form-control text-center required" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button class="btn btn-primary" type="submit">Authenticate</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
