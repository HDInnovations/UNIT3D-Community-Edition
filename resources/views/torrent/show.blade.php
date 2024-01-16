@extends('layout.default')

@section('title')
    <title>
        {{ $torrent->name }} - {{ __('torrent.torrents') }} - {{ config('other.title') }}
    </title>
@endsection

@section('meta')
    <meta
        name="description"
        content="{{ __('torrent.meta-desc', ['name' => $torrent->name]) }}!"
    />
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('torrents.index') }}" class="breadcrumb__link">
            {{ __('torrent.torrents') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $torrent->name }}
    </li>
@endsection

@section('main')
    @switch(true)
        @case($torrent->category->movie_meta)
            @include('torrent.partials.movie_meta', ['category' => $torrent->category, 'tmdb' => $torrent->tmdb])

            @break
        @case($torrent->category->tv_meta)
            @include('torrent.partials.tv_meta', ['category' => $torrent->category, 'tmdb' => $torrent->tmdb])

            @break
        @case($torrent->category->game_meta)
            @include('torrent.partials.game_meta', ['category' => $torrent->category, 'igdb' => $torrent->igdb])

            @break
        @default
            @include('torrent.partials.no_meta', ['category' => $torrent->category])

            @break
    @endswitch
    <h1 class="torrent__name">
        {{ $torrent->name }}
    </h1>
    @include('torrent.partials.general')
    @include('torrent.partials.buttons')

    {{-- Tools Block --}}
    @if (auth()->user()->group->is_internal || auth()->user()->group->is_editor || auth()->user()->group->is_modo || auth()->id() === $torrent->user_id)
        @include('torrent.partials.tools')
    @endif

    {{-- Audits Block --}}
    @if (auth()->user()->group->is_modo)
        @include('torrent.partials.audits')
        @include('torrent.partials.downloads')
    @endif

    {{-- MediaInfo Block --}}
    @if ($torrent->mediainfo !== null)
        @include('torrent.partials.mediainfo')
    @endif

    {{-- BDInfo Block --}}
    @if ($torrent->bdinfo !== null)
        @include('torrent.partials.bdinfo')
    @endif

    {{-- Description Block --}}
    @include('torrent.partials.description')

    {{-- Subtitles Block --}}
    @if ($torrent->category->movie_meta || $torrent->category->tv_meta)
        @include('torrent.partials.subtitles')
    @endif

    {{-- Extra Meta Block --}}
    @include('torrent.partials.extra_meta')

    {{-- Commments Block --}}
    @include('torrent.partials.comments')
@endsection
