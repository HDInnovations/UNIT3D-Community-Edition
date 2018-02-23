@extends('layout.default')

@section('breadcrumb')
<li>
<a href="#" itemprop="url" class="l-breadcrumb-item-link">
<span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.bookmarks') }}</span>
</a>
</li>
@endsection

@section('content')
<div class="container-fluid">
  <div class="block">
    <div class="header gradient orange">
      <div class="inner_content">
        <h1>{{ trans('common.my') }} {{ strtolower(trans('torrent.bookmarks')) }}</h1>
      </div>
    </div>
  <div class="table-responsive">
    <div class="pull-right"></div>
    <table class="table table-condensed table-striped table-bordered">
      <thead>
        <tr>
          <th class="torrents-icon"></th>
          <th class="torrents-filename">{{ trans('torrent.name') }}</th>
          <th><i class="fa fa-download"></i></th>
          <th>{{ trans('torrent.size') }}</th>
          <th>{{ trans('torrent.short-seeds') }}</th>
          <th>{{ trans('torrent.short-leechs') }}</th>
          <th>{{ trans('torrent.short-completed') }}</th>
          <th>{{ trans('torrent.age') }}</th>
          <th>{{ trans('torrent.downloaded') }}</th>
          <th><i class="fa fa-cogs"></i></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($myBookmarks as $t)
        <tr class="">
          <td>
            <center>
              <i class="{{ $t->category->icon }} torrent-icon" data-toggle="tooltip" title="" data-original-title="{{ $t->category->name }} {{ strtolower(trans('torrent.torrent')) }}"></i>
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
              <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip" title="" data-original-title="{{ strtoupper(trans('common.download')) }}"><i class="livicon" data-name="download" data-size="18" data-color="white" data-hc="white" data-l="true"></i></button>
            </a>
          </td>
          <td>
            <span class="">{{ $t->getSize() }}</span>
          </td>
          <td>{{ $t->seeders }}</td>
          <td>{{ $t->leechers }}</td>
          <td>{{ $t->times_completed }} {{ strtolower(trans('common.times')) }}</td>
          <td>{{$t->created_at->diffForHumans()}}</td>
          <td>-</td>
          <td>
            <a href="{{ route('unbookmark', ['id' => $t->id]) }}"><button type="button" id="{{ $t->id }}" class="btn btn-xxs btn-danger btn-delete-wishlist" data-toggle="tooltip" title="" data-original-title="{{ trans('torrent.delete-bookmark') }}"><i class="fa fa-times"></i></button></a>
          </td>
        </tr>
        @empty
            {{ trans('torrent.no-bookmarks') }}
        @endforelse
      </tbody>
    </table>
  </div>
</div>
</div>
@endsection
