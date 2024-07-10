@extends('layout.default')

@section('page', 'page__home')

@section('main')
    @include('blocks.news')

    @if (! auth()->user()->settings?->chat_hidden)
        @vite('resources/js/unit3d/chat.js')
        <livewire:chatbox />
    @endif

    @include('blocks.featured')
    @livewire('random-media')
    @include('blocks.poll')
    @livewire('top-torrents')
    @livewire('top-users')
    @include('blocks.latest_topics')
    @include('blocks.latest_posts')
    @include('blocks.online')
@endsection
