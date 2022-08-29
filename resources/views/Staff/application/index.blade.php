@extends('layout.default')

@section('title')
    <title>Applications - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Applications - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('staff.applications') }}
    </li>
@endsection

@section('page', 'page__application--index')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('staff.applications') }}</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('common.user') }}</th>
                        <th>{{ __('common.email') }}</th>
                        <th>{{ __('staff.application-type') }}</th>
                        <th>{{ __('common.image') }}</th>
                        <th>{{ __('staff.links') }}</th>
                        <th>{{ __('common.created_at') }}</th>
                        <th>{{ __('common.status') }}</th>
                        <th>{{ __('common.moderated-by') }}</th>
                        <th>{{ __('common.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($applications as $application)
                        <tr>
                            <td>{{ $application->id }}</td>
                            <td>
                                @if ($application->user === null)
                                    N/A
                                @else
                                    <x-user_tag :anon="false" :user="$application->user" />
                                @endif
                            </td>
                            <td>{{ $application->email }}</td>
                            <td>{{ $application->type }}</td>
                            <td>{{ $application->imageProofs->count() }}</td>
                            <td>{{ $application->urlProofs->count() }}</td>
                            <td>
                                <time
                                    datetime="{{ $application->created_at }}"
                                    title={{ $application->created_at }}
                                >
                                    {{ $application->created_at->diffForHumans() }}
                                </time>
                            </td>
                            <td>
                                @if ($application->status == 0)
                                    <span class="application--pending">Pending</span>
                                @elseif ($application->status == 1)
                                    <span class="application--approved">Approved</span>
                                @else
                                    <span class="application--rejected">Rejected</span>
                                @endif
                            </td>
                            <td>
                                @if ($application->moderated === null)
                                    N/A
                                @else
                                    <x-user_tag :anon="false" :user="$application->user" />
                                @endif
                            </td>
                            <td>
                                <menu class="data-table__actions">
                                    <li class="data-table__action">
                                        <a
                                            class="form__button form__button--text"
                                            href="{{ route('staff.applications.show', ['id' => $application->id]) }}">
                                            {{ __('common.view') }}
                                        </a>
                                    </li>
                                </menu>
                            </td>
                        </tr>
                    @empty
                        <tr class="applications--empty">
                            <td colspan="10">
                                {{ __('common.no')}} {{__('staff.applications') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $applications->links('partials.pagination') }}
    </section>
@endsection
