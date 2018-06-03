@extends('layout.default')

@section('title')
    <title>Gallery - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Gallery">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('gallery') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Gallery</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="well text-center">
                <h1>Gallery Rules</h1>
                <li><p>BLAH</p></li>
                <li><p>BLAH</p></li>
                <li><p>BLAH</p></li>
                <a href="{{ route('create_album_form') }}" class="btn btn-lg btn-success">Create New Album</a>
            </div>
            <div class="row">
                @foreach($albums as $album)
                    <div class="col-md-3">
                        <div class="thumbnail" style="min-height: 400px;">
                            <img alt="{{$album->name}}" src="{{ url('files/img/' . $album->cover_image) }}">
                            <div class="caption">
                                <h3>{{ $album->name }}</h3>
                                <h4>{{ $album->description }}</h4>
                                <h4><span class="label label-default">{{ count($album->artwork) }} image(s)</span></h4>
                                <h4>Created: {{ $album->created_at->toDayDateTimeString() }}</h4>
                                <a href="{{ route('show_album', ['id' => $album->id]) }}"
                                      class="btn btn-md btn-primary">View Album</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
