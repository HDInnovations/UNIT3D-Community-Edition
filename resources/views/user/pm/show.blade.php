@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('inbox') }}" class="breadcrumb__link">
            {{ __('pm.messages') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $pm->subject }}
    </li>
@endsection

@section('nav-tabs')
    @include('partials.pmmenu')
@endsection

@section('page', 'page__pm--message')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">Re: {{ $pm->subject }}</h2>
        <div class="panel__body">
            @joypixels($pm->getMessageHtml())
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('pm.reply') }}</h2>
        <div class="panel__body">
            <form method="POST" action="{{ route('reply-pm', ['id' => $pm->id]) }}">
                @csrf
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

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.info') }}</h2>
        <dl class="key-value">
            <dt>{{ __('pm.from') }}</dt>
            <dd>
                <x-user_tag :user="$pm->sender" :anon="false" />
            </dd>
            <dt>{{ __('pm.to') }}</dt>
            <dd>
                <x-user_tag :user="$pm->receiver" :anon="false" />
            </dd>
            <dt>{{ __('pm.sent') }}</dt>
            <dd>
                <time datetime="{{ $pm->created_at }}" title="{{ $pm->created_at }}">
                    {{ $pm->created_at->diffForHumans() }}
                </time>
            </dd>
        </dl>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.actions') }}</h2>
        <div class="panel__body">
            <form action="{{ route('delete-pm', ['id' => $pm->id]) }}" method="POST">
                @csrf
                <p class="form__group form__group--horizontal">
                    <button class="form__button form__button--filled">
                        <i class="{{ config('other.font-awesome') }} fa-trash"></i>
                        {{ __('pm.delete') }}
                    </button>
                </p>
            </form>
        </div>
    </section>
@endsection
