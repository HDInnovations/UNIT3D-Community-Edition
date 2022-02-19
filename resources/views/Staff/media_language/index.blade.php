@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.media_languages.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                {{ __('common.media-languages') }}
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>{{ __('common.media-languages') }}</h2>
        <p>{{ __('staff.media-languages-desc') }}</p>

        <a href="{{ route('staff.media_languages.create') }}" class="btn btn-primary">
            {{ __('common.add') }}
            {{ trans_choice('common.a-an-art',false) }}
            {{ __('common.media-language') }}
        </a>

        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>{{ __('common.name') }}</th>
                    <th>{{ __('common.code') }}</th>
                    <th>{{ __('common.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($media_languages as $media_language)
                    <tr>
                        <td>
                            <a href="{{ route('staff.media_languages.edit', ['id' => $media_language->id]) }}">
                                {{ $media_language->name }}
                            </a>
                        </td>
                        <td>
                            <span>{{ $media_language->code }}</span>
                        </td>
                        <td>
                            <a href="{{ route('staff.media_languages.edit', ['id' => $media_language->id]) }}"
                               class="btn btn-warning">
                                {{ __('common.edit') }}
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
