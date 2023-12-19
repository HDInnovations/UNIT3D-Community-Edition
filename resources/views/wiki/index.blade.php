@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('wikis.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Wikis</span>
        </a>
    </li>
@endsection

@section('content')
<div class="container">
    <div class="block">
        <div class="forum-categories">
            <table class="table table-bordered table-hover">
                @foreach ($wiki_categories as $category)
                        <thead class="no-space">
                        <tr class="no-space">
                            <td colspan="5" class="no-space">
                                <div class="header gradient teal some-padding">
                                    <div class="inner_content">
                                        <h2 class="no-space">{{ $category->name }}</h2>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </thead>
                        <thead>
                        <tr>
                            <th>@lang('common.name')</th>
                            <th>Created</th>
                            <th>Updated</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($category->wikis->sortBy('name') as $wiki)
                                <tr>
                                    <td>
                                        <a href="{{ route('wikis.show', ['id' => $wiki->id]) }}">
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
                        @endforeach
                        </tbody>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection