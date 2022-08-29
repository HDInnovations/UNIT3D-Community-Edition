@extends('layout.default')

@section('title')
    <title>Application - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Application - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.applications.index') }}" class="breadcrumb__link">
            {{ __('staff.applications') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $application->id }}
    </li>
@endsection

@section('page', 'page__application--show')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.image') }}</h2>
        <div class="panel__body">
            <ul>
                @foreach($application->imageProofs as $img_proof)
                    <li>
                        <a href="{{ $img_proof->image }}" target="_blank">
                            {{ __('staff.application-image-proofs') }} {{ $loop->iteration }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">
            {{ __('user.profile') }} {{ __('staff.links') }}
        </h2>
        <div class="panel__body">
            <ul>
                @foreach($application->urlProofs as $url_proof)
                    <li>
                        <a href="{{ $url_proof->url }}" target="_blank">
                            {{ $url_proof->url }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">
            {{ __('staff.application-referrer') }}
        </h2>
        <div class="panel__body">{{ $application->referrer }}</div>
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.info') }}</h2>
        <dl class="key-value">
            <dt>{{ __('common.email') }}</dt>
            <dd>{{ $application->email }}</dd>
            <dt>{{ __('staff.application-type') }}</dt>
            <dd>{{ $application->type }}</dd>
            <dt>{{ __('common.created_at') }}</dt>
            <dd>
                <time datetime="{{ $application->created_at }}" title="{{ $application->created_at }}">
                    {{ $application->created_at->diffForHumans() }}
                </time>
            </dd>
            <dt>{{ __('common.status') }}</dt>
            <dd>
                @if ($application->status == 0)
                    <span class="application--pending">{{ __('request.pending') }}</span>
                @elseif ($application->status == 1)
                    <span class="application--approved">{{ __('request.approve') }}</span>
                @else
                    <span class="application--rejected">{{ __('request.reject') }}</span>
                @endif
            </dd>
        </dl>
    </section>
    <section class="panelV2">
        @if($application->status)
            <h2 class="panel__heading">{{ __('common.moderated-by') }}</h2>
            <div class="panel__body">
                <x-user_tag :anon="false" :user="$application->moderated" />
            </div>
        @else
            <h2 class="panel__heading">{{ __('common.action') }}</h2>
            <div class="panel__body">
                    <div x-data="{ open: false }">
                        <p class="form__group form__group--horizontal">
                            <button class="form__button form__button--filled" x-on:click.stop="open = true; $refs.dialog.showModal();">
                                <i class="{{ config('other.font-awesome') }} fa-check"></i>
                                {{ __('request.approve') }}
                            </button>
                        </p>
                        <dialog class="dialog" x-ref="dialog" x-show="open" x-cloak>
                            <h3 class="dialog__heading">
                                {{ __('request.approve') }}
                                {{ __('common.this') }}
                                {{ __('staff.application') }}
                            </h3>
                            <form
                                class="dialog__form"
                                method="POST"
                                action="{{ route('staff.applications.approve', ['id' => $application->id]) }}"
                                x-on:click.outside="open = false; $refs.dialog.close();"
                            >
                                @csrf
                                <input
                                    id="email"
                                    name="email"
                                    type="hidden"
                                    value="{{ $application->email }}"
                                >
                                <p class="form__group">
                                    <textarea id="approve" class="form__textarea" name="approve" placeholder="">Application Approved!</textarea>
                                    <label class="form__label form__label--floating" for="approve">Invitation Message</label>
                                </p>
                                <p class="form__group">
                                    <button class="form__button form__button--filled">
                                        {{ __('request.approve') }}
                                    </button>
                                    <button x-on:click.prevent="open = false; $refs.dialog.close();" class="form__button form__button--outlined">
                                        {{ __('common.cancel') }}
                                    </button>
                                </p>
                            </form>
                        </dialog>
                    </div>
                    <div x-data="{ open: false }">
                        <p class="form__group form__group--horizontal">
                            <button class="form__button form__button--filled" x-on:click.stop="open = true; $refs.dialog.showModal();">
                                <i class="{{ config('other.font-awesome') }} fa-times"></i>
                                {{ __('request.reject') }}
                            </button>
                        </p>
                        <dialog class="dialog" x-ref="dialog" x-show="open" x-cloak>
                            <h3 class="dialog__heading">
                                {{ __('request.reject') }}
                                {{ __('common.this') }}
                                {{ __('staff.application') }}
                            </h3>
                            <form
                                class="dialog__form"
                                method="POST"
                                action="{{ route('staff.applications.reject', ['id' => $application->id]) }}"
                                x-on:click.outside="open = false; $refs.dialog.close();"
                            >
                                @csrf
                                <input
                                    id="email"
                                    name="email"
                                    type="hidden"
                                    value="{{ $application->email }}"
                                >
                                <p class="form__group">
                                    <textarea
                                        id="message"
                                        class="form__textarea"
                                        name="deny"
                                        required
                                    >Insufficient Proofs.</textarea>
                                    <label class="form__label form__label--floating for="message">Rejection Message</label>
                                </p>
                                <p class="form__group">
                                    <button class="form__button form__button--filled">
                                        {{ __('request.reject') }}
                                    </button>
                                    <button x-on:click.prevent="open = false; $refs.dialog.close();" class="form__button form__button--outlined">
                                        {{ __('common.cancel') }}
                                    </button>
                                </p>
                            </form>
                        </dialog>
                    </div>
                </div>
            </div>
        @endif
    </section>
@endsection
