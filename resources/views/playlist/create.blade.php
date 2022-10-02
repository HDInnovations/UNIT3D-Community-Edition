@extends('layout.default')

@section('title')
    <title>{{ __('playlist.title') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Create Playlist">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('playlists.index') }}" class="breadcrumb__link">
            {{ __('playlist.playlists') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.new-adj') }}
    </li>
@endsection

@section('page', 'page__playlist--create')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('playlist.create') }}</h2>
        <div class="panel__body">
            <form
                class="form"
                method="POST"
                action="{{ route('playlists.store') }}"
                enctype="multipart/form-data"
            >
                @csrf
                <p class="form__group">
                    <input
                        id="name"
                        class="form__text"
                        type="text"
                        name="name"
                        placeholder=""
                        required
                        value="{{ old('name') }}"
                    >
                    <label class="form__label form__label--floating" for="name">
                        {{ __('playlist.title') }}
                    <label>
                </p>
                <p class="form__group">
                    <textarea
                        id="description"
                        class="form__textarea"
                        type="text"
                        name="description"
                        placeholder=""
                        required
                    >{{ old('description') }}</textarea>
                    <label class="form__label form__label--floating" for="description">
                        {{ __('playlist.desc') }}
                    </label>
                </p>
                <p class="form__group">
                    <label for="cover_image" class="form__label">
                        {{ __('playlist.cover') }}
                    </label>
                    <input
                        id="cover_image"
                        class="form__file"
                        type="file"
                        name="cover_image"
                    >
                </p>
                <p class="form__group">
                    <input type="hidden" name="is_private" value="0">
                    <input
                        id="is_private"
                        class="form__checkbox"
                        name="is_private"
                        type="checkbox"
                        value="1"
                    >
                    <label class="form__label" for="is_private">{{ __('playlist.is-private') }}</label>
                </p>
                <p class="form__group">
                    <button class="form__button form__button--filled">
                        {{ __('common.submit') }}
                    </button>
                </p>
            </form>
        </div>
    </section>
@endsection
