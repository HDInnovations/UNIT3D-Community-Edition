@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.categories.index') }}" class="breadcrumb__link">
            {{ __('staff.torrent-categories') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.new-adj') }}
    </li>
@endsection

@section('page', 'page__category--create')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">
            {{ __('common.add') }}
            {{ trans_choice('common.a-an-art',false) }}
            {{ __('torrent.category') }}
        </h2>
        <div class="panel__body">
            <form
                class="form"
                method="POST"
                action="{{ route('staff.categories.store') }}"
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
                    >
                    <label class="form__label form__label--floating" for="name">{{ __('common.name') }}<label>
                </p>
                <p class="form__group">
                    <input
                        id="position"
                        class="form__text"
                        type="text"
                        name="position"
                        placeholder=""
                    >
                    <label class="form__label form__label--floating" for="positon">{{ __('common.position') }}</label>
                </p>
                <p class="form__group">
                    <input
                        id="position"
                        class="form__text"
                        type="text"
                        name="icon"
                        placeholder=""
                    >
                    <label class="form__label form__label--floating" for="icon">{{ __('common.icon') }} (FontAwesome)</label>
                </p>
                <p class="form__group">
                    <label for="image">
                        {{ __('common.select') }}
                        {{ trans_choice('common.a-an-art',false) }}
                        {{ __('common.image') }}
                        (If Not Using A FontAwesome Icon)
                    </label>
                    <input
                        id="file"
                        class="form__file"
                        type="file"
                        name="image"
                    >
                </p>
                <p class="form__group">
                    <input type="hidden" name="movie_meta" value="0">
                    <input
                        id="movie_meta"
                        class="form__checkbox"
                        type="checkbox"
                        name="movie_meta"
                        value="1"
                    >
                    <label for="movie_meta">Movie metadata?</label>
                </p>
                <p class="form__group">
                    <input type="hidden" name="tv_meta" value="0">
                    <input
                        id="tv_meta"
                        class="form__checkbox"
                        type="checkbox"
                        name="tv_meta"
                        value="1"
                    >
                    <label for="tv_meta">TV metadata?</label>
                </p>
                <p class="form__group">
                    <input type="hidden" name="game_meta" value="0">
                    <input
                        id="game_meta"
                        class="form__checkbox"
                        type="checkbox"
                        name="game_meta"
                        value="1"
                    >
                    <label for="game_meta">Game metadata?</label>
                </p>
                <p class="form__group">
                    <input type="hidden" name="music_meta" value="0">
                    <input
                        id="music_meta"
                        class="form__checkbox"
                        type="checkbox"
                        name="music_meta"
                        value="1"
                    >
                    <label for="music_meta">Music metadata?</label>
                </p>
                <p class="form__group">
                    <input type="hidden" name="no_meta" value="0">
                    <input
                        class="form__checkbox"
                        type="checkbox"
                        name="no_meta"
                        value="1"
                    >
                    <label for="no_meta">No metadata?</label>
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
