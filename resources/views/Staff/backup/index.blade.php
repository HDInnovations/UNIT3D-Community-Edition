@extends('layout.default')

@section('title')
    <title>{{ __('backup.backup') }} {{ __('backup.manager') }} - {{ __('staff.staff-dashboard') }}
        - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('backup.backup') }} {{ __('backup.manager') }} - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.backups.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('backup.backup') }}
                {{ __('backup.manager') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div>
        @livewire('backup-panel')
    </div>
@endsection
