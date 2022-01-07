@extends('layout.default')

@section('title')
    <title>{{ __('playlist.title') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Create Playlist">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('playlists.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('playlist.title') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('playlists.create') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('playlist.create') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <form name="new-playlist" method="POST" action="{{ route('playlists.store') }}"
                  enctype="multipart/form-data">
                @csrf
                <h2 class="text-center">{{ __('playlist.create') }}</h2>
                <div class="form-group">
                    <label for="name">{{ __('playlist.title') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label for="description">{{ __('playlist.desc') }}</label>
                    <textarea name="description" type="text" class="form-control">{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="cover_image">{{ __('playlist.cover') }}</label>
                    <input type="file" name="cover_image">
                </div>
                <label for="is_private" class="control-label">{{ __('playlist.is-private') }}</label>
                <div class="radio-inline">
                    <label><input type="radio" name="is_private" value="1">{{ __('common.yes') }}</label>
                </div>
                <div class="radio-inline">
                    <label><input type="radio" name="is_private" checked="checked" value="0">{{ __('common.no') }}</label>
                </div>
                <br>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">{{ __('common.add') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
