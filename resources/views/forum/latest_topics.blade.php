@extends('layout.default')

@section('title')
    <title>{{ __('common.latest-topics') }} - {{ __('forum.forums') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Forum {{ __('common.latest-topics') }}">
@endsection
@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('forums.index') }}" class="breadcrumb__link">
            {{ __('forum.forums') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.latest-topics') }}
    </li>
@endsection

@section('nav-tabs')
    @include('forum.buttons')
@endsection

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.latest-topics') }}</h2>
        <ul class="topic-listings">
            @foreach ($results as $topic)
                <li class="topic-listings__item">
                    <x-forum.topic-listing :topic="$topic" />
                </li>
            @endforeach
        </ul>
        {{ $results->links('partials.pagination') }}
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('forum.topic-quick-search') }}</h2>
        <div class="panel__body">
            <form class="form form--horizontal" method="GET" action="{{ route('forum_search_form') }}">
                <input type="hidden" name="sorting" value="created_at">
                <input type="hidden" name="direction" value="desc">
                <p class="form__group">
                    <input
                        id="name"
                        class="form__text"
                        name="name"
                        placeholder=""
                        type="text"
                        value="{{ isset($params) && is_array($params) && array_key_exists('name', $params) ? $params['name'] : '' }}"
                    >
                    <label class="form__label form__label--floating" for="name">
                        {{ __('forum.topic-name') }}
                    </label>
                </p>
                <button class="form__button form__button--filled">
                    {{ __('common.search') }}
                </button>
            </form>
        </div>
    </section>
    @include('forum.stats')
@endsection