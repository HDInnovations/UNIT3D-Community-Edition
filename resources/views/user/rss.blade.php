@extends('layout.default')

@section('title')
    <title>{{ $user->username }} - Rss Configuration - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="#" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                RSS Configuration
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="contrainer">
        @foreach ($labourTypes as $id => $name)
            <div class="checkbox">
                <input type="checkbox" name="labour_types[]" value="{{ $id }}"> {{ $name }}
            </div>
        @endforeach
    </div>
@endsection
