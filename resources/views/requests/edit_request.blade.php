@extends('layout.default')

@section('title')
<title>{{ trans('request.edit-request') }} - {{ Config::get('other.title') }}</title>
@stop

@section('stylesheets')
<link rel="stylesheet" href="{{ url('files/wysibb/theme/default/wbbtheme.css') }}">
@stop

@section('breadcrumb')
<li>
    <a href="{{ url('requests') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('request.requests') }}</span>
    </a>
</li>
<li>
    <a href="{{ url('edit_request') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('request.edit-request') }}</span>
    </a>
</li>
@stop

@section('content')
<div class="container">
@if($user->can_request == 0)
<div class="container">
  <div class="jumbotron shadowed">
    <div class="container">
      <h1 class="mt-5 text-center">
        <i class="fa fa-times text-danger"></i> {{ trans('request.no-privileges') }}
      </h1>
    <div class="separator"></div>
  <p class="text-center">{{ trans('request.no-privileges-desc') }}!</p>
</div>
</div>
</div>
@else
<h1 class="upload-title">{{ trans('request.edit-request') }}</h1>
{{ Form::open(array('route' => array('edit_request', 'id' => $request->id))) }}
<div class="block">
            <div class="form-group">
                <label for="name">{{ trans('request.title') }}</label>
                <input type="text" name="name" class="form-control" value="{{ $request->name }}" required>
            </div>

            <div class="form-group">
               <label for="name">IMDB ID ({{ trans('request.required') }})</label>
               <input type="number" name="imdb" value="{{ $request->imdb }}" class="form-control" required>
           </div>

           <div class="form-group">
              <label for="name">TMDB ID </label>
              <input type="number" name="tmdb" value="{{ $request->tmdb }}" class="form-control" required>
          </div>

           <div class="form-group">
               <label for="name">TVDB ID </label>
               <input type="number" name="tvdb" value="{{ $request->tvdb }}" class="form-control" required>
           </div>

           <div class="form-group">
               <label for="name">MAL ID </label>
               <input type="number" name="mal" value="{{ $request->mal }}" class="form-control" required>
           </div>

      <div class="form-group">
        <label for="category_id">{{ trans('request.category') }}</label>
        <select name="category_id" class="form-control">
          <option value="{{ $request->category->id }}" selected>{{ $request->category->name  }} ({{ trans('request.current') }})</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="type">{{ trans('request.type') }}</label>
        <select name="type" class="form-control">
        <option value="{{ $request->type }}" selected>{{ $request->type  }} ({{ trans('request.current') }})</option>
        @foreach($types as $type)
          <option value="{{ $type->name }}">{{ $type->name }}</option>
        @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="description">{{ trans('request.description') }}</label>
        <textarea id="request-form-description" name="description" cols="30" rows="10" class="form-control">{{ $request->description }}</textarea>
      </div>

      <button type="submit" class="btn btn-primary">{{ trans('common.submit') }}</button>
      {{ Form::close() }}
    <br>
  </div>
@endif
</div>
@stop

@section('javascripts')
<script type="text/javascript" src="{{ url('files/wysibb/jquery.wysibb.js') }}"></script>
<script>
$(document).ready(function() {
    var wbbOpt = { }
    $("#request-form-description").wysibb(wbbOpt);
});
</script>
@stop
