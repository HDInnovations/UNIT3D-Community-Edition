@extends('layout.default')

@section('title')
    <title>Reports - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Reports - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('staff.reports-log') }}
    </li>
@endsection

@section('page', 'page__report--index')

@section('main')
    <div class="panelV2">
        <h2 class="panel__heading">{{ __('staff.reports-log') }}</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>{{ __('common.title') }}</th>
                    <th>Reported</th>
                    <th>{{ __('common.reporter') }}</th>
                    <th>{{ __('user.created-on') }}</th>
                    <th>{{ __('user.judge') }}</th>
                    <th>{{ __('forum.solved') }}</th>
                </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $report)
                        <tr>
                            <td>{{ $report->id }}</td>
                            <td>{{ $report->type }}</td>
                            <td>
                                <a href="{{ route('staff.reports.show', ['id' => $report->id]) }}">
                                    {{ $report->title }}
                                </a>
                            </td>
                            <td>
                                <x-user_tag :anon="false" :user="$report->reported" />
                            </td>
                            <td>
                                <x-user_tag :anon="false" :user="$report->reporter" />
                            </td>
                            <td>
                                <time datetime="{{ $report->created_at }}" title="{{ $report->created_at }}">
                                    {{ $report->created_at->toDayDateTimeString() }}
                                </time>
                            </td>
                            <td>
                                @if ($report->staff_id !== null)
                                    <x-user_tag :anon="false" :user="$report->staff" />
                                @endif
                            </td>
                            <td>
                                @if ($report->solved)
                                    <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                                    {{ __('common.yes') }}
                                @else
                                    <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                                    {{ __('common.no') }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No reports</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $reports->links('partials.pagination') }}
    </div>
@endsection
