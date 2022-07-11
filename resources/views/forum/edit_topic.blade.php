@extends('layout.default')

@section('title')
    <title>{{ __('forum.create-new-topic') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('forum.edit-topic') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('forums.index') }}" class="breadcrumb__link">
            {{ __('forum.forums') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('forum_topic', ['id' => $topic->id]) }}" class="breadcrumb__link">
            {{ $topic->name }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.edit') }}
    </li>
@endsection

@section('nav-tabs')
    @include('forum.buttons')
@endsection

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('forum.edit-topic') }}</h2>
        <div class="panel__body">
            <form class="form" method="POST" action="{{ route('forum_edit_topic', ['id' => $topic->id]) }}">
                @csrf
                <p class="form__group">
                    <input
                        id="forum_name"
                        class="form__text"
                        maxlength="75"
                        name="name"
                        placeholder=""
                        required
                        type="text"
                        value="{{ $topic->name }}"
                    >
                    <label class="form__label form__label--floating" for="forum_name">
                        {{ __('forum.topic-name') }}
                    </label>
                </p>
                <p class="form__group">
                    <select id="forum_id" name="forum_id" class="form__select">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected($topic->forum_id === $category->id )>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <label class="form__label form__label--floating" for="forum_id">{{ __('forum.forum') }}</label>
                </p>
                <button class="form__button form__button--filled" name="post" value="true" id="post">
                    {{ __('forum.edit-topic') }}
                </button>
            </form>
        </div>
    </section>
@endsection
