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

@section('page', 'page__forum--display')

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ $forum->description }}</h2>
            <div class="panel__actions">
                @if ($category->getPermission()->start_topic == true)
                    <a
                        href="{{ route('forum_new_topic_form', ['id' => $forum->id]) }}"
                        class="panel__action form__button form__button--text"
                    >
                        {{ __('forum.create-new-topic') }}
                    </a>
                @endif
                @if ($category->getPermission()->show_forum == true)
                    @if (auth()->user()->subscriptions()->ofForum($forum->id)->exists())
                        <form
                            class="panel__action" 
                            action="{{ route('unsubscribe_forum', ['forum' => $forum->id, 'route' => 'forum']) }}"
                            method="POST"
                        >
                            @csrf
                            <button class="panel__action form__button form__button--text">
                                <i class="{{ config('other.font-awesome') }} fa-bell-slash"></i>
                                {{ __('forum.unsubscribe') }}
                            </button>
                        </form>
                    @else
                        <form
                            class="panel__action"
                            action="{{ route('subscribe_forum', ['forum' => $forum->id, 'route' => 'forum']) }}"
                            method="POST"
                        >
                            @csrf
                            <button class="panel__action form__button form__button--text">
                                <i class="{{ config('other.font-awesome') }} fa-bell"></i>
                                {{ __('forum.subscribe') }}
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </header>
        <ul class="topic-listings">
            @foreach($topics as $topic)
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
            <form class="form form--horizontal" method="GET" action="{{ route('forum_search_form') }}" class="form-inline">
                <input type="hidden" name="sorting" value="created_at">
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
                    <label class="form__label form__label--floating" for="name">{{ __('forum.topic-name') }}</label>
                </p>
                <button class="form__button form__button--filled">
                    {{ __('common.search') }}
                </button>
            </form>
        </div>
    </section>
    @include('forum.stats')
@endsection
