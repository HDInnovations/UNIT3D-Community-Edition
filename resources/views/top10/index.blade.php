@extends('layout.default')

@section('title')
    <title>Top 10</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('torrent.torrents') }}
    </li>
@endsection

@section('nav-tabs')
    <li class="nav-tabV2">
        <a
            class="nav-tab__link"
            href="{{
                route('torrents.index', [
                    'view' => match (auth()->user()->torrent_layout) {
                        1 => 'card',
                        2 => 'group',
                        3 => 'poster',
                        default => 'list',
                    },
                ])
            }}"
        >
            {{ __('common.search') }}
        </a>
    </li>
    <li class="nav-tabV2--active">
        <a class="nav-tab--active__link" href="{{ route('top10.index') }}">Top 10</a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('rss.index') }}">
            {{ __('rss.rss') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('torrents.create', ['category_id' => 1]) }}">
            {{ __('common.upload') }}
        </a>
    </li>
@endsection

@section('main')
    @livewire('top10')
@endsection
