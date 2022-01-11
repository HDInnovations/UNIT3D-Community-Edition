@extends('layout.default')

@section('title')
    <title>{{ __('common.similar') }} - {{ $meta->title ?? $meta->name }}
        ({{ substr($meta->release_date ?? $meta->first_air_date, 0, 4) }}) - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description"
          content="{{ __('common.similar') }} - {{ $meta->title ?? $meta->name }} ({{ substr($meta->release_date ?? $meta->first_air_date, 0, 4) }})">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('torrents') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('torrent.torrents') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('torrents.similar', ['category_id' => $categoryId, 'tmdb' => $tmdbId]) }}"
           itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('common.similar') }} - {{ $meta->title ?? $meta->name }} ({{ substr($meta->release_date ?? $meta->first_air_date, 0, 4) }})</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block single">
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
