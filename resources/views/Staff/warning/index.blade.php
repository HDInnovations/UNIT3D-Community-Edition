@extends('layout.default')

@section('title')
    <title>Warnings Log - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Warnings Log - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('staff.warnings-log') }}
    </li>
@endsection

@section('page', 'page__warning-log--index')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('staff.warnings-log') }} ({{ $warningcount }})</h2>
        <table class="table table-condensed table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>{{ __('common.user') }}</th>
                <th>{{ __('user.warned-by') }}</th>
                <th>{{ __('torrent.torrent') }}</th>
                <th>{{ __('common.reason') }}</th>
                <th>{{ __('user.created-on') }}</th>
                <th>{{ __('user.expires-on') }}</th>
                <th>{{ __('common.active') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($warnings as $warning)
                <tr>
                    <td>
                        <x-user_tag :anon="false" :user="$warning->warneduser" />
                    </td>
                    <td>
                        <x-user_tag :anon="false" :user="$warning->staffuser" />
                    </td>
                    <td>
                        @isset($warning->torrent)
                            <a href="{{ route('torrent', ['id' => $warning->torrenttitle->id]) }}">
                                {{ $warning->torrenttitle->name }}
                            </a>
                        @else
                            n/a
                        @endisset
                    </td>
                    <td>{{ $warning->reason }}</td>
                    <td>
                        <time datetime="{{ $warning->created_at }}">
                            {{ $warning->created_at }}
                        </time>
                    </td>
                    <td>
                        <time datetime="{{ $warning->expires_on }}">
                            {{ $warning->expires_on }}
                        </time>
                    </td>
                    <td>
                        @if ($warning->active)
                            <span class='text-green'>{{ __('common.yes') }}</span>
                        @else
                            <span class='text-red'>{{ __('common.expired') }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No warnings</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        {{ $warnings->links('partials.pagination') }}
    </section>
@endsection
