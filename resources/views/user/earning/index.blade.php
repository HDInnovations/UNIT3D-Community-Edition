@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['user' => $user]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('users.earnings.index', ['user' => $user]) }}" class="breadcrumb__link">
            {{ __('bon.bonus') }} {{ __('bon.points') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('bon.earnings') }}
    </li>
@endsection

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@section('page', 'page__bonus--index')

@section('main')
    <section class="panelV2" x-data="{ extendStats: false }">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('bon.earnings') }}</h2>
            <div class="panel__actions">
                <label class="panel__action">
                    {{ __('bon.extended-stats') }}
                    <input type="checkbox" x-model="extendStats" />
                </label>
            </div>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('common.name') }}</th>
                        <th>{{ __('common.description') }}</th>
                        <th>Per torrent</th>
                        <th>Torrents</th>
                        <th>Hourly</th>
                        <th x-cloak x-show="extendStats">Daily</th>
                        <th x-cloak x-show="extendStats">Weekly</th>
                        <th x-cloak x-show="extendStats">Monthly</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bonEarnings as $bonEarning)
                        <tr>
                            <td>{{ $bonEarning->name }}</td>
                            <td>{{ $bonEarning->description }}</td>
                            <td>
                                @if ($bonEarning->operation === 'multiply')
                                    &times;
                                @else
                                    &plus;
                                @endif

                                {{ $bonEarning->variable }} &times; {{ $bonEarning->multiplier }}
                            </td>
                            <td>&times {{ $bonEarning->user_earnings }}</td>
                        </tr>
                    @endforeach
                </tbody>
                {{-- <tfoot> --}}
                {{-- <tr> --}}
                {{-- <td colspan="2">{{ __('bon.total') }}</td> --}}
                {{-- <td></td> --}}
                {{-- <td>{{ $bonEarnings->sum('torrents_count') }}</td> --}}
                {{-- <td x-cloak x-show="extendStats">{{ $bonEarnings->sum('torrents_count') * 24 }}</td> --}}
                {{-- <td x-cloak x-show="extendStats">{{ $bonEarnings->sum('torrents_count') * 24 * 7 }}</td> --}}
                {{-- <td x-cloak x-show="extendStats">{{ $bonEarnings->sum('torrents_count') * 24 * 30 }}</td> --}}
                {{-- </tr> --}}
                {{-- </tfoot> --}}
            </table>
        </div>
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('bon.your-points') }}</h2>
        <div class="panel__body">{{ $bon }}</div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('bon.your-points') }}</h2>
        <dl class="key-value">
            {{-- <dt>{{ __('bon.per-second') }}</dt> --}}
            {{-- <dd>{{ number_format($total / 60 / 60, 2) }}</dd> --}}
            {{-- <dt>{{ __('bon.per-minute') }}</dt> --}}
            {{-- <dd>{{ number_format($total / 60, 2) }}</dd> --}}
            {{-- <dt>{{ __('bon.per-hour') }}</dt> --}}
            {{-- <dd>{{ number_format($total, 2) }}</dd> --}}
            {{-- <dt>{{ __('bon.per-day') }}</dt> --}}
            {{-- <dd>{{ number_format($total * 24, 2) }}</dd> --}}
            {{-- <dt>{{ __('bon.per-week') }}</dt> --}}
            {{-- <dd>{{ number_format($total * 24 * 7, 2) }}</dd> --}}
            {{-- <dt>{{ __('bon.per-month') }}</dt> --}}
            {{-- <dd>{{ number_format($total * 24 * 30, 2) }}</dd> --}}
            {{-- <dt>{{ __('bon.per-year') }}</dt> --}}
            {{-- <dd>{{ number_format($total * 24 * 365, 2) }}</dd> --}}
        </dl>
        <div class="panel__body">
            <p class="form__group form__group--horizontal">
                <a
                    href="{{ route('users.history.index', ['user' => $user, 'completed' => 'include', 'active' => 'include']) }}"
                    class="form__button form__button--filled form__button--centered"
                >
                    {{ __('bon.review-seeds') }}
                </a>
            </p>
        </div>
    </section>
@endsection
