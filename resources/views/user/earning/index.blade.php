@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('earnings.index', ['username' => $user->username]) }}" class="breadcrumb__link">
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
                    <input type="checkbox" x-model="extendStats">
                </label>
            </div>
        </header>
        <table class="table table-condensed table-bordered table-striped">
            <thead>
            <tr>
                <th>{{ __('common.name') }}</th>
                <th>{{ __('common.description') }}</th>
                <th>Per {{ __('torrent.torrent') }}</th>
                <th>Hourly</th>
                <th x-cloak x-show="extendStats">Daily</th>
                <th x-cloak x-show="extendStats">Weekly</th>
                <th x-cloak x-show="extendStats">Monthly</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ __('torrent.dying-torrent') }}</td>
                <td>{{ __('torrent.last-seeder') }}</td>
                <td>2.00 &times; {{ $dying }}</td>
                <td>{{ $dying * 2 }}</td>
                <td x-cloak x-show="extendStats">{{ $dying * 2 * 24 }}</td>
                <td x-cloak x-show="extendStats">{{ $dying * 2 * 24 * 7 }}</td>
                <td x-cloak x-show="extendStats">{{ $dying * 2 * 24 * 30 }}</td>
            </tr>
            <tr>
                <td>{{ __('torrent.legendary-torrent') }}</td>
                <td>{{ __('common.older-than') }} 12 {{ strtolower(__('common.months')) }}</td>
                <td>1.50 &times; {{ $legendary }}</td>
                <td>{{ $legendary * 1.5 }}</td>
                <td x-cloak x-show="extendStats">{{ $legendary * 1.5 * 24 }}</td>
                <td x-cloak x-show="extendStats">{{ $legendary * 1.5 * 24 * 7 }}</td>
                <td x-cloak x-show="extendStats">{{ $legendary * 1.5 * 24 * 30 }}</td>
            </tr>
            <tr>
                <td>{{ __('torrent.old-torrent') }}</td>
                <td>{{ __('common.older-than') }} 6 {{ strtolower(__('common.months')) }}</td>
                <td>1.00 &times; {{ $old }}</td>
                <td>{{ $old * 1 }}</td>
                <td x-cloak x-show="extendStats">{{ $old * 1 * 24 }}</td>
                <td x-cloak x-show="extendStats">{{ $old * 1 * 24 * 7 }}</td>
                <td x-cloak x-show="extendStats">{{ $old * 1 * 24 * 30 }}</td>
            </tr>
            <tr>
                <td>{{ __('common.huge') }} {{ __('torrent.torrents') }}</td>
                <td>{{ __('torrent.torrent') }} {{ __('torrent.size') }} &gt; 100&nbsp;GiB</td>
                <td>0.75 &times; {{ $huge }}</td>
                <td>{{ $huge * 0.75 }}</td>
                <td x-cloak x-show="extendStats">{{ $huge * 0.75 * 24 }}</td>
                <td x-cloak x-show="extendStats">{{ $huge * 0.75 * 24 * 7 }}</td>
                <td x-cloak x-show="extendStats">{{ $huge * 0.75 * 24 * 30 }}</td>
            </tr>
            <tr>
                <td>{{ __('common.large') }} {{ __('torrent.torrents') }}</td>
                <td>{{ __('torrent.torrent') }} {{ __('torrent.size') }} &ge; 25&nbsp;GiB {{ strtolower(__('common.but')) }} < 100GiB</td>
                <td>0.50 &times; {{ $large }}</td>
                <td>{{ $large * 0.5 }}</td>
                <td x-cloak x-show="extendStats">{{ $large * 0.5 * 24 }}</td>
                <td x-cloak x-show="extendStats">{{ $large * 0.5 * 24 * 7 }}</td>
                <td x-cloak x-show="extendStats">{{ $large * 0.5 * 24 * 30 }}</td>
            </tr>
            <tr>
                <td>{{ __('common.everyday') }} {{ __('torrent.torrents') }}</td>
                <td>{{ __('torrent.torrent') }} {{ __('torrent.size') }} &ge; 1&nbsp;GiB {{ strtolower(__('common.but')) }} < 25GiB</td>
                <td>0.25 &times; {{ $regular }}</td>
                <td>{{ $regular * 0.25 }}</td>
                <td x-cloak x-show="extendStats">{{ $regular * 0.25 * 24 }}</td>
                <td x-cloak x-show="extendStats">{{ $regular * 0.25 * 24 * 7 }}</td>
                <td x-cloak x-show="extendStats">{{ $regular * 0.25 * 24 * 30 }}</td>
            </tr>
            <tr>
                <td>{{ __('torrent.legendary-seeder') }}</td>
                <td>{{ __('torrent.seed-time') }} &ge; 1 {{ strtolower(__('common.year')) }}</td>
                <td>2.00 &times; {{ $legend }}</td>
                <td>{{ $legend * 2 }}</td>
                <td x-cloak x-show="extendStats">{{ $legend * 2 * 24 }}</td>
                <td x-cloak x-show="extendStats">{{ $legend * 2 * 24 * 7 }}</td>
                <td x-cloak x-show="extendStats">{{ $legend * 2 * 24 * 30 }}</td>
            </tr>
            <tr>
                <td>{{ __('torrent.mvp') }} {{ __('torrent.seeder') }}</td>
                <td>{{ __('torrent.seed-time') }} &ge; 6 {{ strtolower(__('common.months')) }} {{ strtolower(__('common.but')) }} < 1 {{ strtolower(__('common.year')) }}</td>
                <td>1.00 &times; {{ $mvp }}</td>
                <td>{{ $mvp * 1 }}</td>
                <td x-cloak x-show="extendStats">{{ $mvp * 1 * 24 }}</td>
                <td x-cloak x-show="extendStats">{{ $mvp * 1 * 24 * 7 }}</td>
                <td x-cloak x-show="extendStats">{{ $mvp * 1 * 24 * 30 }}</td>
            </tr>
            <tr>
                <td>{{ __('torrent.commited') }} {{ __('torrent.seeder') }}</td>
                <td>{{ __('torrent.seed-time') }} &ge; 3 {{ strtolower(__('common.months')) }} {{ strtolower(__('common.but')) }} < 6 {{ strtolower(__('common.months')) }}</td>
                <td>0.75 &times; {{ $committed }}</td>
                <td>{{ $committed * 0.75 }}</td>
                <td x-cloak x-show="extendStats">{{ $committed * 0.75 * 24 }}</td>
                <td x-cloak x-show="extendStats">{{ $committed * 0.75 * 24 * 7 }}</td>
                <td x-cloak x-show="extendStats">{{ $committed * 0.75 * 24 * 30 }}</td>
            </tr>
            <tr>
                <td>{{ __('torrent.team-player') }} {{ __('torrent.seeder') }}</td>
                <td>{{ __('torrent.seed-time') }} &ge; 2 {{ strtolower(__('common.months')) }} {{ strtolower(__('common.but')) }} < 3 {{ strtolower(__('common.months')) }}</td>
                <td>0.50 &times; {{ $teamplayer }}</strong></td>
                <td>{{ $teamplayer * 0.5 }}</td>
                <td x-cloak x-show="extendStats">{{ $teamplayer * 0.5 * 24 }}</td>
                <td x-cloak x-show="extendStats">{{ $teamplayer * 0.5 * 24 * 7 }}</td>
                <td x-cloak x-show="extendStats">{{ $teamplayer * 0.5 * 24 * 30 }}</td>
            </tr>
            <tr>
                <td>{{ __('torrent.participant') }} {{ __('torrent.seeder') }}</td>
                <td>{{ __('torrent.seed-time') }} &ge; 1 {{ strtolower(__('common.month')) }} {{ strtolower(__('common.but')) }} < 2 {{ strtolower(__('common.months')) }}</td>
                <td>0.25 &times; {{ $participant }}</strong></td>
                <td>{{ $participant * 0.25 }}</td>
                <td x-cloak x-show="extendStats">{{ $participant * 0.25 * 24 }}</td>
                <td x-cloak x-show="extendStats">{{ $participant * 0.25 * 24 * 7 }}</td>
                <td x-cloak x-show="extendStats">{{ $participant * 0.25 * 24 * 30 }}</td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2">{{ __('bon.total') }}</td>
                <td>-</td>
                <td>{{ $total }}</td>
                <td x-cloak x-show="extendStats">{{ $total * 24 }}</td>
                <td x-cloak x-show="extendStats">{{ $total * 24 * 7 }}</td>
                <td x-cloak x-show="extendStats">{{ $total * 24 * 30 }}</td>
            </tr>
            </tfoot>
        </table>
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('bon.your-points') }}</h2>
        <div class="panel__body">{{ $userbon }}</div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('bon.your-points') }}</h2>
        <dl class="key-value">
            <dt>{{ __('bon.per-second') }}</dt>
            <dd>{{ number_format($total / 60 / 60, 2) }}</dd>
            <dt>{{ __('bon.per-minute') }}</dt>
            <dd>{{ number_format($total / 60, 2) }}</dd>
            <dt>{{ __('bon.per-hour') }}</dt>
            <dd>{{ number_format($total, 2) }}</dd>
            <dt>{{ __('bon.per-day') }}</dt>
            <dd>{{ number_format($total * 24, 2) }}</dd>
            <dt>{{ __('bon.per-week') }}</dt>
            <dd>{{ number_format($total * 24 * 7, 2) }}</dd>
            <dt>{{ __('bon.per-month') }}</dt>
            <dd>{{ number_format($total * 24 * 30, 2) }}</dd>
            <dt>{{ __('bon.per-year') }}</dt>
            <dd>{{ number_format($total * 24 * 365, 2) }}</dd>
        </dl>
        <div class="panel__body">
            <a
                href="{{ route('users.history.index', ['user' => $user, 'completed' => 'include', 'active' => 'include']) }}"
                class="form__button form__button--filled"
            >
                {{ __('bon.review-seeds') }}
            </a>
        </div>
    </section>
@endsection