@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('bonus') }}" class="breadcrumb__link">
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
            @foreach ($uploadOptions as $u => $uu)
                <tr>
                    <td>{{ $uu['description'] }}</td>
                    <td>{{ $uu['cost'] }}</td>
                    <td>
                        <form method="POST" action="{{ route('bonus_exchange', ['id' => $uu['id']]) }}">
                            @csrf
                            <button class="form__button form__button--filled">
                                {{ __('bon.exchange') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            @foreach ($personalFreeleech as $p => $pf)
                <tr>
                    <td>{{ $pf['description'] }}</td>
                    <td>{{ $pf['cost'] }}</td>
                    <td>
                        @if ($activefl)
                            <button disabled class="form__button form__button--filled">
                                {{ __('bon.activated') }}!
                            </button>
                        @else
                            <form method="POST" action="{{ route('bonus_exchange', ['id' => $pf['id']]) }}">
                                @csrf
                                <button class="form__button form__button--filled">
                                    {{ __('bon.exchange') }}
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            @foreach ($invite as $i => $in)
                <tr>
                    <td>{{ $in['description'] }}</td>
                    <td>{{ $in['cost'] }}</td>
                    <td>
                        <form method="POST" action="{{ route('bonus_exchange', ['id' => $in['id']]) }}">
                            @csrf
                            <button class="form__button form__button--filled">
                                {{ __('bon.exchange') }}
                            </button>
                        </form>
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
