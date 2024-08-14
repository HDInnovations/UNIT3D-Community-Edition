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
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.staff') }} {{ __('common.stats') }}</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('common.name') }}</th>
                        <th>{{ __('stat.last30days') }}</th>
                        <th>{{ __('stat.last60days') }}</th>
                        <th>{{ __('stat.all-time') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($staffActivities as $activity)
                        <tr>
                            <td>
                                <x-user_tag :anon="false" :user="$activity->user" />
                            </td>
                            <td>{{ $activity->last_30_days }}</td>
                            <td>{{ $activity->last_60_days }}</td>
                            <td>{{ $activity->total_actions }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    @livewire('audit-log-search')
@endsection
