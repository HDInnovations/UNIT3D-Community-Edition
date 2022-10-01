@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumb--active">
        Pages
    </li>
@endsection

@section('page', 'page__page--index')

@section('content')
    <section class="panelV2">
        <h2 class="panel__heading">Pages</h1>
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
                    @foreach($pages as $page)
                        <tr>
                            <td>
                                <a href="{{ route('pages.show', ['id' => $page->id]) }}">
                                    {{ $page->name }}
                                </a>
                            </td>
                            <td>
                                {{ $page->created_at }}
                            </td>
                            <td>
                                {{ $page->updated_at }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
