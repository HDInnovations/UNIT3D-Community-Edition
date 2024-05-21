@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('playlist.playlists') }}
    </li>
@endsection

@section('content')
    @livewire('playlist-search')
@endsection
