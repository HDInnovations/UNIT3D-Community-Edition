@extends('layout.default')

@section('title')
<title>Edit Request - {{ Config::get('other.title') }}</title>
@stop

@section('stylesheets')
<link rel="stylesheet" href="{{ url('files/wysibb/theme/default/wbbtheme.css') }}">
@stop

@section('breadcrumb')
<li>
    <a href="{{ url('requests') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Requests</span>
    </a>
</li>
<li>
    <a href="{{ url('edit_request') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Edit Request</span>
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
        <i class="fa fa-times text-danger"></i> Error: Your Request Rights Have Been Disabled
      </h1>
    <div class="separator"></div>
  <p class="text-center">If You Feel This Is In Error, Please Contact Staff!</p>
</div>
</div>
</div>
@else
<h1 class="upload-title">Edit Request</h1>
{{ Form::open(array('route' => array('edit_request', 'id' => $request->id))) }}
<div class="block">
            <div class="form-group">
                <label for="name">Title</label>
                <input type="text" name="name" class="form-control" value="{{ $request->name }}" required>
            </div>

            <div class="form-group">
               <label for="name">IMDB ID (Required)</label>
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
        <label for="category_id">Category</label>
        <select name="category_id" class="form-control">
          <option value="{{ $request->category->id }}" selected>{{ $request->category->name  }} (Current)</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="type">Type</label>
        <select name="type" class="form-control">
        <option value="{{ $request->type }}" selected>{{ $request->type  }} (Current)</option>
        @foreach($types as $type)
          <option value="{{ $type->name }}">{{ $type->name }}</option>
        @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="description">Description</label>
        <textarea id="request-form-description" name="description" cols="30" rows="10" class="form-control">{{ $request->description }}</textarea>
      </div>

      <button type="submit" class="btn btn-primary">Submit</button>
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
