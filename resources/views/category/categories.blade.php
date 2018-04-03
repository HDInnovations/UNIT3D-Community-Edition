@extends('layout.default')

@section('title')
<title>{{ trans('torrent.categories') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
<li>
    <a href="{{ route('categories') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.categories') }}</span>
    </a>
</li>
@endsection

@section('content')
<div class="container box">
    <div class="forum-category-childs">
      <div class="header gradient green">
        <div class="inner_content">
          <h1>{{ trans('torrent.categories') }}</h1>
        </div>
      </div>
        @foreach($categories as $c)
            <a href="{{ route('category', array('slug' => $c->slug, 'id' => $c->id)) }}" class="well col-md-2" style="margin: 10px;">
            <h2>{{ $c->name }}</h2>
            <p class="text-success">{{ $c->num_torrent }} {{ trans('torrent.torrents') }}</p></a>
        @endforeach
    </div>
</div>
@endsection
