@extends('layout.default')

@section('title')
    <title>{{ __('auth.exceededTitle') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('auth.exceededTitle') }} - {{ config('other.title') }}">
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{ mix('css/main/twostep.css') }}" crossorigin="anonymous">
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('auth.exceededTitle') }}
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel verification-exceeded-panel">
                    <div class="panel-heading text-center">
                        <i class="glyphicon glyphicon-lock locked-icon text-danger" aria-hidden="true"></i>
                        <h3>
                            {{ __('auth.exceededTitle') }}
                        </h3>
                    </div>
                    <div class="panel-body">
                        <h4 class="text-center text-danger">
                            <em>
                                {{ __('auth.lockedUntil') }}
                            </em>
                            <br/>
                            <strong>
                                {{ $timeUntilUnlock }}
                            </strong>
                            <br/>
                            <small>
                                {{ __('auth.tryAgainIn') }} {{ $timeCountdownUnlock }} &hellip;
                            </small>
                        </h4>
                        <p class="text-center">
                            <a class="btn btn-info" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               tabindex="6">
                                <i class="glyphicon glyphicon-home" aria-hidden="true"></i> {{ __('auth.returnButton') }}
                            </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
