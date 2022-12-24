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
        {{ __('bon.store') }}
    </li>
@endsection

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@section('page', 'page__bonus--store')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('bon.exchange') }}</h2>
        <table class="table table-condensed table-striped">
            <thead>
            <tr>
                <th>{{ __('bon.item') }}</th>
                <th>{{ __('bon.points') }}</th>
                <th>{{ __('bon.exchange') }}</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->cost }}</td>
                        <td>
                            @if ($item->personal_freeleech && $activefl)
                                <button disabled class="form__button form__button--filled">
                                    {{ __('bon.activated') }}!
                                </button>
                            @else
                                <form method="POST" action="{{ route('transactions.store', ['username' => $user->username]) }}">
                                    @csrf
                                    <button class="form__button form__button--filled">
                                        {{ __('bon.exchange') }}
                                    </button>
                                    <input type="hidden" name="exchange" value="{{ $item->id }}">
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('bon.your-points') }}</h2>
        <div class="panel__body">{{ $userbon }}</div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('bon.no-refund') }}</h2>
        <div class="panel__body">{{ __('bon.exchange-warning') }}</div>
    </section>
@endsection
