@extends('layout.default')

@section('title')
    <title>{{ trans('torrent.categories') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('categories') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.categories') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="header gradient green">
            <div class="inner_content">
                <h1>{{ trans('torrent.categories') }}</h1>
            </div>
        </div>
        <div class="blocks">
            @foreach($categories as $c)
                <a href="{{ route('category', ['slug' => $c->slug, 'id' => $c->id]) }}">
                    <div class="general media_blocks">
                        <h2><i class="{{ $c->icon }}"></i> {{ $c->name }}</h2>
                        <span></span>
                        <h2>{{ $c->num_torrent }} {{ trans('torrent.torrents') }}</h2>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
