@extends('layout.default')

@section('title')
    <title>@lang('mediahub.companies') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('mediahub.companies')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('mediahub.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('mediahub.title')</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('mediahub.companies.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('mediahub.companies')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="header gradient silver">
                <div class="inner_content">
                    <div class="page-title">
                        <h1 style="margin: 0;">@lang('mediahub.companies')</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box container">
        @livewire('company-search')
    </div>
@endsection
