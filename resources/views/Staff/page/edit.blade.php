@extends('layout.default')

@section('stylesheets')
<link rel="stylesheet" href="{{ url('files/wysibb/theme/default/wbbtheme.css') }}">
@endsection

@section('content')
<div class="container box">
		<h2>Add a new page</h2>
        {{ Form::open(['route' => ['staff_page_edit', 'slug' => $page->slug, 'id' => $page->id]]) }}
        {{ csrf_field() }}
            <div class="form-group">
                <label for="name">Page Name</label>
                <input type="text" name="name" class="form-control" value="{{ $page->name }}">
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="content" cols="30" rows="10" class="form-control">{{ $page->content }}</textarea>
            </div>

            <button type="submit" class="btn btn-default">Save</button>
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
