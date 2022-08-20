@extends('layout.default')

@section('title')
    <title>Invites Log - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
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
        {{ __('staff.invites-log') }}
    </li>
@endsection

@section('page', 'page__invite-log--index')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('staff.invites-log') }}</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('user.sender') }}</th>
                        <th>{{ __('common.email') }}</th>
                        <th>Token</th>
                        <th>{{ __('user.created-on') }}</th>
                        <th>{{ __('user.expires-on') }}</th>
                        <th>{{ __('user.accepted-by') }}</th>
                        <th>{{ __('user.accepted-at') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invites as $invite)
                        <tr>
                            <td>{{ $invite->id }}</td>
                            <td>
                                <x-user_tag :anon="false" :user="$invite->sender" />
                            </td>
                            <td>{{ $invite->email }}</td>
                            <td>{{ $invite->code }}</td>
                            <td>
                                <time datetime="{{ $invite->created_at }}">
                                    {{ $invite->created_at }}
                                </time>
                            </td>
                            <td>
                                <time datetime="{{ $invite->expires_on }}">
                                    {{ $invite->expires_on }}
                                </time>
                            </td>
                            <td>
                                @if ($invite->accepted_by === null)
                                    N/A
                                @else
                                    <x-user_tag :anon="false" :user="$invite->receiver" />
                                @endif
                            </td>
                            <td>
                                <time datetime="{{ $invite->accepted_at ?? '' }}">
                                    {{ $invite->accepted_at ?? 'N/A' }}
                                </time>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No invites exist</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    {{ $invites->links('partials.pagination') }}
@endsection
