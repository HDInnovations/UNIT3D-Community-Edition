@extends('layout.default')

@section('title')
	<title>Missing Media</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        Missing Media
    </li>
@endsection

@section('content')
	<style>
        td {
            vertical-align: middle !important;
        }
	</style>
	<div class="box container">
		@livewire('missing-media-search')
	</div>
@endsection