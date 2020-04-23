@extends('layout.default')

@section('title')
    <title>@lang('common.gallery') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('common.gallery')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('albums.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('common.gallery')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="well text-center">
                <h2>@lang('gallery.welcome-title')</h2>
                <h4>@lang('gallery.welcome-text')</h4>
                <a href="{{ route('albums.create') }}" class="btn btn-md btn-success">@lang('gallery.create-new-album')</a>
            </div>
            <div class="row">
                @foreach ($albums as $album)
                    <div class="col-md-3 text-center">
                        <div class="thumbnail" style="min-height: 435px;">
                            <img alt="{{ $album->name }}" src="{{ url('files/img/' . $album->cover_image) }}">
                            <div class="caption">
                                <p class="text-bold">{{ $album->name }}</p>
                                <h4>{{ $album->description }}</h4>
                                <h4><span class="label label-default">{{ $album->images_count }} @lang('gallery.images')</span></h4>
                                <a href="{{ route('albums.show', ['id' => $album->id]) }}" class="btn btn-md btn-primary">@lang('common.view')
                                    @lang('common.album')</a>
                                <br>
                                <small>@lang('gallery.created'): {{ $album->created_at->toDayDateTimeString() }}</small>
                                <br>
                                <small>@lang('gallery.created-by'): {{ $album->user->username }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
