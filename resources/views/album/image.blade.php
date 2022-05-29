@extends('layout.default')

@section('title')
    <title>{{ __('common.add') }} {{ __('common.image') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('common.add') }} {{ __('common.image') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('albums.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.add') }} {{ __('common.image') }}
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="text-center">
                <form name="addimagetoalbum" method="POST" action="{{ route('images.store') }}"
                      enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="album_id" value="{{ $album->id }}"/>
                    <h2>{{ __('gallery.add-an-image-to') }} {{ $album->name }}</h2>
                    <div class="form-group">
                        <label for="type">{{ __('common.type') }}</label>
                        <label>
                            <select name="type" class="form-control">
                                <option value="Cover">Cover</option>
                                <option value="Disc">Disc</option>
                            </select>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="description">{{ __('common.image') }} {{ __('common.description') }}</label>
                        <label>
                            <textarea name="description" type="text" class="form-control"
                                      placeholder="Hi-Res Sleeve / Disc Art"></textarea>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="image">{{ __('gallery.select-image') }}</label>
                        <input type="file" name="image">
                    </div>
                    <button type="submit" class="btn btn-default">{{ __('common.add') }} {{ __('common.image') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
