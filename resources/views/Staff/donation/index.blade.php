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
                            <td>$ {{ $donation->package->cost }}</td>
                            <td>
                                {{ App\Helpers\StringHelper::formatBytes($donation->package->upload_value) }}
                            </td>
                            <td>{{ $donation->package->invite_value }}</td>
                            <td>{{ $donation->package->bonus_value }}</td>
                            <td>
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
