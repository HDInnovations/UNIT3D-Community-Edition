@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.tags.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Torrent Types</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.tags.edit', ['id' => $tag->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Edit Torrent Tag</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Edit A Torrent Tag (Genre)</h2>
        <form role="form" method="POST" action="{{ route('staff.types.update', ['id' => $tag->id]) }}">
            @csrf

            <div class="form-group">
                <label for="name">Name</label>
                <label>
                    <input type="text" class="form-control" name="name" value="{{ $tag->name }}">
                </label>
            </div>

            <button type="submit" class="btn btn-default">
                {{ trans('common.submit') }}
            </button>
        </form>
    </div>
@endsection
