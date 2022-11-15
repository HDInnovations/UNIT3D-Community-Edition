@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('inbox') }}" class="breadcrumb__link">
            {{ __('pm.messages') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.new-adj') }}
    </li>
@endsection

@section('nav-tabs')
    @include('partials.pmmenu')
@endsection

@section('page', 'page__pm--send')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('pm.new') }}</h2>
        <div class="panel__body">
            <form class="form" method="POST" action="{{ route('send-pm') }}">
                @csrf
                <p class="form__group">
                    <input
                        id="receiver_id"
                        class="form__text"
                        name="receiver_id"
                        {{ request()->has('username') ? 'readonly' : 'required' }}
                        value="{{request()->has('username') ? request()->get('username') : '' }}"
                    >
                    <label for="receiver_id" class="form__label form__label--floating">
                        {{ __('common.username') }}
                    </label>
                </p>
                <p class="form__group">
                    <input
                        id="subject"
                        class="form__text"
                        name="subject"
                        required
                    />
                    <label for="subject" class="form__label form__label--floating">
                        {{ __('pm.enter-subject') }}
                    </label>
                </p>
                @livewire('bbcode-input', ['name' => 'message', 'label' => __('pm.reply'), 'required' => true])
                <p class="form__group">
                    <button class="form__button form__button--filled">
                        {{ __('pm.reply') }}
                    </button>
                </p>
            </form>
        </div>
    </section>
@endsection
