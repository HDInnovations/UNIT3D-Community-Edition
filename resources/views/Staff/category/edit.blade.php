@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.categories.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Torrent Categories</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.categories.edit', ['slug' => $category->slug, 'id' => $category->id]) }}"
           itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Edit Torrent Category</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Edit A Category</h2>
            <form role="form" method="POST" action="{{ route('staff.categories.update', ['slug' => $category->slug, 'id' => $category->id]) }}"
                enctype="multipart/form-data">
            @method('PATCH')
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <label>
                    <input type="text" class="form-control" name="name" value="{{ $category->name }}">
                </label>
            </div>
            <div class="form-group">
                <label for="name">Position</label>
                <label>
                    <input type="text" class="form-control" name="position" value="{{ $category->position }}">
                </label>
            </div>
            <div class="form-group">
                <label for="name">Icon (FontAwesome)</label>
                <label>
                    <input type="text" class="form-control" name="icon" value="{{ $category->icon }}">
                </label>
            </div>
            <div class="form-group">
                <label for="image">Select an Image If Not Using A FontAwesome Icon</label>
                <input type="file" name="image">
            </div>

            <label for="movie_meta" class="control-label">Movie Meta Data?</label>
            <div class="radio-inline">
                <label><input type="radio" name="movie_meta" @if ($category->movie_meta) checked @endif value="1">@lang('common.yes')</label>
            </div>
            <div class="radio-inline">
                <label><input type="radio" name="movie_meta" @if (!$category->movie_meta) checked @endif value="0">@lang('common.no')</label>
            </div>
            <br>
            <br>

            <label for="tv_meta" class="control-label">TV Meta Data?</label>
            <div class="radio-inline">
                <label><input type="radio" name="tv_meta" @if ($category->tv_meta) checked @endif value="1">@lang('common.yes')</label>
            </div>
            <div class="radio-inline">
                <label><input type="radio" name="tv_meta" @if (!$category->tv_meta) checked @endif value="0">@lang('common.no')</label>
            </div>
            <br>
            <br>

            <label for="game_meta" class="control-label">Game Meta Data?</label>
            <div class="radio-inline">
                <label><input type="radio" name="game_meta" @if ($category->game_meta) checked @endif value="1">@lang('common.yes')</label>
            </div>
            <div class="radio-inline">
                <label><input type="radio" name="game_meta" @if (!$category->game_meta) checked @endif value="0">@lang('common.no')</label>
            </div>
            <br>
            <br>

            <label for="music_meta" class="control-label">Music Meta Data?</label>
            <div class="radio-inline">
                <label><input type="radio" name="music_meta" @if ($category->music_meta) checked @endif value="1">@lang('common.yes')</label>
            </div>
            <div class="radio-inline">
                <label><input type="radio" name="music_meta" @if (!$category->music_meta) checked @endif value="0">@lang('common.no')</label>
            </div>
            <br>
            <br>

            <label for="no_meta" class="control-label">No Meta Data?</label>
            <div class="radio-inline">
                <label><input type="radio" name="no_meta" @if ($category->no_meta) checked @endif value="1">@lang('common.yes')</label>
            </div>
            <div class="radio-inline">
                <label><input type="radio" name="no_meta" @if (!$category->no_meta) checked @endif value="0">@lang('common.no')</label>
            </div>
            <br>
            <br>

        <button type="submit" class="btn btn-default">@lang('common.submit')</button>
        </form>
    </div>
@endsection
