@extends('layout.default')

@section('title')
	<title>Missing Media</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        Missing Media
    </li>
@endsection

@section('main')
    @livewire('missing-media-search')
@endsection
