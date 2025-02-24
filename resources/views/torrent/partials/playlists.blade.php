<div class="panel__body playlists torrent__playlists">
    @forelse ($torrent->playlists as $playlist)
        <article class="playlists__playlist">
            @if (isset($playlist->cover_image) && Storage::disk('playlist-covers')->exists($playlist->cover_image))
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
                <a
                    class="playlists__playlist-author-link"
                    href="{{ route('users.show', ['user' => $playlist->user]) }}"
                >
                    <img
                        class="playlists__playlist-avatar"
                        src="{{ $playlist->user->image ? route('authenticated_images.user_avatar', ['user' => $playlist->user]) : url'img/profile.png') }}"
                        alt="{{ $playlist->user->username }}"
                    />
                </a>
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
                    {{ $playlist->torrents_count }} {{ __('playlist.titles') }}
                </p>
            </a>
        </article>
    @empty
        {{ __('playlist.about') }}
    @endforelse
</div>
