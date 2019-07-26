@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
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
            @foreach ($categories as $c)
                <tr>
                    <td>
                        {{ $c->position }}
                    </td>
                    <td>
                        <a href="{{ route('staff.categories.edit', ['slug' => $c->slug, 'id' => $c->id]) }}">{{ $c->name }}</a>
                    </td>
                    <td>
                        <i class="{{ $c->icon }}" aria-hidden="true"></i>
                    </td>
                    <td>
                        @if ($c->image != null)
                            <img alt="{{ $c->name }}" src="{{ url('files/img/' . $c->image) }}">
                        @else
                            <span>N/A</span>
                        @endif
                    </td>
                    <td>
                        @if ($c->movie_meta)
                            <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                        @else
                            <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                        @endif
                    </td>
                    <td>
                        @if ($c->tv_meta)
                            <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                        @else
                            <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                        @endif
                    </td>
                    <td>
                        @if ($c->game_meta)
                            <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                        @else
                            <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                        @endif
                    </td>
                    <td>
                        @if ($c->music_meta)
                            <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                        @else
                            <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                        @endif
                    </td>
                    <td>
                        @if ($c->no_meta)
                            <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                        @else
                            <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('staff.categories.edit', ['slug' => $c->slug, 'id' => $c->id]) }}"
                           class="btn btn-warning">Edit</a>
                        <form action="{{ route('staff.categories.destroy', ['slug' => $c->slug, 'id' => $c->id]) }}" method="POST">
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
