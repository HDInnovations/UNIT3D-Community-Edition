@extends('layout.default')

@section('title')
    <title>{{ __('forum.create-new-topic') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ $forum->name . ' - ' . __('forum.create-new-topic') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('forums.index') }}" class="breadcrumb__link">
            {{ __('forum.forums') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('forums.show', ['id' => $forum->id]) }}" class="breadcrumb__link">
            {{ $forum->name }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.new-adj') }}
    </li>
@endsection

@section('nav-tabs')
    @include('forum.buttons')
@endsection

@section('content')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('forum.create-new-topic') }}</h2>
        <div class="panel__body">
            <form class="form" method="POST" action="{{ route('forum_new_topic', ['id' => $forum->id]) }}">
                @csrf
                <p class="form__group">
                    <input
                        id="input-thread-title"
                        class="form__text"
                        maxlength="75"
                        name="title"
                        placeholder=""
                        type="text"
                    >
                    <label class="form__label form__label--floating" for="input-thread-title">
                        {{ __('forum.topic-title') }}
                    </label>
                </p>
                @livewire('bbcode-input', ['name' => 'content', 'label' => __('forum.post') ])
                <button class="form__button form__button--filled">
                    {{ __('forum.send-new-topic') }}
                </button>
            </form>
        </div>
    </section>
@endsection
