@extends('layout.default')

@section('title')
    <title>{{ __('mediahub.persons') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('mediahub.persons') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('mediahub.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('mediahub.title') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('mediahub.persons.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('mediahub.persons') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="box container">
        @livewire('person-search')
    </div>
@endsection
