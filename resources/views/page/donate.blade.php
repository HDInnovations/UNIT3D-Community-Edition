@extends('layout.default')

@section('title')
    <title>Donate - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Donate">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('pages.index') }}" class="breadcrumb__link">
            Pages
        </a>
    </li>
    <li class="breadcrumb--active">
        Donate
    </li>
@endsection

@section('main')
    <!-- Tiers -->
    <h2>VIP Tiers</h2>
    <div class="gird__container--donation">
        @foreach ($items as $item)
            <div class="grid__item--donation">
                <div class="text-center oper-item card_header">
                    <h1>{{ $item->name }}</h1>
                </div>
                <div class="text-center oper-item card">
                    <span class="text-center">
                        <p class="price">${{ $item->price_usd }}@if ($item->id <= 2)<sup>2</sup>@endif</p>
                        <p class="adventage">{{ $item->bon_bonus }} BON points</p>
                    </span>
                </div>
                <div class="text-center oper-item card_footer">
                    <form
                        class="form"
                        method="POST"
                        action="{{ route('create_payment') }}"
                        enctype="multipart/form-data"
                    >
                        @csrf
                        <label for="coin">Coin</label>
                        <select name="coin" class="form__select" x-model="coin">
                            <option hidden disabled selected value=""></option>
                            <option value="BTC">Bitcoin</option>
                            <option value="XMR">Monero</option>
                        </select>
                        <hr>
                        <label for="fiat">Fiat Currency</label>
                        <select name="fiat" class="form__select" x-model="fiat">
                            <option hidden disabled selected value=""></option>
                            <option value="USD">USD</option>
                            <option value="GBP">GBP</option>
                            <option value="EUR">EUR</option>
                        </select>
                        <hr>
                        <label for="tier">Tier</label>
                        <select name="tier" class="form__select" id="btcpay-input-price" x-model="tier">
                            <option hidden disabled selected value=""></option>
                            <option value="1">1 Month</option>
                            <option value="2">3 Months</option>
                            <option value="3">6 Months</option>
                            <option value="4">12 Months</option>
                        </select>
                        <hr>
                        <div class="label-text" style="padding: 5px;">
                            Please note: <br>
                            <u>In the next step</u>, we will ask to provide an email address and a username. You do not need to provide your real email address,<br>
                            but we need your aither username to map your donation to an account.
                        </div>
                        <hr>
                        <button type="submit" x-show="tier && coin && fiat">
                            submit
                        </button>
                    </form>

                </div>
            </div>
        @endforeach
    </div>
@endsection
