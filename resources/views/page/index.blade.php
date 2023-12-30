@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumb--active">Pages</li>
@endsection

@section('page', 'page__page--index')

@section('content')
    <section class="panelV2">
        <h2 class="panel__heading">Pages</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('common.name') }}</th>
                        <th>Created</th>
                        <th>Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pages as $page)
                        <tr>
                            <td>
                                <a href="{{ route('pages.show', ['page' => $page]) }}">
                                    {{ $page->name }}
                                </a>
                            </td>
                            <td>
                                <time
                                    datetime="{{ $page->created_at }}"
                                    title="{{ $page->created_at }}"
                                >
                                    {{ $page->created_at }}
                                </time>
                            </td>
                            <td>
                                <time
                                    datetime="{{ $page->updated_at }}"
                                    title="{{ $page->updated_at }}"
                                >
                                    {{ $page->updated_at }}
                                </time>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
