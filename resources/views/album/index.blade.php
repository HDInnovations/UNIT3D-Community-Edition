@extends('layout.default')

@section('title')
    <title>{{ __('common.gallery') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('common.gallery') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('albums.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('common.gallery') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="well text-center">
                <h2>{{ __('gallery.welcome-title') }}</h2>
                <h4>{{ __('gallery.welcome-text') }}</h4>
                <a href="{{ route('albums.create') }}"
                   class="btn btn-md btn-success">{{ __('gallery.create-new-album') }}</a>
            </div>
            <div class="row">
                @foreach ($albums as $album)
                    <div class="col-md-3 text-center">
                        <div class="thumbnail" style="min-height: 435px;">
                            <img alt="{{ $album->name }}" src="{{ url('files/img/' . $album->cover_image) }}">
                            <div class="caption">
                                <p class="text-bold">{{ $album->name }}</p>
                                <h4>{{ $album->description }}</h4>
                                <h4>
                                    <span class="label label-default">{{ $album->images_count }} {{ __('gallery.images') }}</span>
                                </h4>
                                <a href="{{ route('albums.show', ['id' => $album->id]) }}"
                                   class="btn btn-md btn-primary">{{ __('common.view') }}
                                    {{ __('common.album') }}</a>
                                <br>
                                <small>{{ __('gallery.created') }}: {{ $album->created_at->toDayDateTimeString() }}</small>
                                <br>
                                <small>{{ __('gallery.created-by') }}: {{ $album->user->username }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
