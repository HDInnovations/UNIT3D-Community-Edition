@extends('layout.default')

@section('title')
    <title>{{ __('common.user') }} Notes - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="User Notes - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('staff.user-notes') }}
    </li>
@endsection

@section('page', 'page__notes-log--index')

@section('main')
    @livewire('note-search')
@endsection
