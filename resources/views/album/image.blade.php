@extends('layout.default')

@section('title')
    <title>@lang('common.add') @lang('common.image') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('common.add') @lang('common.image')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('albums.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('common.gallery')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('images.create', ['id' => $album->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('common.add') @lang('common.image')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="text-center">
                <form name="addimagetoalbum" method="POST" action="{{ route('images.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="album_id" value="{{ $album->id }}" />
                    <h2>@lang('gallery.add-an-image-to') {{ $album->name }}</h2>
                    <div class="form-group">
                        <label for="type">@lang('common.type')</label>
                        <label>
                            <select name="type" class="form-control">
                                <option value="Cover">Cover</option>
                                <option value="Disc">Disc</option>
                            </select>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="description">@lang('common.image') @lang('common.description')</label>
                        <label>
                            <textarea name="description" type="text" class="form-control" placeholder="Hi-Res Sleeve / Disc Art"></textarea>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="image">@lang('gallery.select-image')</label>
                        <input type="file" name="image">
                    </div>
                    <button type="submit" class="btn btn-default">@lang('common.add') @lang('common.image')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
