@extends('layout.default')

@section('title')
    <title>Create Album - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Create Album">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('gallery') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Gallery</span>
        </a>
    </li>
    <li>
        <a href="{{ route('create_album_form') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Create Album</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="text-center">
                <form name="createnewalbum" method="POST" action="{{ route('create_album') }}"
                      enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <h2>Create An Album</h2>
                    <div class="form-group">
                        <label for="name">IMDB</label>
                        <input name="imdb" type="text" class="form-control" placeholder="IMDB #"
                               value="{{ old('imdb') }}">
                    </div>
                    <div class="form-group">
                        <label for="description">Album Description</label>
                        <textarea name="description" type="text" class="form-control"
                                  placeholder="Album Description">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="cover_image">Select A Cover Image</label>
                        <input type="file" name="cover_image">
                    </div>
                    <button type="submit" class="btn btn-primary">{{ trans('common.add') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection

