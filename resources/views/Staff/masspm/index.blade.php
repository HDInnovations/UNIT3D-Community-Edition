@extends('layout.default')

@section('title')
    <title>{{ __('staff.mass-pm') }} - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('staff.mass-pm') }} - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.mass-pm.create') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.mass-pm') }}</span>
        </a>
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
                <textarea id="message" name="message" cols="30" rows="10" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-default">{{ __('pm.send') }}</button>
        </form>
    </div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
      $(document).ready(function () {
        $('#message').wysibb({})
      })

    </script>
@endsection
