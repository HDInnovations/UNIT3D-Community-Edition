@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.categories.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Torrent Categories</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Categories</h2>
        <a href="{{ route('staff.categories.create') }}" class="btn btn-primary">Add A Category</a>

        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>Position</th>
                <th>Name</th>
                <th>Icon</th>
                <th>Image</th>
                <th>Movie Meta</th>
                <th>TV Meta</th>
                <th>Game Meta</th>
                <th>Music Meta</th>
                <th>No Meta</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>
                        {{ $category->position }}
                    </td>
                    <td>
                        <a href="{{ route('staff.categories.edit', ['id' => $category->id]) }}">{{ $category->name }}</a>
                    </td>
                    <td>
                        <i class="{{ $category->icon }}" aria-hidden="true"></i>
                    </td>
                    <td>
                        @if ($category->image != null)
                            <img alt="{{ $category->name }}" src="{{ url('files/img/' . $category->image) }}">
                        @else
                            <span>N/A</span>
                        @endif
                    </td>
                    <td>
                        @if ($category->movie_meta)
                            <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                        @else
                            <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                        @endif
                    </td>
                    <td>
                        @if ($category->tv_meta)
                            <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                        @else
                            <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                        @endif
                    </td>
                    <td>
                        @if ($category->game_meta)
                            <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                        @else
                            <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                        @endif
                    </td>
                    <td>
                        @if ($category->music_meta)
                            <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                        @else
                            <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                        @endif
                    </td>
                    <td>
                        @if ($category->no_meta)
                            <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                        @else
                            <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('staff.categories.edit', ['id' => $category->id]) }}" class="btn btn-warning">
                            Edit
                        </a>
                        <form action="{{ route('staff.categories.destroy', ['id' => $category->id]) }}" method="POST">
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
