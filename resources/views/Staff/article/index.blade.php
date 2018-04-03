@extends('layout.default')

@section('title')
<title>Articles - Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
<li>
  <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
  </a>
</li>
<li class="active">
  <a href="{{ route('staff_article_index') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Articles</span>
  </a>
</li>
@endsection

@section('content')
<div class="container box">
  <h2>Articles</h2>
  <a href="{{ route('staff_article_add') }}" class="btn btn-primary">Add a post</a>
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>Title</th>
        <th>Author</th>
        <th>Comments</th>
        <th>Date</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($posts as $p)
      <tr>
        <td><a href="{{ route('staff_article_edit', array('slug' => $p->slug, 'id' => $p->id)) }}">{{ $p->title }}</a></td>
        <td><a href="{{ route('user_edit', array('username' => $p->user->username, 'id' => $p->user->id)) }}">{{ $p->user->username }}</a></td>
        <td>0</td>
        <th>{{ date('d/m/Y', strtotime($p->created_at)) }}</th>
        <td><a href="{{ route('staff_article_delete', array('slug' => $p->slug, 'id' => $p->id)) }}" class="btn btn-danger">Delete</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
 {{ $posts->links() }}
</div>
@endsection
