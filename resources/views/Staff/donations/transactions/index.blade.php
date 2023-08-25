@extends('layout.default')

@section('title')
    <title>Transactions - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Transactions - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="#" class="breadcrumb__link">
            Donations
        </a>
    </li>
    <li class="breadcrumb--active">
        Transactions
    </li>
@endsection

@section('page', 'page__donations-transactions--index')

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">Transactions</h2>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('common.username') }}</th>
                        <th>Item</th>
                        <th>Order ID</th>
                        <th>{{ __('torrent.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td>
                                <a href="{{ route('users.show', ['user' => $transaction->user->username]) }}">
                                    {{ $transaction->user->username }}
                                </a>
                            </td>
                            <td>{{ $transaction->item->name }}</td>
                            <td>
                                {{ $transaction->nowpayments_order_id }}
                            </td>
                            <td>
                                @if ($transaction->confirmed)
                                    <i class="{{ config('other.font-awesome') }} fa-circle-check text-green"></i>
                                @else
                                    <i class="{{ config('other.font-awesome') }} fa-circle-question text-orange"></i>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
