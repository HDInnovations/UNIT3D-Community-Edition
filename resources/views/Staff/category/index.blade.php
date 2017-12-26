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
@stop

@section('content')
<div class="container box">
        <h2>Categories</h2>
        <a href="{{ route('staff_category_add') }}" class="btn btn-primary">Add A Category</a>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Icon</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $c)
                    <tr>
                        <td><a href="{{ route('staff_category_edit', array('slug' => $c->slug, 'id' => $c->id)) }}">{{ $c->name }}</a></td>
                        <td><i class="{{ $c->icon }}" aria-hidden="true"></i></td>
                        <td>
                            <a href="{{ route('staff_category_edit', array('slug' => $c->slug, 'id' => $c->id)) }}" class="btn btn-warning">Edit</a>
                            <a href="{{ route('staff_category_delete', array('slug' => $c->slug, 'id' => $c->id)) }}" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</div>
@stop
