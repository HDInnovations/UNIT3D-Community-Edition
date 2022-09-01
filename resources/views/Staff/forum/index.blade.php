@extends('layout.default')

@section('title')
    <title>Forums - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Forums - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('staff.forums') }}
    </li>
@endsection

@section('page', 'page__forums-admin--index')

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">Forums</h2>
            <a href="{{ route('staff.forums.create') }}" class="form__button form__button--text">
                {{ __('common.add') }}
            </a>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                <tr>
                    <th>{{ __('common.name') }}</th>
                    <th>Type</th>
                    <th>{{ __('common.position') }}</th>
                    <th>{{ __('common.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>
                            <a href="{{ route('staff.forums.edit', ['id' => $category->id]) }}">{{ $category->name }}</a>
                        </td>
                        <td>Category</td>
                        <td>{{ $category->position }}</td>
                        <td>
                            <menu class="data-table__actions">
                                <li class="data-table__action">
                                    <a
                                        class="form__button form__button--text"
                                        href="{{ route('staff.forums.edit', ['id' => $category->id]) }}"
                                    >
                                        {{ __('common.edit') }}
                                    </a>
                                </li>
                                <li class="data-table__action">
                                    <form
                                        method="POST"
                                        action="{{ route('staff.forums.destroy', ['id' => $category->id]) }}"
                                        x-data
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            x-on:click.prevent="Swal.fire({
                                                title: 'Are you sure?',
                                                text: 'Are you sure you want to delete this forum category: {{ $category->name }}?',
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
                    @foreach ($category->getForumsInCategory()->sortBy('position') as $forum)
                        <tr>
                            <td style="padding-left: 50px">
                                <a href="{{ route('staff.forums.edit', ['id' => $forum->id]) }}">{{ $forum->name }}</a>
                            </td>
                            <td>Forum</td>
                            <td>{{ $forum->position }}</td>
                            <td>
                                <menu class="data-table__actions">
                                    <li class="data-table__action">
                                        <a
                                            class="form__button form__button--text"
                                            href="{{ route('staff.forums.edit', ['id' => $forum->id]) }}"
                                        >
                                            {{ __('common.edit') }}
                                        </a>
                                    </li>
                                    <li class="data-table__action">
                                        <form
                                            method="POST"
                                            action="{{ route('staff.forums.destroy', ['id' => $forum->id]) }}"
                                            x-data
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                x-on:click.prevent="Swal.fire({
                                                    title: 'Are you sure?',
                                                    text: 'Are you sure you want to delete this forum: {{ $forum->name }}?',
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
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
