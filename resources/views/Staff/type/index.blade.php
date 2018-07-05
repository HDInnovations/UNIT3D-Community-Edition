@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff_type_index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Torrent Types</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Types</h2>
        <a href="{{ route('staff_type_add') }}" class="btn btn-primary">Add A Torrent Type</a>

        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>Position</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($types as $t)
                <tr>
                    <td>{{ $t->position }}</td>
                    <td>
                        <a href="{{ route('staff_type_edit_form', ['slug' => $t->slug, 'id' => $t->id]) }}">{{ $t->name }}</a>
                    </td>
                    <td>
                        <a href="{{ route('staff_type_edit_form', ['slug' => $t->slug, 'id' => $t->id]) }}"
                           class="btn btn-warning">Edit</a>
                        <a href="{{ route('staff_type_delete', ['slug' => $t->slug, 'id' => $t->id]) }}"
                           class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
    </div>
@endsection
