@extends('layout.default')

@section('title')
    <title>{{ __('common.similar') }} - {{ $meta->title ?? $meta->name }}
        ({{ substr($meta->release_date ?? $meta->first_air_date, 0, 4) }}) - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description"
          content="{{ __('common.similar') }} - {{ $meta->title ?? $meta->name }} ({{ substr($meta->release_date ?? $meta->first_air_date, 0, 4) }})">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('torrents') }}" class="breadcrumb__link">
            {{ __('torrent.torrents') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.similar') }} - {{ $meta->title ?? $meta->name }} ({{ \substr($meta->release_date ?? $meta->first_air_date, 0, 4) }})
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block single" style="padding: 10px 15px !important;">
            <div class="text-center" style="padding-bottom: 8px;">
                <a role="button" class="btn btn-xs btn-primary"
                    href="{{ route('upload_form', ['category_id' => $categoryId, 'title' => $meta->title ?? $meta->name, 'imdb' => $torrent->imdb, 'tmdb' => $tmdbId]) }}">
                    <i class="fas fa-upload"></i> Upload
                </a>
                <a role="button" class="btn btn-xs btn-primary"
                    href="{{ route('add_request_form', ['title' => $meta->title ?? $meta->name, 'imdb' => $torrent->imdb, 'tmdb' => $tmdbId]) }}">
                    <i class="fas fa-hands-helping"></i> Request
                </a>
            </div>
            @if ($torrent->category->movie_meta)
                @include('torrent.partials.movie_meta', ['torrent' => $torrent])
            @endif

            @if ($torrent->category->tv_meta)
                @include('torrent.partials.tv_meta', ['torrent' => $torrent])
            @endif

            @if ($torrent->category->game_meta)
                @include('torrent.partials.game_meta')
            @endif
            @livewire('similar-torrent', ['categoryId' => $categoryId, 'tmdbId' => $tmdbId])
        </div>
    </div>
@endsection
