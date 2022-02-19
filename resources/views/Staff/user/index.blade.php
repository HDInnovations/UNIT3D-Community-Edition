@extends('layout.default')

@section('title')
    <title>{{ __('common.user') }} {{ __('common.search') }} - {{ __('staff.staff-dashboard') }}
        - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="User Search - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user_search') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title"
                  class="l-breadcrumb-item-link-title">{{ __('common.user') }} {{ __('common.search') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <style>
        td {
            vertical-align: middle !important;
        }
    </style>
    <div class="box container">
        @livewire('user-search')
    </div>
@endsection
