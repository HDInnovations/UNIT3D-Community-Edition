@extends('layout.default')

@section('title')
    <title>{{ __('common.upload') }} {{ __('common.subtitle') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('subtitles.index') }}" class="breadcrumb__link">
            {{ __('common.subtitles') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.new-adj') }}
    </li>
@endsection

@section('page', 'page__subtitle--create')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">
            {{ __('common.upload') }} {{ __('common.subtitle') }}
        </h2>
        <div class="panel__body">
            <form
                id="form_upload_subtitle"
                class="form"
                action="{{ route('subtitles.store') }}"
                enctype="multipart/form-data"
                method="POST"
            >
                @csrf
                <input name="torrent_id" type="hidden" value="{{ $torrent->id }}">
                <input name="torrent_name" type="hidden" value="{{ $torrent->name }}">
                <p class="form__group">
                    <label for="subtitle_file" class="form__label">
                        {{ __('subtitle.subtitle-file') }} ({{ __('subtitle.subtitle-file-types') }})
                    </label>
                    <input
                        id="subtitle_file"
                        class="form__file"
                        accept=".srt,.ass,.sup,.zip"
                        name="subtitle_file"
                        required
                        type="file"
                    >
                </p>
                <p class="form__group">
                    <select
                        id="language_id"
                        class="form__select"
                        name="language_id"
                        required
                    >
                        <option hidden disabled selected value=""></option>
                        @foreach ($media_languages as $media_language)
                            <option value="{{ $media_language->id }}" @selected(old('media_language') == $media_language->id)>
                                {{ $media_language->code }}
                            </option>
                        @endforeach
                    </select>
                    <label class="form__label form__label--floating" for="language_id">
                        {{ __('common.language') }}
                    </label>
                </p>
                <p class="form__group">
                    <input
                        type="text"
                        name="note"
                        id="note"
                        class="form__text"
                        placeholder=""
                    >
                    <label class="form__label form__label--floating" for="note">
                        {{ __('subtitle.note') }} ({{ __('subtitle.note-help') }})
                    </label>
                </p>
                <p class="form__group">
                    <input type="hidden" name="anonymous" value="0">
                    <input
                        id="anonymous"
                        class="form__checkbox"
                        name="anonymous"
                        type="checkbox"
                        value="1"
                        @checked(old('anonymous'))
                    >
                    <label class="form__label" for="anonymous">{{ __('common.anonymous') }}?</label>
                </p>
                <p class="form__group">
                    <button class="form__button form__button--filled">
                        {{ __('common.upload') }}
                    </button>
                </p>
            </form>
        </div>
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('torrent.torrent') }}</label>
        <div class="panel__body">
            <a
                href="{{ route('torrent', ['id' => $torrent->id]) }}"
                title="{{ $torrent->name }}"
            >
                {{ $torrent->name }}
            </a>
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('subtitle.rules-title') }}</h2>
        <div class="panel__body">
            <ol>
                @foreach(Str::of(__('subtitle.rules'))->replace(['<ul>', '</ul>', '<li>', '</li>'], '')->trim()->explode("\n") as $rule)
                    <li>{{ $rule }}</li>
                @endforeach
            </ol>
        </div>
    </section>
@endsection
