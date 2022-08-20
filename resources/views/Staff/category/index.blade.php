@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('staff.torrent-categories') }}
    </li>
@endsection

@section('page', 'page__category--index')

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('torrent.categories') }}</h2>
            <div class="panel__actions">
                <form class="panel__action" action="{{ route('staff.categories.create') }}">
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
                    <th>{{ __('common.position') }}</th>
                    <th>{{ __('common.name') }}</th>
                    <th>{{ __('common.icon') }}</th>
                    <th>{{ __('common.image') }}</th>
                    <th>Movie Meta</th>
                    <th>TV Meta</th>
                    <th>Game Meta</th>
                    <th>Music Meta</th>
                    <th>No Meta</th>
                    <th>{{ __('common.action') }}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->position }}</td>
                            <td>
                                <a href="{{ route('staff.categories.edit', ['id' => $category->id]) }}">
                                    {{ $category->name }}
                                </a>
                            </td>
                            <td>
                                <i class="{{ $category->icon }}"></i>
                            </td>
                            <td>
                                @if ($category->image != null)
                                    <img alt="{{ $category->name }}" src="{{ url('files/img/' . $category->image) }}">
                                @else
                                    <span>N/A</span>
                                @endif
                            </td>
                            <td>
                                @if ($category->movie_meta)
                                    <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                                @else
                                    <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                                @endif
                            </td>
                            <td>
                                @if ($category->tv_meta)
                                    <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                                @else
                                    <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                                @endif
                            </td>
                            <td>
                                @if ($category->game_meta)
                                    <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                                @else
                                    <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                                @endif
                            </td>
                            <td>
                                @if ($category->music_meta)
                                    <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                                @else
                                    <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                                @endif
                            </td>
                            <td>
                                @if ($category->no_meta)
                                    <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                                @else
                                    <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                                @endif
                            </td>
                            <td>
                                <menu class="data-table__actions">
                                    <li class="data-table__action">
                                        <a
                                            class="form__button form__button--text"
                                            href="{{ route('staff.categories.edit', ['id' => $category->id]) }}"
                                        >
                                            {{ __('common.edit') }}
                                        </a>
                                    </li>
                                    <li class="data-table__action">
                                        <form
                                            action="{{ route('staff.categories.destroy', ['id' => $category->id]) }}"
                                            method="POST"
                                            x-data
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                x-on:click.prevent="Swal.fire({
                                                    title: 'Are you sure?',
                                                    text: 'Are you sure you want to delete this category: {{ $category->name }}?',
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
