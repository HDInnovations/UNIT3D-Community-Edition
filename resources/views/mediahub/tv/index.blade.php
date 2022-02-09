@extends('layout.default')

@section('title')
    <title>TV Shows - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="TV Shows">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('mediahub.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">MediaHub</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('mediahub.shows.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">TV Shows</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="box container">
        @livewire('tv-search')
    </div>
@endsection