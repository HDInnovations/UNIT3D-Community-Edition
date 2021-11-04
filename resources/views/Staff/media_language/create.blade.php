@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                @lang('staff.staff-dashboard')
            </span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.media_languages.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                @lang('common.media-languages')
            </span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.media_languages.create') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                @lang('common.add') @lang('common.media-language')
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>
            @lang('common.add') @lang('common.media-language') @lang('staff.media-languages-desc')
        </h2>
        <form role="form" method="POST" action="{{ route('staff.media_languages.store') }}">
            @csrf

            <div class="form-group">
                <label for="name">@lang('common.name')</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>

            <div class="form-group">
                <label for="code">@lang('common.code')</label>
                <input type="text" class="form-control" id="code" name="code">
            </div>

            <button type="submit" class="btn btn-default">{{ trans('common.add') }}</button>
        </form>
    </div>
@endsection
