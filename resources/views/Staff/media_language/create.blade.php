@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.media_languages.index') }}" class="breadcrumb__link">
            {{ __('common.media-languages') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.new-adj') }}
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>
            {{ __('common.add') }} {{ __('common.media-language') }} {{ __('staff.media-languages-desc') }}
        </h2>
        <form role="form" method="POST" action="{{ route('staff.media_languages.store') }}">
            @csrf

            <div class="form-group">
                <label for="name">{{ __('common.name') }}</label>
                <label>
                    <input type="text" class="form-control" name="name">
                </label>
            </div>

            <div class="form-group">
                <label for="name">{{ __('common.code') }}</label>
                <label>
                    <input type="text" class="form-control" name="code">
                </label>
            </div>

            <button type="submit" class="btn btn-default">{{ __('common.add') }}</button>
        </form>
    </div>
@endsection
