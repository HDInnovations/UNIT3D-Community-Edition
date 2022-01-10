@extends('layout.default')

@section('title')
    <title>{{ __('common.create') }} {{ __('common.album') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('common.create') }} {{ __('common.album') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('albums.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('common.gallery') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('albums.create') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title"
                  class="l-breadcrumb-item-link-title">{{ __('common.create') }} {{ __('common.album') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <form name="createnewalbum" method="POST" action="{{ route('albums.store') }}"
                  enctype="multipart/form-data">
                @csrf
                <h2 class="text-center">{{ __('gallery.create-an-album') }}</h2>
                <h4 class="text-red text-center">
                    {{ __('gallery.create-an-album-tip') }}
                </h4>
                <div class="form-group">
                    <label for="name">IMDB</label>
                    <label>
                        <input name="imdb" type="text" class="form-control" placeholder="IMDB #"
                               value="{{ old('imdb') }}">
                    </label>
                </div>
                <div class="form-group">
                    <label for="description">{{ __('common.album') }} {{ __('common.description') }}</label>
                    <label>
                        <textarea name="description" type="text" class="form-control"
                                  placeholder="Sleeves / Disc Art">{{ old('description') }}</textarea>
                    </label>
                </div>
                <div class="form-group">
                    <label for="cover_image">{{ __('gallery.select-cover') }}</label>
                    <input type="file" name="cover_image">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">{{ __('common.add') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
