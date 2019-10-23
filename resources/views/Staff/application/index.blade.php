@extends('layout.default')

@section('title')
    <title>Applications - Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Applications - Staff Dashboard">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.applications.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Applications</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block">
            <h2>Applications</h2>
            <hr>
            <p class="text-red"><strong><i class="{{ config('other.font-awesome') }} fa-list"></i> Applications</strong></p>
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>@lang('common.no')</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Image Proofs</th>
                        <th>Profile Links</th>
                        <th>Created On</th>
                        <th>Status</th>
                        <th>Moderated By</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($applications)
                        @foreach ($applications as $key => $application)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $application->user->username ?? 'N/A' }}</td>
                                <td>{{ $application->email }}</td>
                                <td>{{ $application->type }}</td>
                                <td>{{ $application->imageProofs->count() }}</td>
                                <td>{{ $application->urlProofs->count() }}</td>
                                <td>
                                    {{ $application->created_at->toDayDateTimeString() }}
                                    ({{ $application->created_at->diffForHumans() }})
                                </td>
                                <td>
                                    @if ($application->status == 0)
                                        <span class="text-warning">PENDING</span>
                                    @elseif ($application->status == 1)
                                        <span class="text-success">APPROVED</span>
                                    @else
                                        <span class="text-danger">REJECTED</span>
                                    @endif
                                </td>
                                <td>{{ $application->moderated->username ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('staff.applications.show', ['id' => $application->id]) }}" class="btn btn-xs btn-success">
                                        <i class="{{ config('other.font-awesome') }} fa-eye"></i> View App
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                <div class="text-center">
                    {{ $applications->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
