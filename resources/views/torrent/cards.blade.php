@extends('layout.default')

@section('title')
    <title>{{ __('torrent.torrents') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('torrent.torrents') }} {{ config('other.title') }}">
@endsection

@section('breadcrumb')
    <li class="active">
        <a href="{{ route('cards') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('torrent.torrent') }} Cards</span>
        </a>
    </li>
@endsection

@section('content')
    <div>
        @livewire('torrent-card-search')
    </div>
@endsection
