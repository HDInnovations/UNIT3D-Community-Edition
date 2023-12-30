@extends('layout.default')

@section('title')
    <title>
        {{ __('staff.gifts-log') }} - {{ __('staff.staff-dashboard') }} -
        {{ config('other.title') }}
    </title>
@endsection

@section('meta')
    <meta name="description" content="Gifts Log - {{ __('staff.staff-dashboard') }}" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('staff.gifts-log') }}
    </li>
@endsection

@section('page', 'page__gift-log--index')

@section('main')
    @livewire('gift-log-search')
@endsection
