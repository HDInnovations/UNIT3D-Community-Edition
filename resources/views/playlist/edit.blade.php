@extends('layout.default')

@section('title')
    <title>@lang('playlist.edit-playlist') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('playlist.edit-playlist')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('playlists.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('playlist.playlist')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('playlists.edit', ['id' => $playlist->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('playlist.edit-playlist')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <form name="edit-playlist" method="POST" action="{{ route('playlists.update', ['id' => $playlist->id]) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <h2 class="text-center">@lang('playlist.edit-playlist')</h2>
                <div class="form-group">
                    <label for="name">@lang('playlist.title')</label>
                    <input type="text" name="name" class="form-control" value="{{ $playlist->name }}" required>
                </div>
                <div class="form-group">
                    <label for="description">@lang('playlist.desc')</label>
                    <textarea name="description" type="text" class="form-control">{{ $playlist->description }}</textarea>
                </div>
                <div class="form-group">
                    <label for="cover_image">@lang('playlist.cover')</label>
                    <input type="file" name="cover_image">
                </div>
                <label for="is_private" class="control-label">@lang('playlist.is-private')</label>
                <div class="radio-inline">
                <label><input type="radio" name="is_private" @if ($playlist->is_private == 1) checked @endif
                        value="1">@lang('common.yes')</label>
                </div>
                <div class="radio-inline">
                <label><input type="radio" name="is_private" @if ($playlist->is_private == 0) checked @endif
                        value="0">@lang('common.no')</label>
                </div>
                <br>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
