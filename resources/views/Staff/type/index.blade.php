@extends('layout.default')

@section('content')
<div class="container box">
        <h2>Types</h2>
        <a href="{{ route('staff_type_add') }}" class="btn btn-primary">Add a torrent type</a>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($types as $t)
                    <tr>
                        <td><a href="{{ route('staff_type_edit', array('slug' => $t->slug, 'id' => $t->id)) }}">{{ $t->name }}</a></td>
                        <td><a href="{{ route('staff_type_delete', array('slug' => $t->slug, 'id' => $t->id)) }}" class="btn btn-danger">Delete</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</div>
@stop
