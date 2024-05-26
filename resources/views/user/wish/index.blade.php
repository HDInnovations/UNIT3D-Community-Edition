@extends('layout.default')

@section('title')
    <title>{{ $user->username }} {{ __('user.wishlist') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['user' => $user]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('user.wishlist') }}
    </li>
@endsection

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('user.wishlist') }}</h2>
            <div class="panel__actions" x-data="{ meta: 'movie' }">
                <div class="panel__action">
                    <div class="form__group">
                        <select
                            id="meta"
                            class="form__select"
                            form="wishlistForm"
                            name="meta"
                            x-model="meta"
                            required
                        >
                            <option selected value="movie">{{ __('mediahub.movie') }}</option>
                            <option value="tv">TV</option>
                        </select>
                        <label class="form__label form__label--floating" for="model">
                            {{ __('mediahub.movie') }}/TV
                        </label>
                    </div>
                </div>
                <div class="panel__action">
                    <div class="form__group">
                        <input
                            id="tmdb"
                            class="form__text"
                            form="wishlistForm"
                            x-bind:name="meta === 'movie' ? 'movie_id' : 'tv_id'"
                            type="text"
                            required
                        />
                        <label class="form__label form__label--floating" for="tmdb">TMDB ID</label>
                    </div>
                </div>
                <form
                    id="wishlistForm"
                    class="form form--horizontal panel__action"
                    action="{{ route('users.wishes.store', ['user' => $user]) }}"
                    method="POST"
                >
                    @csrf
                    <button class="form__button form__button--text">
                        {{ __('common.add') }}
                    </button>
                </form>
            </div>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('torrent.title') }}</th>
                        <th>{{ __('torrent.torrents') }}</th>
                        <th>{{ __('common.status') }}</th>
                        <th>{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($wishes as $wish)
                        <tr>
                            <td>
                                @if ($wish->source === null)
                                    {{ $wish->title }}
                                @else
                                    <a href="{{ $wish->source }}">{{ $wish->title }}</a>
                                @endif
                            </td>
                            <td>
                                <a
                                    href="{{ route('torrents.index', ['tmdbId' => $wish->movie_id ?? $wish->tv_id, 'view' => 'group']) }}"
                                >
                                    @if ($wish->movie_id !== null)
                                        Torrents ({{ $wish->movie_torrents_count }})
                                    @elseif ($wish->tv_id !== null)
                                        Torrents ({{ $wish->tv_torrents_count }})
                                    @endif
                                </a>
                            </td>
                            <td>
                                @if ($wish->movie_id !== null)
                                    @if ($wish->movie_torrents_count === 0)
                                        <i
                                            class="{{ config('other.font-awesome') }} fa-times text-red"
                                            title="Not yet uploaded"
                                        ></i>
                                    @else
                                        <i
                                            class="{{ config('other.font-awesome') }} fa-check text-green"
                                            title="Already uploaded"
                                        ></i>
                                    @endif
                                @elseif ($wish->tv_id !== null)
                                    @if ($wish->tv_torrents_count === 0)
                                        <i
                                            class="{{ config('other.font-awesome') }} fa-times text-red"
                                            title="Not yet uploaded"
                                        ></i>
                                    @else
                                        <i
                                            class="{{ config('other.font-awesome') }} fa-check text-green"
                                            title="Already uploaded"
                                        ></i>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <menu class="data-table__actions">
                                    <li class="data-table__action">
                                        <form
                                            action="{{ route('users.wishes.destroy', ['user' => $user, 'wish' => $wish]) }}"
                                            method="POST"
                                            x-data="confirmation"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                x-on:click.prevent="confirmAction"
                                                data-b64-deletion-message="{{ base64_encode('Are you sure you want to delete this wish: ' . $wish->title . '?') }}"
                                                class="form__button form__button--text"
                                            >
                                                {{ __('common.delete') }}
                                            </button>
                                        </form>
                                    </li>
                                </menu>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No wishes</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $wishes->links('partials.pagination') }}
    </section>
@endsection
