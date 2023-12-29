@extends('layout.default')

@section('title')
    <title>Audits Log - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Audits Log - {{ __('staff.staff-dashboard') }}" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('staff.audit-log') }}
    </li>
@endsection

@section('page', 'page__audit-log--index')

@section('main')
    @livewire('audit-log-search')
@endsection
