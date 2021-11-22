@extends('layout.default')

@section('title')
    <title>@lang('playlist.title') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Create Playlist">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('playlists.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('playlist.title')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('playlists.create') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('playlist.create')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <form name="new-playlist" method="POST" action="{{ route('playlists.store') }}" enctype="multipart/form-data">
                @csrf
                <h2 class="text-center">@lang('playlist.create')</h2>
                <div class="form-group">
                    <label for="name">@lang('playlist.title')</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label for="description">@lang('playlist.desc')</label>
                    <textarea name="description" type="text" class="form-control">{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="cover_image">@lang('playlist.cover')</label>
                    <input type="file" name="cover_image">
                </div>
                <label for="is_private" class="control-label">@lang('playlist.is-private')</label>
                <div class="radio-inline">
                    <label><input type="radio" name="is_private" value="1">@lang('common.yes')</label>
                </div>
                <div class="radio-inline">
                    <label><input type="radio" name="is_private" checked="checked" value="0">@lang('common.no')</label>
                </div>
                <br>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">@lang('common.add')</button>
                </div>
            </form>
        </div>
    </div>
@endsection
