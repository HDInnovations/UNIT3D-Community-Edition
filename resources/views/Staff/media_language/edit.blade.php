@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.media_languages.index') }}" class="breadcrumb__link">
            {{ __('common.media_languages') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.edit') }}
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>
            {{ __('common.edit') }} {{ __('common.media-language') }} {{ __('staff.media-languages-desc') }}</h2>
        <form role="form" method="POST"
              action="{{ route('staff.media_languages.update', ['id' => $media_language->id]) }}">
            @csrf

            <div class="form-group">
                <label for="name">{{ __('common.name') }}</label>
                <label>
                    <input type="text" class="form-control" name="name" value="{{ $media_language->name }}">
                </label>
            </div>

            <div class="form-group">
                <label for="name">{{ __('common.code') }}</label>
                <label>
                    <input type="text" class="form-control" name="code" value="{{ $media_language->code }}">
                </label>
            </div>

            <button type="submit" class="btn btn-default">
                {{ __('common.submit') }}
            </button>
        </form>
    </div>
@endsection
