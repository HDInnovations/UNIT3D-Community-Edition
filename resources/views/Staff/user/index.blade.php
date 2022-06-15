@extends('layout.default')

@section('title')
    <title>{{ __('common.user') }} {{ __('common.search') }} - {{ __('staff.staff-dashboard') }}
        - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="User Search - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.users') }}
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
