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
    </div>

    <h2>Donate</h2>
    <form
        class="form"
        method="POST"
        action="{{ route('create_payment') }}"
        enctype="multipart/form-data"
        x-data
    >
        @csrf
        <p class="form__group">
            <select name="coin" id="coin" class="form__select" x-data="{ selected: '' }" x-model="selected" x-bind:class="selected === '' ? 'form__select--default' : ''">
                <option hidden="" disabled selected value=""></option>
                <option value="BTC">Bitcoin</option>
                <option value="XMR">Monero</option>
            </select>
            <label class="form__label form__label--floating" for="coin">Coin</label>
        </p>

        <p class="form__group">
            <select name="fiat" id="select" class="form__select" x-data="{ selected: '' }" x-model="selected" x-bind:class="selected === '' ? 'form__select--default' : ''">
                <option hidden="" disabled selected value=""></option>
                <option value="USD">USD</option>
                <option value="GBP">GBP</option>
                <option value="EUR">EUR</option>
            </select>
            <label class="form__label form__label--floating" for="fiat">Fiat Currency</label>
        </p>

        <p class="form__group">
            <select name="item" id="item" class="form__select" x-data="{ selected: '' }" x-model="selected" x-bind:class="selected === '' ? 'form__select--default' : ''">
                <option hidden="" disabled selected value=""></option>
                @foreach ($items as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
            <label class="form__label form__label--floating" for="item">Item</label>
        </p>

        @error('error')
            <span class="form__hint">{{ $error }}</span>
        @enderror

        <button type="submit">
            Submit
        </button>
    </form>
@endsection
