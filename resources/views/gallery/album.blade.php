@extends('layout.default')

@section('title')
    <title>Album - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Album">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('gallery') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Gallery</span>
        </a>
    </li>
    <li>
        <a href="{{ route('show_album', ['id' => $album->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $album->name }} Album</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="well">
                <img class="media-object pull-left" alt="{{ $album->name }}"
                     src="{{ url('files/img/' . $album->cover_image) }}"
                     height="175px" width="auto" style="margin-right: 20px;">
                <div class="media-body">
                    <h2 class="media-heading">Album Name:</h2>
                    <p class="text-bold">{{ $album->name }}</p>
                    <div class="media">
                        <h2 class="media-heading">Album Description:</h2>
                        <p class="text-bold">{{ $album->description }}</p>
                        <a href="{{ route('add_image', ['id' => $album->id]) }}">
                            <button type="button" class="btn btn-success btn-md">Add New Image to Album</button>
                        </a>
                        @if(auth()->user()->group->is_modo || auth()->user()->id == $album->user_id && Carbon\Carbon::now()->lt($album->created_at->addDay()))
                        <a href="{{ route('delete_album', ['id' => $album->id]) }}"
                           onclick="return confirm('Are you sure?')">
                            <button type="button" class="btn btn-danger btn-md">Delete Album</button>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($album->images as $photo)
                    <div class="col-lg-3">
                        <div class="thumbnail" style="max-height: 450px; min-height: 400px;">
                            <img alt="{{ $album->name }}" src="{{ url('files/img/' . $photo->image) }}"
                                 style="max-height: 300px; min-height: 300px; border: 6px solid gray; border-radius: 5px;">
                            <div class="caption text-center">
                                <h4 class="label label-success">{{ $photo->type }}</h4>
                                <br>
                                <h4 class="badge badge-user"> Uploaded By: {{ $photo->user->username }}</h4>
                                <br>
                                <button type="button" class="btn btn-sm"><i class="fa fa-heart text-pink"> </i>
                                </button>
                                <a href="{{ route('image_download', ['id' => $photo->id]) }}">
                                <button type="button" class="btn btn-sm"><i
                                            class="fa fa-download text-green"> {{ $photo->downloads }}</i>
                                </button>
                                </a>
                                @if(auth()->user()->group->is_modo || auth()->user()->id === $photo->user_id)
                                <a href="{{ route('delete_image', ['id' => $photo->id]) }}">
                                    <button type="button" class="btn btn-sm"><i class="fa fa-times text-red"> </i>
                                    </button>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

