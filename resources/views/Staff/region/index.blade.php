@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        Torrent Regions
    </li>
@endsection

@section('page', 'page__region--index')

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">Torrent Regions</h2>
            <div class="panel__actions">
                <a
                    href="{{ route('staff.regions.create') }}"
                    class="panel__action form__button form__button--text"
                >
                    {{ __('common.add') }}
                </a>
            </div>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                <tr>
                    <th>{{ __('common.position') }}</th>
                    <th>{{ __('common.name') }}</th>
                    <th>{{ __('common.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($regions as $region)
                    <tr>
                        <td>{{ $region->position }}</td>
                        <td>
                            <a href="{{ route('staff.regions.edit', ['id' => $region->id]) }}">
                                {{ $region->name }}
                            </a>
                        </td>
                        <td>
                            <menu class="data-table__actions">
                                <li class="data-table__action">
                                    <a
                                        href="{{ route('staff.regions.edit', ['id' => $region->id]) }}"
                                        class="form__button form__button--text"
                                    >
                                        {{ __('common.edit') }}
                                    </a>
                                </li>
                                <li class="data-table__action" x-data="{ open: false }">
                                    <button class="form__button form__button--text" x-on:click.stop="open = true; $refs.dialog.showModal();">
                                        {{ __('common.delete') }}
                                    </button>
                                    <dialog class="dialog" x-ref="dialog">
                                        <h4 class="dialog__heading">
                                            Delete Torrent Region: {{ $region->name }}
                                        </h4>
                                        <form
                                            class="dialog__form"
                                            method="POST"
                                            action="{{ route('staff.regions.destroy', ['id' => $region->id]) }}"
                                            x-on:click.outside="open = false; $refs.dialog.close();"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <p class="form__group">
                                                An existing torrent on site may already use this region. Would you like to change it?
                                            </p>
                                            <p class="form__group" x-data>
                                                <select
                                                    name="region_id"
                                                    id="autoreg"
                                                    class="form__select"
                                                    x-data="{ region: '' }"
                                                    x-model="region"
                                                    x-bind:class="region === '' ? 'form__select--default' : ''"
                                                >
                                                    <option hidden disabled selected value=""></option>
                                                    @foreach ($regions as $region)
                                                        <option value="{{ $region->id }}">
                                                            {{ $region->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <label class="form__label form__label--floating" for="autoreg">
                                                    Replacement region
                                                </label>
                                            </p>
                                            <p class="form__group">
                                                <button class="form__button form__button--filled">
                                                    {{ __('common.delete') }}
                                                </button>
                                                <button class="form__button form__button--outlined" x-on:click.prevent="open = false; $refs.dialog.close();">
                                                    {{ __('common.cancel') }}
                                                </button>
                                            </p>
                                        </form>
                                    </dialog>
                                </li>
                                
                            </menu>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No regions</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
