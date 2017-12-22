@extends('layout.default')

@section('stylesheets')
<link rel="stylesheet" href="{{ url('files/wysibb/theme/default/wbbtheme.css') }}">
@stop

@section('breadcrumb')
<li>
  <a href="{{ route('edit', ['slug' => $tor->slug, 'id' => $tor->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.torrent') }} {{ trans('common.edit') }}</span>
  </a>
</li>
@stop

@section('content')
<div class="container">
    <div class="col-md-10">
        <h2>{{ trans('common.edit') }}: {{ $tor->name }}</h2>
        <div class="block">
         {{ Form::open(array('route' => array('edit', 'slug' => $tor->slug, 'id' => $tor->id))) }}
         {{ csrf_field() }}
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="name" value="{{ $tor->name }}">
            </div>

           <div class="form-group">
              <label for="name">IMDB ID (Required)</label>
              <input type="number" name="imdb" value="{{ $tor->imdb }}" class="form-control">
          </div>

          <div class="form-group">
             <label for="name">TMDB ID </label>
             <input type="number" name="tmdb" value="{{ $tor->tmdb }}" class="form-control">
         </div>

          <div class="form-group">
              <label for="name">TVDB ID </label>
              <input type="number" name="tvdb" value="{{ $tor->tvdb }}" class="form-control">
          </div>

          <div class="form-group">
              <label for="name">MAL ID </label>
              <input type="number" name="mal" value="{{ $tor->mal }}" class="form-control">
          </div>

            <div class="form-group">
              <label for="category_id">{{ trans('torrent.category') }}</label>
              <select name="category_id" class="form-control">
                  <option value="{{ $tor->category->id }}" selected>{{ $tor->category->name  }} (Current)</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="type">{{ trans('torrent.type') }}</label>
              <select name="type" class="form-control">
                  <option value="{{ $tor->type }}" selected>{{ $tor->type  }} (Current)</option>
                  @foreach($types as $type)
                    <option value="{{ $type->name }}">{{ $type->name }}</option>
                  @endforeach
              </select>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="upload-form-description" name="description" cols="30" rows="10" class="form-control">{{ $tor->description }}</textarea>
            </div>

            <div class="form-group">
                <label for="description">MediaInfo</label>
                <textarea name="mediainfo" cols="30" rows="10" class="form-control">{{ $tor->mediainfo }}</textarea>
            </div>

            <label for="hidden" class="control-label">Anonymous Upload?</label>
            <div class="radio-inline">
                <label><input type="radio" name="anonymous" @if($tor->anon == 1) checked @endif value="1">YES</label>
              </div>
            <div class="radio-inline">
                <label><input type="radio" name="anonymous" @if($tor->anon == 0) checked @endif value="0">NO</label>
            </div>
            <br>
            <br>
            <label for="hidden" class="control-label">Stream Optimized?</label>
            <div class="radio-inline">
                <label><input type="radio" name="stream" @if($tor->stream == 1) checked @endif value="1">YES</label>
              </div>
            <div class="radio-inline">
                <label><input type="radio" name="stream" @if($tor->stream == 0) checked @endif value="0">NO</label>
            </div>
            <br>
            <br>
            <label for="hidden" class="control-label">SD Content?</label>
            <div class="radio-inline">
                <label><input type="radio" name="sd" @if($tor->sd == 1) checked @endif value="1">YES</label>
              </div>
            <div class="radio-inline">
                <label><input type="radio" name="sd" @if($tor->sd == 0) checked @endif value="0">NO</label>
            </div>
            <br>
            <br>
            <button type="submit" class="btn btn-primary">{{ trans('common.submit') }}</button>
        {{ Form::close() }}
    </div>
</div>
</div>
@stop

@section('javascripts')
<script type="text/javascript" src="{{ url('files/wysibb/jquery.wysibb.js') }}"></script>
<script>
$(document).ready(function() {
    var wbbOpt = { }

    $("#upload-form-description").wysibb(wbbOpt);
});
</script>
@stop
