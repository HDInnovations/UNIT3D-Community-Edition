@extends('layout.default')

@section('title')
    <title>Top 10</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('top10.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Top 10</span>
        </a>
    </li>
@endsection

@section('content')
    <style>
        td {
            vertical-align: middle !important;
        }
    </style>
    <div class="box container">
        @livewire('top10')
    </div>
@endsection