@extends('layout.default')

@section('title')
    <title>{{ $user->username }} {{ __('user.wishlist') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="breadcrumb__link">
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
            <div class="panel__actions">
                <form
                    class="form form--horizontal panel__action"
                    action="{{ route('wishes.store') }}"
                    method="POST"
                >
                    @csrf
                    <div class="form__group">
                        <input
                            id="tmdb"
                            class="form__text"
                            name="tmdb"
                            required
                            type="text"
                        />
                        <label class="form__label form__label--floating">
                            TMDB ID
                        </label>
                    </div>
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
                        <th>TMDB</th>
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
                                <a href="{{ route('mediahub.movies.show', ['id' => $wish->tmdb]) }}" target="_blank">
                                    MediaHub
                                </a>
                            </td>
                            <td>
                                @if ($wish->source === null)
                                    <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                                @else
                                    <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                                @endif
                            </td>
                            <td>
                                <menu class="data-table__actions">
                                    <li class="data-table__action">
                                        <form
                                            action="{{ route('wishes.destroy', ['id' => $wish->id]) }}"
                                            method="POST"
                                            x-data
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                x-on:click.prevent="Swal.fire({
                                                    title: 'Are you sure?',
                                                    text: 'Are you sure you want to delete this wish: {{ $wish->title }}?',
                                                    icon: 'warning',
                                                    showConfirmButton: true,
                                                    showCancelButton: true,
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $root.submit();
                                                    }
                                                })"
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
