@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
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
        <a href="{{ route('staff.media_languages.edit', ['id' => $media_language->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                @lang('common.edit') @lang('common.media-language')
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>
            @lang('common.edit') @lang('common.media-language') @lang('staff.media-languages-desc')</h2>
        <form role="form" method="POST" action="{{ route('staff.media_languages.update', ['id' => $media_language->id]) }}">
            @csrf
    
            <div class="form-group">
                <label for="name">@lang('common.name')</label>
                <label>
                    <input type="text" class="form-control" name="name" value="{{ $media_language->name }}">
                </label>
            </div>

            <div class="form-group">
                <label for="name">@lang('common.code')</label>
                <label>
                    <input type="text" class="form-control" name="code" value="{{ $media_language->code }}">
                </label>
            </div>
    
            <button type="submit" class="btn btn-default">
                {{ trans('common.submit') }}
            </button>
        </form>
    </div>
@endsection
