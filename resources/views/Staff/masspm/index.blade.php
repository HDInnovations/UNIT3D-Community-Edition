@extends('layout.default')

@section('title')
    <title>{{ __('staff.mass-pm') }} - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('staff.mass-pm') }} - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('staff.mass-pm') }}
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>{{ __('staff.mass-pm') }}</h2>
        <form action="{{ route('staff.mass-pm.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="subject">{{ __('pm.subject') }}</label>
                <label>
                    <input type="text" class="form-control" name="subject">
                </label>
            </div>

            <div class="form-group">
                <label for="message">{{ __('pm.message') }}</label>
                <textarea id="editor" name="message" cols="30" rows="10" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-default">{{ __('pm.send') }}</button>
        </form>
    </div>
@endsection
