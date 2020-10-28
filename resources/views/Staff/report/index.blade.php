@extends('layout.default')

@section('title')
    <title>Reports - @lang('staff.staff-dashboard') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Reports - @lang('staff.staff-dashboard')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.reports.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.reports-log')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <h2>@lang('staff.reports-log')</h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <p class="text-red"><strong><i class="{{ config('other.font-awesome') }} fa-list"></i>
                            @lang('common.report')</strong>
                    </p>
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>@lang('common.title')</th>
                                    <th>Reported</th>
                                    <th>@lang('common.reporter')</th>
                                    <th class="col-md-2">@lang('user.created-on')</th>
                                    <th>@lang('user.judge')</th>
                                    <th>@lang('forum.solved')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($reports) == 0)
                                    <p>The are no reports in database</p>
                                @else
                                    @foreach ($reports as $report)
                                        <tr>
                                            <td>
                                                {{ $report->id }}
                                            </td>
                                            <td>
                                                {{ $report->type }}
                                            </td>
                                            <td>
                                                <a
                                                    href="{{ route('staff.reports.show', ['id' => $report->id]) }}">{{ $report->title }}</a>
                                            </td>
                                            <td class="user-name">
                                                <a class="name"
                                                    href="{{ route('users.show', ['username' => $report->reported->username]) }}">
                                                    {{ $report->reported->username }}
                                                </a>
                                            </td>
                                            <td class="user-name">
                                                <a class="name"
                                                    href="{{ route('users.show', ['username' => $report->reporter->username]) }}">
                                                    {{ $report->reporter->username }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $report->created_at->toDayDateTimeString() }}
                                            </td>
                                            <td class="user-name">
                                                <a class="name"
                                                    href="{{ $report->staff->username ? route('users.show', ['username' => $report->staff->username]) : route('home') }}">
                                                    {{ $report->staff_id ? $report->staff->username : '' }}
                                                </a>
                                            </td>
                                            <td>
                                                @if ($report->solved == 0)
                                                    <span class="text-red">
                                                        <strong><i class="{{ config('other.font-awesome') }} fa-times"></i> @lang('common.no')</strong>
                                                    </span>
                                                @else
                                                    <span class="text-green">
                                                        <strong><i class="{{ config('other.font-awesome') }} fa-check"></i> @lang('common.yes')</strong>
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="text-center">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
@endsection
