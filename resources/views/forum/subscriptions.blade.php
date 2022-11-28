@extends('layout.default')

@section('title')
    <title>{{ __('common.subscriptions') }} - {{ __('forum.forums') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Forum {{ __('common.subscriptions') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('forums.index') }}" class="breadcrumb__link">
            {{ __('forum.forums') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.subscriptions') }}
    </li>
@endsection

@section('nav-tabs')
    @include('forum.buttons')
@endsection

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('forum.forums') }}</h2>
        @foreach ($results->whereIn('id', $forum_neos) as $subforum)
                <x-forum.subforum-listing :subforum="$subforum" />
        @endforeach
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('forum.topics') }}</h2>
        @foreach ($results as $result)
            @foreach($result->subscription_topics as $topic)
                <x-forum.topic-listing :topic="$topic" />
            @endforeach
        @endforeach
    </section>
    {{ $results->links('partials.pagination') }}
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('forum.post-quick-search') }}</h2>
        <div class="panel__body">
            <form class="form form--horizontal" method="GET" action="{{ route('forum_search_form') }}">
                <input type="hidden" name="sorting" value="created_at">
                <input type="hidden" name="direction" value="desc">
                <p class="form__group">
                    <input
                        id="body"
                        class="form__text"
                        name="body"
                        placeholder=""
                        type="text"
                        value="{{ isset($params) && is_array($params) && array_key_exists('body', $params) ? $params['body'] : '' }}"
                    >
                    <label class="form__label form__label--floating" for="body">
                        {{ __('forum.forums-post-search') }}
                    </label>
                </p>
                <button type="submit" class="form__button form__button--filled">
                    {{ __('common.search') }}
                </button>
            </form>
        </div>
    </section>
    @include('forum.stats')
@endsection
