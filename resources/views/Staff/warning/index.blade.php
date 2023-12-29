@extends('layout.default')

@section('title')
    <title>Warnings Log - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Warnings Log - {{ __('staff.staff-dashboard') }}" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('staff.warnings-log') }}
    </li>
@endsection

@section('page', 'page__warning-log--index')

@section('main')
    @livewire('warning-log-search')
@endsection
