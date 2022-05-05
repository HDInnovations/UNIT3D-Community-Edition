@extends('layout.default')

@section('title')
    <title>{{ __('notification.notifications') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('notifications.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('notification.notifications') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div>
        @livewire('notification-search')
    </div>
@endsection