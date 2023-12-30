@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a
            href="{{ route('users.sent_messages.index', ['user' => $user]) }}"
            class="breadcrumb__link"
        >
            {{ __('pm.messages') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $privateMessage->subject }}
    </li>
@endsection

@section('nav-tabs')
    @include('partials.pmmenu')
@endsection

@section('page', 'page__pm--message')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">Re: {{ $privateMessage->subject }}</h2>
        <div class="panel__body bbcode-rendered">
            @joypixels($privateMessage->getMessageHtml())
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('pm.reply') }}</h2>
        <div class="panel__body">
            <form
                method="POST"
                action="{{ route('users.sent_messages.update', ['user' => $user, 'sentPrivateMessage' => $privateMessage]) }}"
            >
                @csrf
                @method('PATCH')
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
                <x-user_tag :user="$privateMessage->sender" :anon="false" />
            </dd>
            <dt>{{ __('pm.to') }}</dt>
            <dd>
                <x-user_tag :user="$privateMessage->receiver" :anon="false" />
            </dd>
            <dt>{{ __('pm.sent') }}</dt>
            <dd>
                <time
                    datetime="{{ $privateMessage->created_at }}"
                    title="{{ $privateMessage->created_at }}"
                >
                    {{ $privateMessage->created_at->diffForHumans() }}
                </time>
            </dd>
        </dl>
    </section>
@endsection
