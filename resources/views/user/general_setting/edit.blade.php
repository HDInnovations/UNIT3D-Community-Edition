@extends('layout.default')

@section('title')
    <title>{{ $user->username }} - Settings - {{ __('common.members') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('user.settings') }}
    </li>
@endsection

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@section('content')
    <section class="panelV2">
        <h2 class="panel__heading">General {{ __('user.settings') }}</h2>
        <div class="panel__body">
            <form
                class="form"
                method="POST"
                action="{{ route('users.general_settings.update', ['user' => $user]) }}"
                enctype="multipart/form-data"
            >
                @csrf
                @method('PATCH')
                <p class="form__group">
                    <select id="locale" class="form__select" name="locale" required>
                        @foreach (App\Models\Language::allowed() as $code => $name)
                            <option class="form__option" value="{{ $code }}" @selected($user->locale === $code)>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    <label class="form__label form__label--floating" for="locale">
                        Language
                    </label>
                </p>
                <fieldset class="form form__fieldset">
                    <legend class="form__legend">Style</legend>
                    <p class="form__group">
                        <select id="style" class="form__select" name="style" required>
                            <option class="form__option" value="0" @selected($user->style === 0)>Light</option>
                            <option class="form__option" value="1" @selected($user->style === 1)>Galactic</option>
                            <option class="form__option" value="2" @selected($user->style === 2)>Dark Blue</option>
                            <option class="form__option" value="3" @selected($user->style === 3)>Dark Green</option>
                            <option class="form__option" value="4" @selected($user->style === 4)>Dark Pink</option>
                            <option class="form__option" value="5" @selected($user->style === 5)>Dark Purple</option>
                            <option class="form__option" value="6" @selected($user->style === 6)>Dark Red</option>
                            <option class="form__option" value="7" @selected($user->style === 7)>Dark Teal</option>
                            <option class="form__option" value="8" @selected($user->style === 8)>Dark Yellow</option>
                            <option class="form__option" value="9" @selected($user->style === 9)>Cosmic Void</option>
                        </select>
                        <label class="form__label form__label--floating" for="style">
                            Theme
                        </label>
                    </p>
                    <p class="form__group">
                        <input
                            id="custom_css"
                            class="form__text"
                            name="custom_css"
                            placeholder=""
                            type="url"
                            value="{{ $user->custom_css }}"
                        >
                        <label class="form__label form__label--floating" for="custom_css">
                            External CSS Stylesheet (Stacks on top of above theme)
                        </label>
                    </p>
                    <p class="form__group">
                        <input
                            id="standalone_css"
                            class="form__text"
                            name="standalone_css"
                            placeholder=""
                            type="url"
                            value="{{ $user->standalone_css }}"
                        >
                        <label class="form__label form__label--floating" for="standalone_css">
                            Standalone CSS Stylesheet (No site theme used)
                        </label>
                    </p>
                </fieldset>
                <fieldset class="form__fieldset">
                    <legend class="form__legend">Chat</legend>
                    <p class="form__group">
                        <label class="form__label">
                            <input type="hidden" name="censor" value="0">
                            <input
                                class="form__checkbox"
                                type="checkbox"
                                name="censor"
                                value="1"
                                @checked($user->censor)
                            />
                            Language Censor Chat
                        </label>
                    </p>
                    <p class="form__group">
                        <label class="form__label">
                            <input type="hidden" name="chat_hidden" value="0">
                            <input
                                class="form__checkbox"
                                type="checkbox"
                                name="chat_hidden"
                                value="1"
                                @checked($user->chat_hidden)
                            />
                            Hide Chat
                        </label>
                    </p>
                </fieldset>
                <fieldset class="form form__fieldset">
                    <legend class="form__legend">Torrent</legend>
                    <p class="form__group">
                        <select id="show_poster" class="form__select" name="torrent_layout" required>
                            <option class="form__option" value="0" @selected($user->torrent_layout === 0)>Torrent list</option>
                        </select>
                        <label class="form__label form__label--floating" for="torrent_layout">
                            Default torrent layout
                        </label>
                    </p>
                    <p class="form__group">
                        <select id="ratings" class="form__select" name="ratings" required>
                            <option class="form__option" value="0" @selected($user->ratings === 0)>TMDB</option>
                            <option class="form__option" value="1" @selected($user->ratings === 1)>IMDB</option>
                        </select>
                        <label class="form__label form__label--floating" for="ratings">
                            Ratings source
                        </label>
                    </p>
                    <p class="form__group">
                        <label class="form__label">
                            <input type="hidden" name="show_poster" value="0">
                            <input
                                class="form__checkbox"
                                type="checkbox"
                                name="show_poster"
                                value="1"
                                @checked($user->show_poster)
                            />
                            Show Posters On Torrent List View
                        </label>
                    </p>
                </fieldset>
                <p class="form__group">
                    <button class="form__button form__button--filled">
                        {{ __('common.save') }}
                    </button>
                </p>
            </form>
        </div>
    </section>
@endsection
