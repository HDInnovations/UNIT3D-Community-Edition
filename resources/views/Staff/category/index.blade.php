@extends('layout.default')

@section('content')
<div class="container box">
        <h2>Categories</h2>
        <a href="{{ route('staff_category_add') }}" class="btn btn-primary">Add a category</a>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $c)
                    <tr>
                        <td><a href="{{ route('staff_category_edit', array('slug' => $c->slug, 'id' => $c->id)) }}">{{ $c->name }}</a></td>
                        <td><a href="{{ route('staff_category_delete', array('slug' => $c->slug, 'id' => $c->id)) }}" class="btn btn-danger">Delete</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</div>
@stop
