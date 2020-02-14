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
        <a href="{{ route('staff.categories.create') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                @lang('common.add')
                @lang('torrent.torrent')
                @lang('torrent.category')
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>
            @lang('common.add')
            @lang(trans_choice('common.a-an-art',false))
            @lang('torrent.category')
        </h2>
        <form role="form" method="POST" action="{{ route('staff.categories.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">@lang('common.name')</label>
                <label>
                    <input type="text" class="form-control" name="name">
                </label>
            </div>
            <div class="form-group">
                <label for="name">@lang('common.position')</label>
                <label>
                    <input type="text" class="form-control" name="position">
                </label>
            </div>
            <div class="form-group">
                <label for="name">Icon (FontAwesome)</label>
                <label>
                    <input type="text" class="form-control" name="icon"
                        placeholder="Example: {{ config('other.font-awesome') }} fa-rocket">
                </label>
            </div>
            <div class="form-group">
                <label for="image">
                    @lang('common.select')
                    @lang(trans_choice('common.a-an-art',false))
                    @lang('common.image')
                    (If Not Using A FontAwesome Icon)
                </label>
                <input type="file" name="image">
            </div>
    
            <label for="movie_meta" class="control-label">Movie Meta Data?</label>
            <div class="radio-inline">
                <label><input type="radio" name="movie_meta" value="1">@lang('common.yes')</label>
            </div>
            <div class="radio-inline">
                <label><input type="radio" name="movie_meta" value="0" checked>@lang('common.no')</label>
            </div>
            <br>
            <br>
    
            <label for="tv_meta" class="control-label">TV Meta Data?</label>
            <div class="radio-inline">
                <label><input type="radio" name="tv_meta" value="1">@lang('common.yes')</label>
            </div>
            <div class="radio-inline">
                <label><input type="radio" name="tv_meta" value="0" checked>@lang('common.no')</label>
            </div>
            <br>
            <br>
    
            <label for="game_meta" class="control-label">Game Meta Data?</label>
            <div class="radio-inline">
                <label><input type="radio" name="game_meta" value="1">@lang('common.yes')</label>
            </div>
            <div class="radio-inline">
                <label><input type="radio" name="game_meta" value="0" checked>@lang('common.no')</label>
            </div>
            <br>
            <br>
    
            <label for="music_meta" class="control-label">Music Meta Data?</label>
            <div class="radio-inline">
                <label><input type="radio" name="music_meta" value="1">@lang('common.yes')</label>
            </div>
            <div class="radio-inline">
                <label><input type="radio" name="music_meta" value="0" checked>@lang('common.no')</label>
            </div>
            <br>
            <br>
    
            <label for="no_meta" class="control-label">No Meta Data?</label>
            <div class="radio-inline">
                <label><input type="radio" name="no_meta" value="1">@lang('common.yes')</label>
            </div>
            <div class="radio-inline">
                <label><input type="radio" name="no_meta" value="0" checked>@lang('common.no')</label>
            </div>
            <br>
            <br>
    
            <button type="submit" class="btn btn-default">@lang('common.add')</button>
        </form>
    </div>
@endsection
