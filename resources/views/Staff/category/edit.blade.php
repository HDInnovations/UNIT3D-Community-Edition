@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.categories.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.torrent-categories')</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.categories.edit', ['id' => $category->id]) }}" itemprop="url"
            class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                @lang('common.edit')
                @lang('torrent.torrent')
                @lang('torrent.category')
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>
            @lang('common.edit')
            @lang(trans_choice('common.a-an-art',false))
            @lang('torrent.category')
        </h2>
        <form role="form" method="POST" action="{{ route('staff.categories.update', ['id' => $category->id]) }}"
            enctype="multipart/form-data">
            @method('PATCH')
            @csrf
            <div class="form-group">
                <label for="name">@lang('common.name')</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}">
            </div>
            <div class="form-group">
                <label for="position">@lang('common.position')</label>
                <input type="text" class="form-control" id="position" name="position" value="{{ $category->position }}">
            </div>
            <div class="form-group">
                <label for="icon">@lang('common.icon') (FontAwesome)</label>
                <input type="text" class="form-control" id="icon" name="icon" value="{{ $category->icon }}">
            </div>
            <div class="form-group">
                <label for="image">
                    @lang('common.select')
                    @lang(trans_choice('common.a-an-art',false))
                    @lang('common.image')
                    (If Not Using A FontAwesome Icon)
                </label>
                <input type="file" id="image" name="image">
            </div>

            <label for="movie_meta" class="control-label">Movie Meta Data?</label>
            <div class="radio-inline">
                <input type="radio" id="movie_meta" name="movie_meta" @if ($category->movie_meta) checked @endif value="1">@lang('common.yes')
            </div>
            <div class="radio-inline">
                <input type="radio" id="movie_meta" name="movie_meta" @if (!$category->movie_meta) checked @endif value="0">@lang('common.no')
            </div>
            <br>
            <br>

            <label for="tv_meta" class="control-label">TV Meta Data?</label>
            <div class="radio-inline">
                <input type="radio" id="tv_meta" name="tv_meta" @if ($category->tv_meta) checked @endif value="1">@lang('common.yes')
            </div>
            <div class="radio-inline">
                <input type="radio" id="tv_meta" name="tv_meta" @if (!$category->tv_meta) checked @endif value="0">@lang('common.no')
            </div>
            <br>
            <br>

            <label for="game_meta" class="control-label">Game Meta Data?</label>
            <div class="radio-inline">
                <input type="radio" id="game_meta" name="game_meta" @if ($category->game_meta) checked @endif value="1">@lang('common.yes')
            </div>
            <div class="radio-inline">
                <input type="radio" id="game_meta" name="game_meta" @if (!$category->game_meta) checked @endif value="0">@lang('common.no')
            </div>
            <br>
            <br>

            <label for="music_meta" class="control-label">Music Meta Data?</label>
            <div class="radio-inline">
                <input type="radio" id="music_meta" name="music_meta" @if ($category->music_meta) checked @endif value="1">@lang('common.yes')
            </div>
            <div class="radio-inline">
                <input type="radio" id="music_meta" name="music_meta" @if (!$category->music_meta) checked @endif value="0">@lang('common.no')
            </div>
            <br>
            <br>

            <label for="no_meta" class="control-label">No Meta Data?</label>
            <div class="radio-inline">
                <input type="radio" id="no_meta" name="no_meta" @if ($category->no_meta) checked @endif value="1">@lang('common.yes')
            </div>
            <div class="radio-inline">
                <input type="radio" id="no_meta" name="no_meta" @if (!$category->no_meta) checked @endif value="0">@lang('common.no')
            </div>
            <br>
            <br>

            <button type="submit" class="btn btn-default">@lang('common.submit')</button>
        </form>
    </div>
@endsection
