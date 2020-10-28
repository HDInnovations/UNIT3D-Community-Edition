@extends('layout.default')

@section('title')
    <title>@lang('torrent.categories') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('categories.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.categories')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="header gradient green">
            <div class="inner_content">
                <h1>@lang('torrent.categories')</h1>
            </div>
        </div>
        <section class="tall_categories">
            @foreach($categories as $category)
                <a href="{{ route('categories.show', ['id' => $category->id]) }}">
                    <div class="category {{ strtolower($category->name) }}">
                        <div>
                            <p>{{ $category->name }}</p>
                            <span></span>
                            <p>{{ $category->num_torrent }} {{ trans('torrent.torrents') }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </section>
    </div>
@endsection
