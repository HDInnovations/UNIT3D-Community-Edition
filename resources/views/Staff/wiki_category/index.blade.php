@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">Wiki Categories</li>
@endsection

@section('content')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">Wiki Categories</h2>
            <div class="panel__actions">
                <a
                    href="{{ route('staff.wiki_categories.create') }}"
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
                        <th>{{ __('common.title') }}</th>
                        <th>{{ __('common.position') }}</th>
                        <th>{{ __('common.icon') }}</th>
                        <th>{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($wikiCategories as $wikiCategory)
                        <tr>
                            <td>
                                <a
                                    href="{{ route('staff.wiki_categories.edit', ['wikiCategory' => $wikiCategory]) }}"
                                >
                                    {{ $wikiCategory->name }}
                                </a>
                            </td>
                            <td>{{ $wikiCategory->position }}</td>
                            <td>
                                <i class="{{ $wikiCategory->icon }}" aria-hidden="true"></i>
                            </td>
                            <td>
                                <menu class="data-table__actions">
                                    <li class="data-table__action">
                                        <a
                                            href="{{ route('staff.wiki_categories.edit', ['wikiCategory' => $wikiCategory]) }}"
                                            class="form__button form__button--text"
                                        >
                                            {{ __('common.edit') }}
                                        </a>
                                    </li>
                                    <li class="data-table__action">
                                        <form
                                            action="{{ route('staff.wiki_categories.destroy', ['wikiCategory' => $wikiCategory]) }}"
                                            method="POST"
                                            x-data="confirmation"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                x-on:click.prevent="confirmAction"
                                                data-b64-deletion-message="{{ base64_encode('Are you sure you want to delete this wiki category: ' . $wikiCategory->name . '?') }}"
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
                            <td colspan="2">No Wiki Categories</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
