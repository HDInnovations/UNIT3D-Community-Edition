@extends('layout.default')

@section('title')
    <title>{{ __('mediahub.companies') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('mediahub.companies') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('mediahub.index') }}" class="breadcrumb__link">
            {{ __('mediahub.title') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('mediahub.companies') }}
    </li>
@endsection

@section('content')
    <div class="box container">
        @livewire('company-search')
    </div>
@endsection
