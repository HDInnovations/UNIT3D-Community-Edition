@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('playlist.playlists') }}
    </li>
@endsection

@section('content')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('playlist.playlists') }}</h2>
            <div class="panel__actions">
                <a class="panel__action form__button form__button--text" href="{{ route('playlists.create') }}">
                    {{ __('common.add') }}
                </a>
            </div>
        </header>
        <div class="panel__body playlists">
            @forelse($playlists as $playlist)
                <article class="playlists__playlist">
                    @if(isset($playlist->cover_image))
                        <a
                            class="playlists__playlist-image-link" 
                            href="{{ route('playlists.show', ['id' => $playlist->id]) }}"
                        >
                            <img
                                class="playlists__playlist-image"
                                src="{{ url('files/img/' . $playlist->cover_image) }}"
                                alt="Cover Image"
                            >
                        </a>
                    @else
                        <a
                            class="playlists__playlist-image-link--none" 
                            href="{{ route('playlists.show', ['id' => $playlist->id]) }}"
                        >
                            <div class="playlists__playlist-image--none "></div>
                        </a>
                    @endif
                    <div class="playlists__playlist-author">
                        <a
                            class="playlists__playlist-author-link" 
                            href="{{ route('users.show', ['username' => $playlist->user->username]) }}"
                        >
                            <img
                                class="playlists__playlist-avatar"
                                src="{{ url($playlist->user->image ? 'files/img/'.$playlist->user->image : 'img/profile.png') }}"
                                alt="{{ $playlist->user->username }}"
                            >
                        </a>
                        <x-user_tag :user="$playlist->user" :anon="false" />
                    </div>
                    <a
                        class="playlists__playlist-link" 
                        href="{{ route('playlists.show', ['id' => $playlist->id]) }}"
                    >
                        <h3 class="playlists__playlist-name">
                            {{ $playlist->name }}
                        </h3>
                    </a>
                    <a
                        class="playlists__playlist-link-titles" 
                        href="{{ route('playlists.show', ['id' => $playlist->id]) }}"
                    >
                        <p class="playlists__playlist-titles">
                            {{ $playlist->torrents_count }} {{ __('playlist.titles') }}
                        </p>
                    </a>
                </article>
            @empty
                {{ __('playlist.about') }}
            @endforelse
        </div>
        {{ $playlists->links('partials.pagination') }}
    </section>
@endsection
