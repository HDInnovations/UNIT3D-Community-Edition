@extends('layout.default')

@section('breadcrumb')
<li>
<a href="#" itemprop="url" class="l-breadcrumb-item-link">
<span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.bookmarks') }}</span>
</a>
</li>
@stop

@section('content')
<div class="container-fluid">
  <div class="block">
    <div class="header gradient orange">
      <div class="inner_content">
        <h1>My {{ trans('torrent.bookmarks') }}</h1>
      </div>
    </div>
  <div class="table-responsive">
    <div class="pull-right"></div>
    <table class="table table-condensed table-striped table-bordered">
      <thead>
        <tr>
          <th class="torrents-icon"></th>
          <th class="torrents-filename">File</th>
          <th><i class="fa fa-download"></i></th>
          <th>Size</th>
          <th>S</th>
          <th>L</th>
          <th>C</th>
          <th>Added</th>
          <th>Downloaded</th>
          <th><i class="fa fa-cogs"></i></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($myBookmarks as $t)
        <tr class="">
          <td>
            <center>
              @if($t->category_id == "1")
              <i class="fa fa-film torrent-icon" data-toggle="tooltip" title="" data-original-title="Movie Torrent"></i>
              @elseif($t->category_id == "2")
              <i class="fa fa-tv torrent-icon" data-toggle="tooltip" title="" data-original-title="TV-Show Torrent"></i>
              @else
              <i class="fa fa-film torrent-icon" data-toggle="tooltip" title="" data-original-title="Movie Torrent"></i>
              @endif
            </center>
          </td>
          <td>
            <div class="torrent-file">
              <div>
                <a class="view-torrent" data-id="{{ $t->id }}" data-slug="{{ $t->slug }}" href="{{ route('torrent', array('slug' => $t->slug, 'id' => $t->id)) }}" data-toggle="tooltip" title="" data-original-title="{{ $t->name }}">{{ $t->name }}</a>
              </div>
            </div>
          </td>
          <td>
            <a href="{{ route('download', array('slug' => $t->slug, 'id' => $t->id)) }}">&nbsp;&nbsp;
              <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip" title="" data-original-title="DOWNLOAD!"><i class="livicon" data-name="download" data-size="18" data-color="white" data-hc="white" data-l="true"></i></button>
            </a>
          </td>
          <td>
            <span class="">{{ $t->getSize() }}</span>
          </td>
          <td>{{ $t->seeders }}</td>
          <td>{{ $t->leechers }}</td>
          <td>{{ $t->times_completed }} {{ trans('common.times') }}</td>
          <td>{{$t->created_at->diffForHumans()}}</td>
          <td>-</td>
          <td>
            <a href="{{ route('unbookmark', ['id' => $t->id]) }}"><button type="button" id="{{ $t->id }}" class="btn btn-xxs btn-danger btn-delete-wishlist" data-toggle="tooltip" title="" data-original-title="Delete This Bookmark"><i class="fa fa-times"></i></button></a>
          </td>
        </tr>
        @empty
            There are no bookmarks found.
        @endforelse
      </tbody>
    </table>
  </div>
</div>
</div>
@stop
