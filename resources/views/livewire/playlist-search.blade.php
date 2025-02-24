<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('playlist.playlists') }}</h2>
        <div class="panel__actions">
            <div class="panel__action">
                <div class="form__group">
                    <input
                        id="name"
                        class="form__text"
                        placeholder=" "
                        type="search"
                        autocomplete="off"
                        wire:model.live.debounce.250ms="name"
                    />
                    <label class="form__label form__label--floating" for="name">
                        {{ __('torrent.search-by-name') }}
                    </label>
                </div>
            </div>
            <div class="panel__action">
                <div class="form__group">
                    <input
                        id="username"
                        class="form__text"
                        placeholder=" "
                        type="search"
                        autocomplete="off"
                        wire:model.live.debounce.250ms="username"
                    />
                    <label class="form__label form__label--floating" for="username">
                        {{ __('common.username') }}
                    </label>
                </div>
            </div>
            <div class="panel__action">
                <a class="form__button form__button--text" href="{{ route('playlists.create') }}">
                    {{ __('common.add') }}
                </a>
            </div>
        </div>
    </header>
    <div class="panel__body playlists">
        @forelse ($playlists as $playlist)
            <article class="playlists__playlist">
                @if (isset($playlist->cover_image) && Storage::disk('torrent-covers')->exists("torrent-cover_$torrent->id.jpg"))
                    <a
                        class="playlists__playlist-image-link"
                        href="{{ route('playlists.show', ['playlist' => $playlist]) }}"
                    >
                        <img
                            class="playlists__playlist-image"
                            src="{{ route('authenticated_images.playlist_image', ['playlist' => $playlist]) }}"
                            alt=""
                        />
                    </a>
                @else
                    <a
                        class="playlists__playlist-image-link--none"
                        href="{{ route('playlists.show', ['playlist' => $playlist]) }}"
                    >
                        <div class="playlists__playlist-image--none"></div>
                    </a>
                @endif
                <div class="playlists__playlist-author">
                    <x-user_tag :user="$playlist->user" :anon="false" />
                </div>
                <a
                    class="playlists__playlist-link"
                    href="{{ route('playlists.show', ['playlist' => $playlist]) }}"
                >
                    <h3 class="playlists__playlist-name">
                        {{ $playlist->name }}
                    </h3>
                </a>
                <a
                    class="playlists__playlist-link-titles"
                    href="{{ route('playlists.show', ['playlist' => $playlist]) }}"
                >
                    <p class="playlists__playlist-titles">
                        {{ $playlist->torrents_count }} {{ __('torrent.torrents') }}
                    </p>
                </a>
            </article>
        @empty
            {{ __('playlist.about') }}
        @endforelse
    </div>
    {{ $playlists->links('partials.pagination') }}
</section>
