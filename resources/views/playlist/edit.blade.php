@extends('layout.default')

@section('title')
    <title>@lang('common.edit') Playlist - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('common.edit') Playlist">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('playlists.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Playlists</span>
        </a>
    </li>
    <li>
        <a href="{{ route('playlists.edit', ['id' => $playlist->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('common.edit') Playlist</span>
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
                <h2 class="text-center">@lang('common.edit') Playlist</h2>
                <div class="form-group">
                    <label for="name">@lang('torrent.title')</label>
                    <input type="text" name="name" class="form-control" value="{{ $playlist->name }}" required>
                </div>
                <div class="form-group">
                    <label for="description">Playlist Description</label>
                    <textarea name="description" type="text" class="form-control">{{ $playlist->description }}</textarea>
                </div>
                <div class="form-group">
                    <label for="cover_image">Select A Cover Image</label>
                    <input type="file" name="cover_image">
                </div>
                <label for="is_private" class="control-label">Private Playlist?</label>
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
