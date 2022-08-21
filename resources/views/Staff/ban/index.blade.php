@extends('layout.default')

@section('title')
    <title>Bans - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Bans - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('staff.bans-log') }}
    </li>
@endsection

@section('page', 'page__bans-log--index')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.user') }} {{ __('user.bans') }}</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
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
                            <td>{{ $ban->id }}</td>
                            <td>
                                <x-user_tag :anon="false" :user="$ban->banneduser" />
                            </td>
                            <td>
                                <x-user_tag :anon="false" :user="$ban->staffuser" />
                            </td>
                            <td>{{ $ban->ban_reason }}</td>
                            <td>{{ $ban->unban_reason }}</td>
                            <td>
                                <time datetime="{{ $ban->created_at }}">
                                    {{ $ban->created_at }}
                                </time>
                            </td>
                            <td>
                                <time datetime="{{ $ban->removed_at }}">
                                    {{ $ban->removed_at }}
                                </time>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                No bans
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $bans->links('partials.pagination') }}
    </section>
@endsection
