@extends('layout.default')

@section('title')
    <title>Ban Log - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('user.bans') }}
    </li>
@endsection

@section('nav-tabs')
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('warnings.show', ['username' => $user->username]) }}">
            {{ __('user.warning-log') }}
        </a>
    </li>
    <li class="nav-tab--active">
        <a class="nav-tab--active__link" href="{{ route('users.bans.index', ['user' => $user]) }}">
            {{ __('user.ban-log') }}
        </a>
    </li>
@endsection

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('user.bans') }}</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('common.user') }}</th>
                        <th>{{ __('user.judge') }}</th>
                        <th>{{ __('user.reason-ban') }}</th>
                        <th>{{ __('user.reason-unban') }}</th>
                        <th>{{ __('user.created') }}</th>
                        <th>{{ __('user.removed') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bans as $ban)
                        <tr>
                            <td>
                                <x-user_tag :user="$ban->banneduser" :anon="false" />
                            </td>
                            <td>
                                <x-user_tag :user="$ban->staffuser" :anon="false" />
                            </td>
                            <td>{{ $ban->ban_reason }}</td>
                            <td>{{ $ban->unban_reason }}</td>
                            <td>{{ $ban->created_at }}</td>
                            <td>{{ $ban->removed_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">{{ __('user.no-ban') }}</td>
                        </tr>
                    @endforelse
            </table>
        </div>
    </section>
@endsection
