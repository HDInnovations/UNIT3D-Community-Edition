@extends('layout.default')

@section('stylesheets')
<link rel="stylesheet" href="{{ url('files/wysibb/theme/default/wbbtheme.css') }}">
@stop

@section('content')
<div class="container box">
        <h2>Add a category</h2>
        {{ Form::open(array('route' => 'staff_category_add')) }}
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" cols="30" rows="10" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-default">Add</button>
        {{ Form::close() }}
</div>
@stop

@section('javascripts')
<script type="text/javascript" src="{{ url('files/wysibb/jquery.wysibb.js') }}"></script>
<script>
$(document).ready(function() {
    var wbbOpt = { }
    $("#description").wysibb(wbbOpt);
});
</script>
@stop
