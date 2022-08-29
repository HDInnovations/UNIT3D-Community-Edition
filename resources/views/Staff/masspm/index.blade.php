@extends('layout.default')

@section('title')
    <title>{{ __('staff.mass-pm') }} - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('staff.mass-pm') }} - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('staff.mass-pm') }}
    </li>
@endsection

@section('page', 'page__mass-pm--index')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('staff.mass-pm') }}</h2>
        <div class="panel__body">
            <form class="form" action="{{ route('staff.mass-pm.store') }}" method="POST" x-data>
                @csrf
                <p class="form__group">
                    <input
                        id="subject"
                        class="form__text"
                        minlength="5"
                        name="subject"
                        type="text"
                        required
                    >
                    <label class="form__label form__label--floating" for="subject">
                        {{ __('pm.subject') }}
                    </label>
                </p>
                @livewire('bbcode-input', ['name' => 'message', 'label' => __('pm.message'), 'required' => true])
                <p class="form__group">
                    <button 
                        x-on:click.prevent="Swal.fire({
                            title: 'Are you sure?',
                            text: 'Are you sure you want to send this private message to every user on the site?',
                            icon: 'warning',
                            showConfirmButton: true,
                            showCancelButton: true,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $root.submit();
                            }
                        })"
                        class="form__button form__button--filled"
                    >
                        {{ __('pm.send') }}
                    </button>
                </p>
            </form>
        </div>
    </section>
@endsection
