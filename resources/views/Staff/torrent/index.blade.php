@extends('layout.default')

@section('title')
<title>Torrents - Staff Dashboard - {{ Config::get('other.title') }}</title>
@endsection

@section('breadcrumb')
<li>
    <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
    </a>
</li>
<li class="active">
  <a href="{{ route('staff_torrent_index') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Torrents</span>
  </a>
</li>
@endsection

@section('content')
<div class="container box">
  <h2>Torrents</h2>
  <form action="{{route('torrent-search')}}" method="any">
  <input type="text" name="name" id="name" size="25" placeholder="Quick Search by Title" class="form-control" style="float:right;">
  </form>
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>#</th>
        <th>Title</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($torrents as $t)
      <tr>
        <td>{{ $t->id }}</a>
        </td>
        <td><a href="{{ route('edit', array('slug' => $t->slug, 'id' => $t->id)) }}">{{ $t->name }}</a></td>
        <td><a href="{{ route('edit', array('slug' => $t->slug, 'id' => $t->id)) }}" class="btn btn-warning">Edit</a>
          <a href="{{ route('delete', ['slug' => $t->slug, 'id' => $t->id]) }}" class="btn btn-danger">Delete</a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  {{ $torrents->links() }}
</div>
@endsection
