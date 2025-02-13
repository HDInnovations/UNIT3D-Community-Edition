@extends('layout.with-main')

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('playlist.playlists') }}
    </li>
@endsection

@section('main')
    @livewire('playlist-search')
@endsection
