@extends('layout.default')

@section('title')
    <title>{{ __('common.search') }} - {{ __('forum.forums') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Forum Search">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('forums.index') }}" class="breadcrumb__link">
            {{ __('forum.forums') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.search') }}
    </li>
@endsection

@section('nav-tabs')
    @include('forum.buttons')
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('forum.forums-topic-search') }}</h2>
        <div class="panel__body">
            <form class="form" method="GET" action="{{ route('forum_search_form') }}">
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
                        {{ __('forum.topic') }}
                    </label>
                </p>
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
                        {{ __('forum.post') }}
                    </label>
                </p>
                <p class="form__group">
                    <select
                        name="category"
                        id="category"
                        class="form__select"
                        required
                    >
                        <option value="0">{{ __('forum.select-all-forum') }}</option>
                        @foreach ($categories as $category)
                            @if ($category->getPermission() != null && $category->getPermission()->show_forum == true && $category->getForumsInCategory()->count() > 0)
                                <option
                                    value="{{ $category->id }}"
                                    @selected(isset($params) && is_array($params) && array_key_exists('category', $params) && $params['category'] == $category->id)
                                >
                                    {{ $category->name }}
                                </option>
                                @foreach ($category->getForumsInCategory()->sortBy('position') as $categoryChild)
                                    @if ($categoryChild->getPermission() != null && $categoryChild->getPermission()->show_forum == true)
                                        <option
                                            value="{{ $categoryChild->id }}"
                                            @selected(isset($params) && is_array($params) && array_key_exists('category', $params) && $params['category'] == $categoryChild->id)
                                        >
                                            &raquo; {{ $categoryChild->name }}
                                        </option>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                    <label class="form__label form__label--floating" for="category">
                        {{ __('common.forum') }}
                    </label>
                </p>
                <div class="form__group--horizontal">
                    <div class="form__group">
                        <fieldset class="form__fieldset">
                            <legend class="form__legend">{{ __('forum.label') }}</legend>
                            <p class="form__group">
                                <input
                                    id="implemented"
                                    class="form__checkbox"
                                    type="checkbox"
                                    value="1"
                                    name="implemented"
                                    @checked(isset($params) && is_array($params) && array_key_exists('implemented', $params) && $params['implemented'])    
                                >
                                <label class="form__label" for="implemented">
                                    <i class="{{ config('other.font-awesome') }} fa-check text-purple"></i>
                                    {{ __('forum.implemented') }}
                                </label>
                            </p>
                            <p class="form__group">
                                <input
                                    id="approved"
                                    class="form__checkbox"
                                    type="checkbox"
                                    value="1"
                                    name="approved"
                                    @checked(isset($params) && is_array($params) && array_key_exists('approved', $params) && $params['approved'])    
                                >
                                <label class="form__label" for="approved">
                                    <i class="{{ config('other.font-awesome') }} fa-tag text-green"></i>
                                    {{ __('forum.approved') }}
                                </label>
                            </p>
                            <p class="form__group">
                                <input
                                    id="denied"
                                    class="form__checkbox"
                                    type="checkbox"
                                    value="1"
                                    name="denied"
                                    @checked(isset($params) && is_array($params) && array_key_exists('denied', $params) && $params['denied'])    
                                >
                                <label class="form__label" for="denied">
                                    <i class="{{ config('other.font-awesome') }} fa-tag text-red"></i>
                                    {{ __('forum.denied') }}
                                </label>
                            </p>
                            <p class="form__group">
                                <input
                                    id="solved"
                                    class="form__checkbox"
                                    type="checkbox"
                                    value="1"
                                    name="solved"
                                    @checked(isset($params) && is_array($params) && array_key_exists('solved', $params) && $params['solved'])    
                                >
                                <label class="form__label" for="solved">
                                    <i class="{{ config('other.font-awesome') }} fa-thumbs-up text-green"></i>
                                    {{ __('forum.solved') }}
                                </label>
                            </p>
                            <p class="form__group">
                                <input
                                    id="invalid"
                                    class="form__checkbox"
                                    type="checkbox"
                                    value="1"
                                    name="invalid"
                                    @checked(isset($params) && is_array($params) && array_key_exists('invalid', $params) && $params['invalid'])    
                                >
                                <label class="form__label" for="invalid">
                                    <i class="{{ config('other.font-awesome') }} fa-thumbs-down text-red"></i>
                                    {{ __('forum.invalid') }}
                                </label>
                            </p>
                            <p class="form__group">
                                <input
                                    id="bug"
                                    class="form__checkbox"
                                    type="checkbox"
                                    value="1"
                                    name="bug"
                                    @checked(isset($params) && is_array($params) && array_key_exists('bug', $params) && $params['bug'])    
                                >
                                <label class="form__label" for="bug">
                                    <i class="{{ config('other.font-awesome') }} fa-bug text-red"></i>
                                    {{ __('forum.bug') }}
                                </label>
                            </p>
                            <p class="form__group">
                                <input
                                    id="suggestion"
                                    class="form__checkbox"
                                    type="checkbox"
                                    value="1"
                                    name="suggestion"
                                    @checked(isset($params) && is_array($params) && array_key_exists('suggestion', $params) && $params['suggestion'])    
                                >
                                <label class="form__label" for="suggestion">
                                    <i class="{{ config('other.font-awesome') }} fa-info text-blue"></i>
                                    {{ __('forum.suggestion') }}
                                </label>
                            </p>
                        </fieldset>
                    </div>
                    <div class="form__group">
                        <fieldset class="form__fieldset">
                            <legend class="form__legend">{{ __('forum.state') }}</legend>
                            <p class="form__group">
                                <input
                                    id="open"
                                    class="form__checkbox"
                                    type="checkbox"
                                    value="1"
                                    name="open"
                                    @checked(isset($params) && is_array($params) && array_key_exists('open', $params) && $params['open'])    
                                >
                                <label class="form__label" for="open">
                                    <i class="{{ config('other.font-awesome') }} fa-lock-open text-green"></i>
                                    {{ __('forum.open') }}
                                </label>
                            </p>
                            <p class="form__group">
                                <input
                                    id="closed"
                                    class="form__checkbox"
                                    type="checkbox"
                                    value="1"
                                    name="closed"
                                    @checked(isset($params) && is_array($params) && array_key_exists('closed', $params) && $params['closed'])    
                                >
                                <label class="form__label" for="closed">
                                    <i class="{{ config('other.font-awesome') }} fa-lock text-red"></i>
                                    {{ __('forum.closed') }}
                                </label>
                            </p>
                        </fieldset>
                    </div>
                    <div class="form__group">
                        <fieldset class="form__fieldset">
                            <legend class="form__legend">{{ __('forum.activity') }}</legend>
                            <p class="form__group">
                                <input
                                    id="subscribed"
                                    class="form__checkbox"
                                    type="checkbox"
                                    value="1"
                                    name="subscribed"
                                    @checked(isset($params) && is_array($params) && array_key_exists('subscribed', $params) && $params['subscribed'])    
                                >
                                <label class="form__label" for="subscribed">
                                    <i class="{{ config('other.font-awesome') }} fa-bell text-green"></i>
                                    {{ __('forum.subscribed') }}
                                </label>
                            </p>
                            <p class="form__group">
                                <input
                                    id="notsubscribed"
                                    class="form__checkbox"
                                    type="checkbox"
                                    value="1"
                                    name="notsubscribed"
                                    @checked(isset($params) && is_array($params) && array_key_exists('notsubscribed', $params) && $params['notsubscribed'])    
                                >
                                <label class="form__label" for="notsubscribed">
                                    <i class="{{ config('other.font-awesome') }} fa-bell-slash text-red"></i>
                                    {{ __('forum.not-subscribed') }}
                                </label>
                            </p>
                        </fieldset>
                    </div>
                </div>
                <p class="form__group">
                    <select
                        id="sorting"
                        class="form__select"
                        name="sorting"
                        required
                    >
                        <option
                            value="updated_at"
                            @selected(isset($params) && is_array($params) && array_key_exists('sorting', $params) && $params['sorting'] == 'updated_at')
                        >
                            {{ __('forum.updated-at') }}
                        </option>
                        <option
                            value="created_at"
                            @selected(isset($params) && is_array($params) && array_key_exists('sorting', $params) && $params['sorting'] == 'created_at')
                        >
                            {{ __('forum.created-at') }}
                        </option>
                    </select>
                    <label class="form__label form__label--floating" for="sorting">
                        {{ __('common.sort') }}
                    </label>
                </p>
                <p class="form__group">
                    <select
                        id="direction"
                        class="form__select"
                        name="direction"
                        required
                    >
                        <option
                            value="desc"
                            @selected(isset($params) && is_array($params) && array_key_exists('direction', $params) && $params['direction'] == 'desc')
                        >
                            {{ __('common.descending') }}
                        </option>
                        <option
                            value="asc"
                            @selected(isset($params) && is_array($params) && array_key_exists('direction', $params) && $params['direction'] == 'asc')
                        >
                            {{ __('common.ascending') }}
                        </option>
                    </select>
                    <label class="form__label form__label--floating" for="direction">
                        {{ __('common.direction') }}
                    </label>
                </p>
                <p class="form__group--horizontal">
                    <button class="form__button form__button--filled">
                        {{ __('common.search') }}
                    </button>
                </p>
            </form>
        </div>
    </section>
@endsection

@section('main')
    <h2 class="panel__heading">{{ __('common.search-results') }}</h2>
    <ul class="topic-posts">
        @foreach($results as $post)
            <li class="post-listings__item">
                <x-forum.post :post="$post" />
            </li>
        @endforeach
    </ul>
    {{ $results->links('partials.pagination') }}
@endsection
