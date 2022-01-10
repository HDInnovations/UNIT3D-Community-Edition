@extends('layout.default')

@section('title')
    <title>Applications - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Applications - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.applications.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.applications') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block">
            <h2>{{ __('staff.applications') }}</h2>
            <hr>
            <p class="text-red">
                <strong>
                    <i class="{{ config('other.font-awesome') }} fa-list"></i>
                    {{ __('staff.applications') }}
                </strong>
            </p>
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>{{ __('common.no') }}</th>
                        <th>{{ __('common.user') }}</th>
                        <th>{{ __('common.email') }}</th>
                        <th>{{ __('staff.application-type') }}</th>
                        <th>{{ __('staff.application-image-proofs') }}</th>
                        <th>{{ __('user.profile') }} {{ __('staff.links') }}</th>
                        <th>{{ __('common.created_at') }}</th>
                        <th>{{ __('common.status') }}</th>
                        <th>{{ __('common.moderated-by') }}</th>
                        <th>{{ __('common.action') }}</th>
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
                                    <a href="{{ route('staff.applications.show', ['id' => $application->id]) }}"
                                       class="btn btn-xs btn-success">
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
