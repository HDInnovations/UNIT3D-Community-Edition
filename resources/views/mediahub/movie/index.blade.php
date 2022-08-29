@extends('layout.default')

@section('title')
    <title>{{ __('mediahub.movies') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Movies">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('mediahub.index') }}" class="breadcrumb__link">
            {{ __('mediahub.title') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('mediahub.movies') }}
    </li>
@endsection

@section('content')
    <div class="box container">
        @livewire('movie-search')
    </div>
@endsection
