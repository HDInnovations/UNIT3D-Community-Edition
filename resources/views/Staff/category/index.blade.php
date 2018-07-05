@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff_category_index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Torrent Categories</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Categories</h2>
        <a href="{{ route('staff_category_add') }}" class="btn btn-primary">Add A Category</a>

        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>Position</th>
                <th>Name</th>
                <th>Icon</th>
                <th>Meta?</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $c)
                <tr>
                    <td>{{ $c->position }}</td>
                    <td>
                        <a href="{{ route('staff_category_edit_form', ['slug' => $c->slug, 'id' => $c->id]) }}">{{ $c->name }}</a>
                    </td>
                    <td><i class="{{ $c->icon }}" aria-hidden="true"></i></td>
                    <td>@if($c->meta == 1) YES @else NO @endif</td>
                    <td>
                        <a href="{{ route('staff_category_edit_form', ['slug' => $c->slug, 'id' => $c->id]) }}"
                           class="btn btn-warning">Edit</a>
                        <a href="{{ route('staff_category_delete', ['slug' => $c->slug, 'id' => $c->id]) }}"
                           class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
    </div>
@endsection
