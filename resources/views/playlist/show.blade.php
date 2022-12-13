@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('playlists.index') }}" class="breadcrumb__link">
            {{ __('playlist.playlists') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $playlist->name }}
    </li>
@endsection

@if(auth()->user()->id == $playlist->user_id || auth()->user()->group->is_modo)
    @section('sidebar')
        <section class="panelV2">
            <h2 class="panel__heading">{{ __('common.actions') }}</h2>
            <div class="panel__body">
                <div class="form__group form__group--horizontal" x-data="{ open: false }">
                    <button class="form__button form__button--filled" x-on:click.stop="open = true; $refs.dialog.showModal();">
                        <i class="{{ config('other.font-awesome') }} fa-search-plus"></i>
                        {{ __('playlist.add-torrent') }}
                    </button>
                    <dialog class="dialog" x-ref="dialog" x-show="open" x-cloak>
                        <h4 class="dialog__heading">
                            {{ __('playlist.add-to-playlist') }}
                        </h4>
                        <form
                            class="dialog__form"
                            method="POST"
                            action="{{ route('playlists.attach') }}"
                            x-on:click.outside="open = false; $refs.dialog.close();"
                        >
                            @csrf
                            <p class="form__group">
                                <input id="playlist_id" name="playlist_id" type="hidden" value="{{ $playlist->id }}">
                            </p>
                            <p class="form__group">
                                <input
                                    id="torrent_id"
                                    class="form__text"
                                    name="torrent_id"
                                    type="text"
                                    required
                                >
                                <label class="form__label form__label--floating" for="torrent_id">Torrent ID</label>
                            </p>
                            <p class="form__group">
                                <button class="form__button form__button--filled">
                                    {{ __('common.add') }}
                                </button>
                                <button x-on:click.prevent="open = false; $refs.dialog.close();" class="form__button form__button--outlined">
                                    {{ __('common.cancel') }}
                                </button>
                            </p>
                        </form>
                    </dialog>
                </div>
                <p class="form__group form__group--horizontal">
                    <a
                        href="{{ route('playlists.edit', ['id' => $playlist->id]) }}"
                        class="form__button form__button--filled"
                    >
                        <i class="{{ config('other.font-awesome') }} fa-edit"></i>
                        {{ __('playlist.edit-playlist') }}
                    </a>
                </p>
                <form
                    action="{{ route('playlists.destroy', ['id' => $playlist->id]) }}"
                    method="POST"
                    x-data
                >
                    @csrf
                    @method('DELETE')
                        <p class="form__group form__group--horizontal">
                        <button 
                            x-on:click.prevent="Swal.fire({
                                title: 'Are you sure?',
                                text: 'Are you sure you want to delete this playlist: {{ $playlist->name }}?',
                                icon: 'warning',
                                showConfirmButton: true,
                                showCancelButton: true,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $root.submit();
                                }
                            })"
                            class="form__button form__button--filled"
                        >
                            <i class="{{ config('other.font-awesome') }} fa-trash"></i>
                            {{ __('common.delete') }}
                        </p>
                    </button>
                </form>
            </div>
        </section>
        <section class="panelV2">
            <h2 class="panel__heading">{{ __('common.download') }}</h2>
            <div class="panel__body">
                <p class="form__group form__group--horizontal">
                    <button
                        href="{{ route('playlists.download', ['id' => $playlist->id]) }}"
                        class="form__button form__button--filled"
                    >
                        <i class='{{ config('other.font-awesome') }} fa-download'></i>
                        {{ __('playlist.download-all') }}
                    </button>
                </p>
                <p class="form__group form__group--horizontal">
                    <a
                        href="{{ route('torrents', ['playlistId' => $playlist->id]) }}"
                        class="form__button form__button--filled"
                    >
                        <i class='{{ config('other.font-awesome') }} fa-eye'></i>
                        Playlist Torrents List
                    </a>
                </p>
            </div>
        </section>
    @endsection
@endif

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ $playlist->name }}</h2>
        @php $tmdb_backdrop = isset($meta->backdrop) ? tmdb_image('back_big', $meta->backdrop) : 'https://via.placeholder.com/1280x350' @endphp
        <div class="playlist__backdrop" style="background-image: url('{{ $tmdb_backdrop }}')">
            <div class="playlist__backdrop-filter">
                <a class="playlist__author-link" href="{{ route('users.show', ['username' => $playlist->user->username]) }}">
                    <img
                        class="playlist__author-avatar"
                        src="{{ url($playlist->user->image ? 'files/img/'.$playlist->user->image : 'img/profile.png') }}"
                        alt="{{ $playlist->user->username }}"
                    >
                </a>
                <p class="playlist__author">
                    <x-user_tag :user="$playlist->user" :anon="false" />
                </p>
                <p class="playlist__description">
                    {{ $playlist->description }}
                </p>
            </div>
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('torrent.torrents') }}</h2>
        <div class="panel__body playlist__torrents">
            @foreach($torrents as $playlistTorrent)
                @php
                    $meta = match(1) {
                        $playlistTorrent->torrent->category->tv_meta => App\Models\Tv::query()->with('genres', 'networks', 'seasons')->where('id', '=', $playlistTorrent->torrent->tmdb ?? 0)->first(),
                        $playlistTorrent->torrent->category->movie_meta => App\Models\Movie::query()->with('genres', 'cast', 'companies', 'collection')->where('id', '=', $playlistTorrent->torrent->tmdb ?? 0)->first(),
                        $playlistTorrent->torrent->category->game_meta => MarcReichel\IGDBLaravel\Models\Game::query()->with(['artworks' => ['url', 'image_id'], 'genres' => ['name']])->find((int) $playlistTorrent->torrent->igdb),
                        default => null,
                    };
                @endphp
                <div class="playlist__torrent-container">
                    <x-torrent.card :meta="$meta" :torrent="$playlistTorrent->torrent" />
                    @if(auth()->user()->id == $playlist->user_id || auth()->user()->group->is_modo)
                        <form
                            action="{{ route('playlists.detach', ['id' => $playlistTorrent->id]) }}"
                            method="POST"
                        >
                            @csrf
                            @method('DELETE')
                            <button class="form__standard-icon-button">
                                <i class="{{ config('other.font-awesome') }} fa-trash"></i>
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
        {{ $torrents->links() }}
    </section>
    <livewire:comments :model="$playlist"/>
@endsection
