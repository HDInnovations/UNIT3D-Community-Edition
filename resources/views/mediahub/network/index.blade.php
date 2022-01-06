@extends('layout.default')

@section('title')
    <title>@lang('mediahub.networks') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('mediahub.networks')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('mediahub.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('mediahub.title')</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('mediahub.networks.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('mediahub.networks')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="box container">
        @livewire('network-search')
    </div>
@endsection
