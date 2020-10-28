@extends('layout.default')

@section('title')
    <title>@lang('common.create') @lang('common.album') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('common.create') @lang('common.album')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('albums.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('common.gallery')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('albums.create') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('common.create') @lang('common.album')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <form name="createnewalbum" method="POST" action="{{ route('albums.store') }}" enctype="multipart/form-data">
                @csrf
                <h2 class="text-center">@lang('gallery.create-an-album')</h2>
                <h4 class="text-red text-center">
                    @lang('gallery.create-an-album-tip')
                </h4>
                <div class="form-group">
                    <label for="name">IMDB</label>
                    <label>
                        <input name="imdb" type="text" class="form-control" placeholder="IMDB #" value="{{ old('imdb') }}">
                    </label>
                </div>
                <div class="form-group">
                    <label for="description">@lang('common.album') @lang('common.description')</label>
                    <label>
                        <textarea name="description" type="text" class="form-control" placeholder="Sleeves / Disc Art">{{ old('description') }}</textarea>
                    </label>
                </div>
                <div class="form-group">
                    <label for="cover_image">@lang('gallery.select-cover')</label>
                    <input type="file" name="cover_image">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">@lang('common.add')</button>
                </div>
            </form>
        </div>
    </div>
@endsection
