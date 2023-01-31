@extends('layout.default')

@section('title')
    <title>{{ $forum->name }} - {{ __('forum.forums') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('forum.display-forum') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('forums.index') }}" class="breadcrumb__link">
            {{ __('forum.forums') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $forum->name }}
    </li>
@endsection

@section('nav-tabs')
    @include('forum.buttons')
@endsection

@section('page', 'page__forum--category')

@section('main')
        <section class="panelV2">
            <h2 class="panel__heading">{{ $forum->description }}</h2>
            <ul class="topic-listings">
                @foreach ($topics as $topic)
                    <li class="topic-listings__item">
                        <x-forum.topic-listing :topic="$topic" />
                    </li>
                @endforeach
            </ul>
            {{ $topics->links('partials.pagination') }}
        </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('forum.category-quick-search') }}</h2>
        <div class="panel__body">
            <form class="form form--horizontal" method="GET" action="{{ route('forum_search_form') }}">
                <input type="hidden" name="sorting" value="updated_at">
                <input type="hidden" name="direction" value="desc">
                <input type="hidden" name="category" value="{{ $forum->id }}">
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
