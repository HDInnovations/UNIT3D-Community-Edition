@extends('layout.default')

@section('title')
    <title>{{ __('forum.forums') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ config('other.title') }} - {{ __('forum.forums') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('forum.forums') }}
    </li>
@endsection

@section('nav-tabs')
    @include('forum.buttons')
@endsection

@section('page', 'page__forums--index')

@section('main')
    @foreach ($categories as $category)
        @if ($category->getPermission() != null && $category->getPermission()->show_forum == true &&
            $category->getForumsInCategory()->count() > 0)
            <section class="panelV2">
                <h2 class="panel__heading">
                    <a class="panel__header-link" href="{{ route('forums.categories.show', ['id' => $category->id]) }}">
                        {{ $category->name }}
                    </a>
                </h2>
                <ul class="subforum-listings">
                    @foreach ($category->getForumsInCategory()->sortBy('position') as $subforum)
                        @if ($subforum->getPermission() != null && $subforum->getPermission()->show_forum == true)
                            <li class="subforum-listings__item">
                                <x-forum.subforum-listing :subforum="$subforum" />
                            </li>
                        @endif
                    @endforeach
                </ul>
            </section>
        @endif
    @endforeach
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
                        type="text"
                        value="{{ isset($params) && is_array($params) && array_key_exists('name', $params) ? $params['name'] : '' }}"
                        placeholder=""
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
