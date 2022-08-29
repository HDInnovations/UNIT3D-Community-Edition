@extends('layout.default')

@section('title')
    <title>TV Shows - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="TV Shows">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('mediahub.index') }}" class="breadcrumb__link">
            {{ __('mediahub.title') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        TV Show
    </li>
@endsection

@section('content')
    <div class="box container">
        @livewire('tv-search')
    </div>
@endsection