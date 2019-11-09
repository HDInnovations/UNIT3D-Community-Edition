@extends('layout.default')

@section('title')
    <title>Gallery - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Gallery">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('albums.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Gallery</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="well text-center">
                <h2>Welcome To The Gallery</h2>
                <h4>Here you will find albums that contain hi-res images of cover sleeves and disc art. Enjoy!</h4>
                <a href="{{ route('albums.create') }}" class="btn btn-md btn-success">Create New Album</a>
            </div>
            <div class="row">
                @foreach ($albums as $album)
                    <div class="col-md-3 text-center">
                        <div class="thumbnail" style="min-height: 435px;">
                            <img alt="{{ $album->name }}" src="{{ url('files/img/' . $album->cover_image) }}">
                            <div class="caption">
                                <p class="text-bold">{{ $album->name }}</p>
                                <h4>{{ $album->description }}</h4>
                                <h4><span class="label label-default">{{ $album->images_count }} image(s)</span></h4>
                                <a href="{{ route('albums.show', ['id' => $album->id]) }}" class="btn btn-md btn-primary">View
                                    Album</a>
                                <br>
                                <small>Created: {{ $album->created_at->toDayDateTimeString() }}</small>
                                <br>
                                <small>By: {{ $album->user->username }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
