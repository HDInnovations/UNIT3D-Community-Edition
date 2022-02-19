@extends('layout.default')

@section('title')
    <title>{{ __('mediahub.movies') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Movies">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('mediahub.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('mediahub.title') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('mediahub.movies.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('mediahub.movies') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="box container">
        @livewire('movie-search')
    </div>
@endsection
