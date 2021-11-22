@extends('layout.default')

@section('title')
    <title>@lang('mediahub.collections') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('mediahub.collections')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('mediahub.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('mediahub.title')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('mediahub.collections.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('mediahub.collections')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="header gradient silver">
                <div class="inner_content">
                    <div class="page-title">
                        <h1 style="margin: 0;">@lang('mediahub.collections')</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box container">
        @livewire('collection-search')
    </div>
@endsection
