@extends('layout.default')

@section('title')
    <title>@lang('torrent.torrents') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('torrent.torrents')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('torrents') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.torrents')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('grouping_categories') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.grouping-categories')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="header gradient blue">
            <div class="inner_content">
                <h1>@lang('torrent.grouping-categories-desc')</h1>
            </div>
        </div>
        <div class="blocks">
            @foreach ($categories as $category)
                <a href="{{ route('grouping', ['category_id' => $category->id]) }}"
                   title="{{ $category->name }}">
                    <div class="blu media_blocks">
                        <h2><i class="{{ $category->icon }}"></i> {{ $category->name }}</h2>
                        <span></span>
                        <h2>{{ $category->torrents_count }} @lang('torrent.torrents')</h2>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
