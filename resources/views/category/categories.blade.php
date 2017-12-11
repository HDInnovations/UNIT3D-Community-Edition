@extends('layout.default')

@section('title')
<title>Categories - {{ Config::get('other.title') }}</title>
@stop

@section('breadcrumb')
<li>
    <a href="{{ route('categories') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Categories</span>
    </a>
</li>
@stop

@section('content')
<div class="container box">
    <div class="forum-category-childs">
      <div class="header gradient green">
        <div class="inner_content">
          <h1>Our Categories</h1>
        </div>
      </div>
        @foreach($categories as $c)
            <a href="{{ route('category', array('slug' => $c->slug, 'id' => $c->id)) }}" class="well col-md-2" style="margin: 10px;">
            <h2>{{ $c->name }}</h2>
            <p class="text-success">{{ $c->num_torrent }} Torrents</p></a>
        @endforeach
    </div>
</div>
@stop
