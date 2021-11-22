@extends('layout.default')

@section('title')
    <title>@lang('common.subtitles') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('subtitles.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('common.subtitles')</span>
        </a>
    </li>
@endsection

@section('content')
    <div>
        @livewire('subtitle-search')
    </div>
@endsection