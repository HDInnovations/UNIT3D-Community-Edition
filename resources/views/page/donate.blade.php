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
    <h2>Donation Items</h2>
    <div class="gird__container--donation">
        @foreach ($items as $item)
            <div class="grid__item--donation">
                <div class="text-center oper-item card_header">
                    <h1>{{ $item->name }}</h1>
                </div>
                <div class="text-center oper-item card">
                    <span class="text-center">
                        <p class="type">Type: {{ $item->type ?? 'N/A' }}</p>
                        <p class="price">${{ $item->price_usd }}@if ($item->id <= 2)<sup>2</sup>@endif</p>
                        <p class="adventage">{{ $item->seedbonus ?? 0 }} BON points</p>
                        <p class="adventage">{{ $item->upload ?? 0 }} Upload</p>
                        <p class="adventage">{{ $item->invites ?? 0 }} invites</p>
                    </span>
                </div>
            </div>
        @endforeach
        <h2>Donate</h2>
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
                <label for="item">Item</label>
                <select name="item" class="form__select" id="btcpay-input-price" x-model="item">
                    <option hidden disabled selected value=""></option>
                    @foreach ($items as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
                <hr>
                <div class="label-text" style="padding: 5px;">
                    Please note: <br>
                    <u>In the next step</u>, we will ask to provide an email address and a username. You do not need to provide your real email address,<br>
                    but we need your aither username to map your donation to an account.
                </div>
                <hr>
                <button type="submit" x-show="item && coin && fiat">
                    submit
                </button>
            </form>
        </div>
    </div>
@endsection
