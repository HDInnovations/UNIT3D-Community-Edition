@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.types.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Torrent Types</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Types</h2>
        <a href="{{ route('staff.types.store') }}" class="btn btn-primary">Add A Torrent Type</a>

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
            @foreach ($types as $type)
                <tr>
                    <td>
                        {{ $type->position }}
                    </td>
                    <td>
                        <a href="{{ route('staff.types.edit', ['id' => $type->id]) }}">
                            {{ $type->name }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('staff.types.edit', ['id' => $type->id]) }}" class="btn btn-warning">
                            Edit
                        </a>
                        <form action="{{ route('staff.types.destroy', ['id' => $type->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
    </div>
@endsection
