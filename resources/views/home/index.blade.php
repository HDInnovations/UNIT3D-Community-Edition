@extends('layout.default')

@section('page', 'page__home')

@section('main')
    @include('blocks.news')
    @if (! auth()->user()->chat_hidden)
        <div id="vue">
            @include('blocks.chat')
        </div>
        @vite('resources/js/unit3d/chat.js')
    @endif

    @include('blocks.featured')
    @livewire('random-media')
    @include('blocks.poll')
    @livewire('top-torrents')
    @include('blocks.top_uploaders')
    @include('blocks.latest_topics')
    @include('blocks.latest_posts')
    @include('blocks.online')
@endsection
