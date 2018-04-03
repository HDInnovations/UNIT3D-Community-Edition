@extends('layout.default')

@section('title')
<title>Articles - Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('stylesheets')
<link rel="stylesheet" href="{{ url('files/wysibb/theme/default/wbbtheme.css') }}">
@endsection

@section('breadcrumb')
<li>
  <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
  </a>
</li>
<li>
  <a href="{{ route('staff_article_index') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Articles</span>
  </a>
</li>
<li class="active">
  <a href="{{ route('staff_article_add') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Add Article</span>
  </a>
</li>
@endsection

@section('content')
<div class="container box">
  <h2>Add a post</h2>
  {{ Form::open(array('route' => 'staff_article_add', 'files' => true)) }}
  <div class="form-group">
    <label for="title">Title</label>
    <input type="text" class="form-control" name="title" required>
  </div>

  <div class="form-group">
    <label for="image">Image thumbnail</label>
    <input type="file" name="image">
  </div>

  <div class="form-group">
    <label for="content">The content of your article</label>
    <textarea name="content" id="content" cols="30" rows="10" class="form-control"></textarea>
  </div>

  <button type="submit" class="btn btn-default">Post</button>
  {{ Form::close() }}
</div>
@endsection

@section('javascripts')
<script type="text/javascript" src="{{ url('files/wysibb/jquery.wysibb.js') }}"></script>
<script>
$(document).ready(function() {
    var wbbOpt = { }
    $("#content").wysibb(wbbOpt);
});
</script>
@endsection
