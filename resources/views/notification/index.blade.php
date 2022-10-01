@extends('layout.default')

@section('title')
    <title>{{ __('notification.notifications') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('notification.notifications') }}
    </li>
@endsection

@section('main')
    @livewire('notification-search')
@endsection
