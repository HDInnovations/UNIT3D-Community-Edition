@extends('layout.default')

@section('title')
    <title>{{ __('request.requests') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('requests.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('request.requests') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div>
        @livewire('torrent-request-search')
    </div>
@endsection