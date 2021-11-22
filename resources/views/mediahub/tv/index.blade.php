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
    <div class="container">
        <div class="block">
            <div class="header gradient silver">
                <div class="inner_content">
                    <div class="page-title">
                        <h1 style="margin: 0;">TV Shows</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box container">
        @livewire('tv-search')
    </div>
@endsection