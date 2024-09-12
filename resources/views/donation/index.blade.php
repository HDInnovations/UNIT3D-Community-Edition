@extends('layout.default')

@section('title')
    <title>Donate - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Donate" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">Donate</li>
@endsection

@section('content')
    <section x-data class="panelV2">
        <h2 class="panel__heading">Support {{ config('other.title') }}</h2>
        <div class="panel__body bbcode-rendered">
            <p>{{ config('donation.description') }}</p>
            <table>
                <tbody>
                    <tr>
                        @foreach ($packages as $package)
                            <td style="text-align: center">
                                <span>
                                    @if ($package->donor_value === null)
                                        Lifetime
                                    @else
                                        {{ $package->donor_value }} Days
                                    @endif
                                    --- {{ $package->cost }} {{ config('donation.currency') }}
                                </span>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($packages as $package)
                            <td>
                                <ul>
                                    @if($package->donor_value === null)
                                        <li>Unlimited Download Slots</li>
                                    @endif
                                    @if($package->donor_value === null)
                                         <li>Custom User Icon</li>
                                    @endif
                                    <li>Global Freeleech</li>
                                    <li>Immunity To Automated Warnings (Don't Abuse)</li>
                                    <li
                                        style="
                                            background-image: url(/img/sparkels.gif);
                                            width: auto;
                                        "
                                    >
                                        Sparkle Effect On Username
                                    </li>
                                    <li>
                                        Donor Star By Username
                                        @if ($package->donor_value === null)
                                            <i
                                                id="lifeline"
                                                class="fal fa-star"
                                                title="Lifetime Donor"
                                            ></i>
                                        @else
                                            <i class="fal fa-star text-gold" title="Donor"></i>
                                        @endif
                                    </li>
                                    <li>
                                        Warm Fuzzy Feeling By Supporting
                                        {{ config('other.title') }}
                                    </li>
                                    @if ($package->upload_value !== null)
                                        <li>{{ App\Helpers\StringHelper::formatBytes($package->upload_value) }}  Upload Credit</li>
                                    @endif

                                    @if ($package->bonus_value !== null)
                                        <li>{{ number_format($package->bonus_value) }} Bonus Points</li>
                                    @endif

                                    @if ($package->invite_value !== null)
                                        <li>{{ $package->invite_value }} Invites</li>
                                    @endif
                                </ul>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($packages as $package)
                            <td style="text-align: center">
                                <button
                                    x-on:click.stop="$refs.dialog{{ $package->id }}.showModal()"
                                    class="form__button form__button--filled form__button--centered"
                                >
                                    Donate
                                </button>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

        @foreach ($packages as $package)
            <dialog class="dialog" x-ref="dialog{{ $package->id }}">
                <h4 class="dialog__heading">Donate $ {{ $package->cost }} USD</h4>
                <form
                    class="dialog__form"
                    method="POST"
                    action="{{ route('donations.store') }}"
                    x-on:click.outside="$refs.dialog{{ $package->id }}.close()"
                >
                    @csrf
                    <a href="#">To make a donation you must complete the following steps:</a>
                    <div>
                        @foreach ($gateways->sortBy('position') as $gateway)
                            <div class="label label-default" style="display: inline-block">
                                {{ $gateway->name }}
                            </div>
                            <input
                                class="form__text"
                                type="text"
                                disabled
                                value="{{ $gateway->address }}"
                            />
                        @endforeach

                        2: Send
                        <strong>$ {{ $package->cost }} {{ config('donation.currency') }}</strong>
                        to gateway of your choice.
                        <br />
                        3: Take note of the tx hash, receipt number, etc and input it below.
                        <br />
                    </div>
                    <span class="badge-extra text-center" style="font-size: 18px">Info:</span>
                    <div>
                        <div>
                            Amount:
                            <br />
                            <input
                                type="number"
                                name="amount"
                                value="{{ $package->cost }}"
                                placerholder=""
                                class="form__text"
                                disabled
                            />
                        </div>
                        <div>
                            Transaction Proof:
                            <br />
                            <input
                                type="text"
                                name="transaction"
                                value="tx hash, receipt number, etc."
                                class="form__text"
                            />
                        </div>
                    </div>
                    <span>* Transactions may take up to 48 hours to process.</span>
                    <p class="form__group">
                        <input type="hidden" name="package_id" value="{{ $package->id }}" />
                        <button class="form__button form__button--filled">Donate</button>
                        <button
                            formmethod="dialog"
                            formnovalidate
                            class="form__button form__button--outlined"
                        >
                            {{ __('common.cancel') }}
                        </button>
                    </p>
                </form>
            </dialog>
        @endforeach
    </section>
@endsection
