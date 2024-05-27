@extends('layout.default')

@section('title')
    <title>Wikis - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ config('other.title') }} - Wikis" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">Wikis</li>
@endsection

@section('page', 'page__wikis--index')

@section('main')
    @foreach ($wiki_categories as $category)
        <section class="panelV2">
            <h2 class="panel__heading">
                {{ $category->name }}
            </h2>
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
                        @forelse ($category->wikis->sortBy('name') as $wiki)
                            <tr>
                                <td>
                                    <a href="{{ route('wikis.show', ['wiki' => $wiki]) }}">
                                        {{ $wiki->name }}
                                    </a>
                                </td>
                                <td>
                                    {{ $wiki->created_at }}
                                </td>
                                <td>
                                    {{ $wiki->updated_at }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">No wikis in category.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endforeach
@endsection
