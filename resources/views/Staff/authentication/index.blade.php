@extends('layout.default')

@section('title')
    <title>Failed Login Log - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Invites Log - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('staff.failed-login-log') }}
    </li>
@endsection

@section('page', 'page__failed-logins-log--index')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('staff.failed-login-log') }}</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
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
                    @forelse ($attempts as $attempt)
                        <tr>
                            <td>{{ $attempt->id }}</td>
                            <td>{{ $attempt->user_id ?? 'Not Found' }}</td>
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
                            <td>{{ $attempt->ip_address }}</td>
                            <td>
                                <time datetime="{{ $attempt->created_at }}">
                                    {{ $attempt->created_at }}
                                </time>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No failed logins</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $attempts->links('partials.pagination') }}
    </section>
@endsection
