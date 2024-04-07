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
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('bon.earnings') }}</h2>
        </header>
        <div class="panel__body">
            <p>
                {{ __('Your bonus earnings are influenced by the number of seeders a torrent has and the size of the torrent you're seeding. Below, you will find detailed explanations on how these factors affect your bonuses:') }}
            </p>
            <p>
                <strong>{{ __('Seeder Bonus Calculation:') }}</strong>
                {{ __('Bonus points vary based on the scarcity of seeders:') }}
                <ul>
                    <li>1 seeder: <strong>10 points</strong></li>
                    <li>2 seeders: <strong>5 points</strong></li>
                    <li>3-5 seeders: <strong>3 points</strong></li>
                    <li>6-9 seeders: <strong>2 points</strong></li>
                    <li>10-19 seeders: <strong>1.5 points</strong></li>
                    <li>20-35 seeders: <strong>1 point</strong></li>
                    <li>36-49 seeders: <strong>0.75 points</strong></li>
                    <li>50+ seeders: <strong>0.5 points</strong>, the base amount</li>
                </ul>
            </p>
            <p>
                <strong>{{ __('Size Multiplier Formula:') }}</strong>
                {{ __('The size of the torrent also plays a significant role in determining your bonus. Torrent sizes are rounded up to the nearest GB, with a formula applied to calculate the size multiplier as follows:') }}
                <br>
                <em>Size Multiplier = (log(sizeInGB) + 1) * 0.5</em>
                <br>
                {{ __('This adjustment, with a scale factor set at 0.5, ensures an equitable reward system across different torrent sizes.') }}
            </p>
            <p>
                <strong>{{ __('Examples with Updated Scale Factor:') }}</strong>
                Assuming a base seeder bonus of 1 (for simplicity), here's how different torrent sizes would affect your bonus:
                <ul>
                    <li>1GB torrent: Size Multiplier = (log(1) + 1) * 0.5 ≈ 0.5</li>
                    <li>10GB torrent: Size Multiplier = (log(10) + 1) * 0.5 ≈ 1.65</li>
                    <li>20GB torrent: Size Multiplier = (log(20) + 1) * 0.5 ≈ 2</li>
                    <li>50GB torrent: Size Multiplier = (log(50) + 1) * 0.5 ≈ 2.45</li>
                    <li>100GB torrent: Size Multiplier = (log(100) + 1) * 0.5 ≈ 2.80</li>
                </ul>
                {{ __('These multipliers, when applied alongside the seeder bonus, calculate the total bonus for seeding each torrent, rewarding your contributions to the community.') }}
            </p>
        </div>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Hourly</th>
                        <th>Daily</th>
                        <th>Weekly</th>
                        <th>Monthly</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Simplified Display Row for Total Earnings -->
                    <tr>
                        <td>{{ number_format($total, 2) }}</td>
                        <td>{{ number_format($total * 24, 2) }}</td>
                        <td>{{ number_format($total * 24 * 7, 2) }}</td>
                        <td>{{ number_format($total * 24 * 30, 2) }}</td>
                    </tr>
                </tbody>
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
