@extends('layout.default')

@section('title')
    <title>Forums - Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Forums - Staff Dashboard">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.forums.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Forums</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Forums</h2>
        <a href="{{ route('staff.forums.store') }}" class="btn btn-primary">Add New Category/Forum</a>
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Position</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($categories as $c)
                <tr class="success">
                    <td>
                        <a href="{{ route('staff.forums.update', ['id' => $c->id]) }}">{{ $c->name }}</a>
                    </td>
                    <td>Category</td>
                    <td>{{ $c->position }}</td>
                    <td><a href="{{ route('staff.forums.destroy', ['id' => $c->id]) }}"
                           class="btn btn-danger">Delete</a></td>
                </tr>
                @foreach ($c->getForumsInCategory()->sortBy('position') as $f)
                    <tr>
                        <td>
                            <a href="{{ route('staff.forums.update', ['id' => $f->id]) }}">---- {{ $f->name }}</a>
                        </td>
                        <td>Forum</td>
                        <td>{{ $f->position }}</td>
                        <td><a href="{{ route('staff.forums.destroy', ['id' => $f->id]) }}"
                               class="btn btn-danger">Delete</a></td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
        </div>
    </div>
@endsection
