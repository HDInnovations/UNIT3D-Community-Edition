@extends('layout.default')

@section('title')
    <title>{{ $user->username }} {{ __('user.uploads') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['user' => $user]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('user.uploads') }}
    </li>
@endsection

@section('page', 'page__user-uploads--index')

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@section('main')
    @livewire('user-uploads', ['userId' => $user->id])
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('user.statistics') }}</h2>
        <dl class="key-value">
            <dt>{{ __('user.total-download') }}</dt>
            <dd>{{ App\Helpers\StringHelper::formatBytes($history->download ?? 0, 2) }}</dd>
            <dt>{{ __('user.total-download') }} ({{ __('user.credited-download') }})</dt>
            <dd>
                {{ App\Helpers\StringHelper::formatBytes($history->credited_download ?? 0, 2) }}
            </dd>
            <dt>{{ __('user.total-upload') }}</dt>
            <dd>{{ App\Helpers\StringHelper::formatBytes($history->upload ?? 0, 2) }}</dd>
            <dt>{{ __('user.total-upload') }} ({{ __('user.credited-upload') }})</dt>
            <dd>{{ App\Helpers\StringHelper::formatBytes($history->credited_upload ?? 0, 2) }}</dd>
        </dl>
    </section>
@endsection
