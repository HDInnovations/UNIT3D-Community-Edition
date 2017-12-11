@extends('layout.default')

@section('content')
<div class="container box">
        <h2>Add a torrent type</h2>
        {{ Form::open(array('route' => 'staff_type_add')) }}
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name">
            </div>

            <button type="submit" class="btn btn-default">Add</button>
        {{ Form::close() }}
</div>
@stop
