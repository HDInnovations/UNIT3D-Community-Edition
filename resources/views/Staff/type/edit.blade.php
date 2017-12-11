@extends('layout.default')

@section('content')
<div class="container box">
        <h2>Edit a torrent type</h2>
        {{ Form::open(array('route' => array('staff_type_edit', 'slug' => $type->slug, 'id' => $type->id))) }}
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" value="{{ $type->name }}">
            </div>

            <button type="submit" class="btn btn-default">Save</button>
        {{ Form::close() }}
</div>
@stop
