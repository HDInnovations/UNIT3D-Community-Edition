@extends('layout.default')

@section('title')
<title>{{ trans('torrent.catalogs') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
<li>
    <a href="{{ route('catalogs') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.catalogs') }}</span>
    </a>
</li>
@endsection

@section('content')
<div class="container box">
    <div class="forum-category-childs">
      <div class="header gradient blue">
        <div class="inner_content">
          <h1>{{ trans('torrent.catalogs') }}</h1>
        </div>
      </div>
        @foreach($catalogs as $c)
            <a href="{{ route('catalog', array('slug' => $c->slug, 'id' => $c->id)) }}" class="well col-md-2" style="margin: 10px;">
            <h2>{{ $c->name }}</h2>
            <p class="text-success">{{ $c->num_torrent }} {{ trans('torrent.titles') }}</p></a>
        @endforeach
    </div>
</div>
@endsection
