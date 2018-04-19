@extends('layout.default')

@section('title')
<title>{{ trans('torrent.torrents') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
<meta name="description" content="{{ trans('torrent.torrents') }}">
@endsection

@section('breadcrumb')
<li>
    <a href="{{ route('torrents') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.torrents') }}</span>
    </a>
</li>
<li>
    <a href="{{ route('grouping_categories') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Grouping Categories</span>
    </a>
</li>
@endsection

@section('content')
<div class="container">
    <div class="block block-titled">
        <h2 class="h3">What category would you like to group?</h2>
        <div class="row">
            @foreach($categories as $category)
            <div class="col-xs-4 text-center">
                <a href="{{ route('grouping', ['category_id' => $category->id]) }}" title="{{ $category->name }}">
                    <i class="{{ $category->icon }} fa-3x"></i><br>{{ $category->name }}
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
