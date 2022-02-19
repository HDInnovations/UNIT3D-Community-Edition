@extends('layout.default')

@section('title')
    <title>{{ __('mediahub.companies') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('mediahub.companies') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('mediahub.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('mediahub.title') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('mediahub.companies.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('mediahub.companies') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="box container">
        @livewire('company-search')
    </div>
@endsection
