@extends('layout.default')

@section('title')
<title>Add Request - {{ Config::get('other.title') }}</title>
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
    <a href="{{ url('add_request') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Add Request</span>
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
  <div class="col-sm-12">
      <div class="well well-sm mt-20">
          <p class="lead text-orange text-center">All Requests Must Contain A IMDB Number<br><strong>NO EXCEPTIONS!</strong>
          </p>
      </div>
  </div>
<h1 class="upload-title">Add New Request</h1>
{{ Form::open(['route' => 'add_request', 'method' => 'post', 'role' => 'form', 'class' => 'upload-form']) }}
<div class="block">
  <div class="upload col-md-12">
            <div class="form-group">
                <label for="name">Title</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
               <label for="name">IMDB ID (Required)</label>
               <input type="number" name="imdb" value="0" class="form-control" required>
           </div>

           <div class="form-group">
              <label for="name">TMDB ID </label>
              <input type="number" name="tmdb" value="0" class="form-control" required>
          </div>

           <div class="form-group">
               <label for="name">TVDB ID </label>
               <input type="number" name="tvdb" value="0" class="form-control" required>
           </div>

           <div class="form-group">
               <label for="name">MAL ID </label>
               <input type="number" name="mal" value="0" class="form-control" required>
           </div>

      <div class="form-group">
        <label for="category_id">Category</label>
        <select name="category_id" class="form-control">
          @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="type">Type</label>
        <select name="type" class="form-control">
          @foreach($types as $type)
            <option value="{{ $type->name }}">{{ $type->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="description">Description</label>
        <textarea id="request-form-description" name="description" cols="30" rows="10" class="form-control" required></textarea>
      </div>

      <div class="form-group">
        <label for="bonus_point">Reward</label>
          <input class="form-control" name="bounty" type="number" min='100' value="100" required>
          <span class="help-block">How much bonus point would you like to reward? Minimum 100 BP</span>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Submit</button>
      {{ Form::close() }}
    <br>
  </div>
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
