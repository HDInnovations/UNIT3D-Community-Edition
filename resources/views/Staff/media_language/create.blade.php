@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.media_languages.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                {{ __('common.media-languages') }}
            </span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.media_languages.create') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                {{ __('common.add') }} {{ __('common.media-language') }}
            </span>
        </a>
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
