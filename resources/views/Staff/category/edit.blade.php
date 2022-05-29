@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.categories.index') }}" class="breadcrumb__link">
            {{ __('staff.torrent-categories') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.edit') }}
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>
            {{ __('common.edit') }}
            {{ trans_choice('common.a-an-art',false) }}
            {{ __('torrent.category') }}
        </h2>
        <form role="form" method="POST" action="{{ route('staff.categories.update', ['id' => $category->id]) }}"
              enctype="multipart/form-data">
            @method('PATCH')
            @csrf
            <div class="form-group">
                <label for="name">{{ __('common.name') }}</label>
                <label>
                    <input type="text" class="form-control" name="name" value="{{ $category->name }}">
                </label>
            </div>
            <div class="form-group">
                <label for="name">{{ __('common.position') }}</label>
                <label>
                    <input type="text" class="form-control" name="position" value="{{ $category->position }}">
                </label>
            </div>
            <div class="form-group">
                <label for="name">{{ __('common.icon') }} (FontAwesome)</label>
                <label>
                    <input type="text" class="form-control" name="icon" value="{{ $category->icon }}">
                </label>
            </div>
            <div class="form-group">
                <label for="image">
                    {{ __('common.select') }}
                    {{ trans_choice('common.a-an-art',false) }}
                    {{ __('common.image') }}
                    (If Not Using A FontAwesome Icon)
                </label>
                <input type="file" name="image">
            </div>

            <label for="movie_meta" class="control-label">Movie Meta Data?</label>
            <div class="radio-inline">
                <label><input type="radio" name="movie_meta" @if ($category->movie_meta) checked @endif
                    value="1">{{ __('common.yes') }}</label>
            </div>
            <div class="radio-inline">
                <label><input type="radio" name="movie_meta" @if (!$category->movie_meta) checked @endif
                    value="0">{{ __('common.no') }}</label>
            </div>
            <br>
            <br>

            <label for="tv_meta" class="control-label">TV Meta Data?</label>
            <div class="radio-inline">
                <label><input type="radio" name="tv_meta" @if ($category->tv_meta) checked @endif
                    value="1">{{ __('common.yes') }}</label>
            </div>
            <div class="radio-inline">
                <label><input type="radio" name="tv_meta" @if (!$category->tv_meta) checked @endif
                    value="0">{{ __('common.no') }}</label>
            </div>
            <br>
            <br>

            <label for="game_meta" class="control-label">Game Meta Data?</label>
            <div class="radio-inline">
                <label><input type="radio" name="game_meta" @if ($category->game_meta) checked @endif
                    value="1">{{ __('common.yes') }}</label>
            </div>
            <div class="radio-inline">
                <label><input type="radio" name="game_meta" @if (!$category->game_meta) checked @endif
                    value="0">{{ __('common.no') }}</label>
            </div>
            <br>
            <br>

            <label for="music_meta" class="control-label">Music Meta Data?</label>
            <div class="radio-inline">
                <label><input type="radio" name="music_meta" @if ($category->music_meta) checked @endif
                    value="1">{{ __('common.yes') }}</label>
            </div>
            <div class="radio-inline">
                <label><input type="radio" name="music_meta" @if (!$category->music_meta) checked @endif
                    value="0">{{ __('common.no') }}</label>
            </div>
            <br>
            <br>

            <label for="no_meta" class="control-label">No Meta Data?</label>
            <div class="radio-inline">
                <label><input type="radio" name="no_meta" @if ($category->no_meta) checked @endif
                    value="1">{{ __('common.yes') }}</label>
            </div>
            <div class="radio-inline">
                <label><input type="radio" name="no_meta" @if (!$category->no_meta) checked @endif
                    value="0">{{ __('common.no') }}</label>
            </div>
            <br>
            <br>

            <button type="submit" class="btn btn-default">{{ __('common.submit') }}</button>
        </form>
    </div>
@endsection
