@extends('layout.default')

@section('title')
    <title>Failed Login Log - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Invites Log - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.authentications.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.failed-login-log') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <h2>{{ __('staff.failed-login-log') }}</h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <h2>Failed Logins</h2>
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>{{ __('common.no') }}</th>
                                <th>{{ __('user.user-id') }}</th>
                                <th>{{ __('common.username') }}</th>
                                <th>{{ __('common.ip') }}</th>
                                <th>{{ __('user.created-on') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($attempts) == 0)
                                <p>The are no failed login entries in the database!</p>
                            @else
                                @foreach ($attempts as $attempt)
                                    <tr>
                                        <td>
                                            {{ $attempt->id }}
                                        </td>
                                        <td>
                                            {{ $attempt->user_id ?? 'Not Found' }}
                                        </td>
                                        <td>
                                            @if ($attempt->user_id == null)
                                                {{ $attempt->username }}
                                            @else
                                                <a class="text-bold"
                                                   href="{{ route('users.show', ['username' => $attempt->username]) }}">
                                                    {{ $attempt->username }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $attempt->ip_address }}
                                        </td>
                                        <td>
                                            {{ $attempt->created_at }}
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
                {{ $attempts->links() }}
            </div>
        </div>
    </div>
@endsection
