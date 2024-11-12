@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">Donations</li>
@endsection

@section('content')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">Donation Statistics</h2>
        </header>
        <div class="chart-wrapper">
            <div>
                <canvas id="dailyDonationsChart"></canvas>
            </div>
            <div>
                <canvas id="monthlyDonationsChart"></canvas>
            </div>
        </div>
    </section>

    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">Donations</h2>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>User</th>
                        <th>Transaction</th>
                        <th>Cost</th>
                        <th>Upload #</th>
                        <th>Invite #</th>
                        <th>Bonus #</th>
                        <th>Lenght</th>
                        <th>Status</th>
                        <th>{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($donations as $donation)
                        <tr>
                            <td>{{ $donation->created_at }}</td>
                            <td>
                                <x-user_tag :user="$donation->user" :anon="false" />
                            </td>
                            <td>{{ $donation->transaction }}</td>
                            <td
                                class="{{ $donation->package->trashed() ? 'text-danger' : '' }}"
                                title="{{ $donation->package->trashed() ? 'Package has been deleted' : '' }}"
                            >
                                $ {{ $donation->package->cost }}
                            </td>
                            <td
                                class="{{ $donation->package->trashed() ? 'text-danger' : '' }}"
                                title="{{ $donation->package->trashed() ? 'Package has been deleted' : '' }}"
                            >
                                {{ App\Helpers\StringHelper::formatBytes($donation->package->upload_value ?? 0) }}
                            </td>
                            <td
                                class="{{ $donation->package->trashed() ? 'text-danger' : '' }}"
                                title="{{ $donation->package->trashed() ? 'Package has been deleted' : '' }}"
                            >
                                {{ $donation->package->invite_value ?? 0 }}
                            </td>
                            <td
                                class="{{ $donation->package->trashed() ? 'text-danger' : '' }}"
                                title="{{ $donation->package->trashed() ? 'Package has been deleted' : '' }}"
                            >
                                {{ $donation->package->bonus_value ?? 0 }}
                            </td>
                            <td
                                class="{{ $donation->package->trashed() ? 'text-danger' : '' }}"
                                title="{{ $donation->package->trashed() ? 'Package has been deleted' : '' }}"
                            >
                                @if ($donation->package->donor_value === null)
                                    Lifetime
                                @else
                                    {{ $donation->package->donor_value }} Days
                                @endif
                            </td>
                            <td>
                                @if ($donation->status === App\Models\Donation::PENDING)
                                    <span class="text-warning">Pending</span>
                                @elseif ($donation->status === App\Models\Donation::APPROVED)
                                    <span class="text-success">Approved</span>
                                @else
                                    <span class="text-danger">Rejected</span>
                                @endif
                            </td>

                            <td>
                                @if ($donation->status === App\Models\Donation::PENDING)
                                    <menu class="data-table__actions">
                                        <li class="data-table__action">
                                            <form
                                                action="{{ route('staff.donations.update', ['donation' => $donation]) }}"
                                                method="POST"
                                                x-data="confirmation"
                                            >
                                                @csrf
                                                <button
                                                    x-on:click.prevent="confirmAction"
                                                    data-b64-deletion-message="{{ base64_encode('Are you sure you want to approve this donation: ' . $donation->id . '?') }}"
                                                    class="form__button form__button--filled"
                                                >
                                                    Approve
                                                </button>
                                            </form>
                                        </li>

                                        <li class="data-table__action">
                                            <form
                                                action="{{ route('staff.donations.destroy', ['donation' => $donation]) }}"
                                                method="POST"
                                                x-data="confirmation"
                                            >
                                                @csrf
                                                <button
                                                    x-on:click.prevent="confirmAction"
                                                    data-b64-deletion-message="{{ base64_encode('Are you sure you want to reject this donation: ' . $donation->id . '?') }}"
                                                    class="form__button form__button--filled"
                                                >
                                                    Reject
                                                </button>
                                            </form>
                                        </li>
                                    </menu>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $donations->links('partials.pagination') }}
    </section>
@endsection

@section('scripts')
    @vite('resources/js/vendor/chart.js')
    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
        document.addEventListener('DOMContentLoaded', function () {
            const dailyDonations = {!! Js::encode($dailyDonations) !!};
            const monthlyDonations = {!! Js::encode($monthlyDonations) !!};

            // Daily Donations Chart
            const dailyCtx = document.getElementById('dailyDonationsChart').getContext('2d');
            new Chart(dailyCtx, {
                type: 'line',
                data: {
                    labels: dailyDonations.map((donation) => donation.date),
                    datasets: [
                        {
                            label: 'Daily Donations',
                            data: dailyDonations.map((donation) => donation.total),
                            backgroundColor: getComputedStyle(
                                document.documentElement,
                            ).getPropertyValue('--donation-chart-daily-bg'),
                            borderColor: getComputedStyle(
                                document.documentElement,
                            ).getPropertyValue('--donation-chart-daily-border'),
                            borderWidth: 1,
                            fill: false,
                        },
                    ],
                },
                options: {
                    scales: {
                        x: { type: 'time', time: { unit: 'day' } },
                        y: { beginAtZero: true },
                    },
                },
            });

            // Monthly Donations Chart
            const monthlyCtx = document.getElementById('monthlyDonationsChart').getContext('2d');
            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: monthlyDonations.map(
                        (donation) => `${donation.year}-${donation.month}`,
                    ),
                    datasets: [
                        {
                            label: 'Monthly Donations',
                            data: monthlyDonations.map((donation) => donation.total),
                            backgroundColor: getComputedStyle(
                                document.documentElement,
                            ).getPropertyValue('--donation-chart-monthly-bg'),
                            borderColor: getComputedStyle(
                                document.documentElement,
                            ).getPropertyValue('--donation-chart-monthly-border'),
                            borderWidth: 1,
                            fill: false,
                        },
                    ],
                },
                options: {
                    scales: {
                        x: { type: 'category' },
                        y: { beginAtZero: true },
                    },
                },
            });
        });
    </script>
@endsection
