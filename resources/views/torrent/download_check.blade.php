@extends('layout.default')

@section('title')
    <title>Download Check - {{ Config::get('other.title') }}</title>
@stop

@section('breadcrumb')
<li>
    <a href="#" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.torrents') }}</span>
    </a>
</li>
<li>
    <a href="#" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.torrent') }}</span>
    </a>
</li>
<li>
    <a href="#" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Download Check</span>
    </a>
</li>
@stop

@section('content')
<div class="container">
<div class="block">
  <div class="panel panel-info">
    <div class="panel-heading">
      <div align="center">
        <h2>{{ $user->username }}</h2>
        @if($user->getRatio() < config('other.ratio') || $user->can_download == 0)
        <h4>You Are Not Able To Download This File. Please Check Below For More Info</h4>
        @else
        <h4>Your Torrent File Is Ready For Download</h4>
        @endif
      </div>
    </div>
  </div>

  <div class="panel panel-primary">
    <div class="panel-heading">
      <h4 class="text-center">Info</h4></div>
      <center>
        <h3 class="movie-title">
          <a href="#" title="{{ $torrent->name }}">{{ $torrent->name }}</a>
        </h3>
        <ul class="list-inline">
          <span class="badge-extra text-blue"><i class="fa fa-database"></i> <strong>Size: </strong> {{ $torrent->getSize() }}</span>
          <span class="badge-extra text-blue"><i class="fa fa-fw fa-calendar"></i> <strong>Released: </strong> {{ $torrent->created_at->diffForHumans() }}</span>
          <span class="badge-extra text-green"><li><i class="fa fa-arrow-up"></i> <strong>Seeders: </strong> {{ $torrent->seeders }}</li></span>
          <span class="badge-extra text-red"><li><i class="fa fa-arrow-down"></i> <strong>Leechers: </strong> {{ $torrent->leechers }}</li></span>
          <span class="badge-extra text-orange"><li><i class="fa fa-check-square-o"></i> <strong>Completed: </strong> {{ $torrent->times_completed }}</li></span>
        </ul>
      </center>
      </div>
    </div>
  <div class="well">
  <center>
  <strong>Ratio Greater Than {{ config('other.ratio') }}: </strong>
      @if($user->getRatio() < config('other.ratio'))<span class="badge-extra text-red"><i class="fa fa-times"></i> FAILED</span>
      @else<span class="badge-extra text-green"><i class="fa fa-check"></i> PASSED</span>
      @endif
  <strong>Download Rights Active: </strong>
      @if($user->can_download == 0)<span class="badge-extra text-red"><i class="fa fa-times"></i> FAILED</span>
      @else<span class="badge-extra text-green"><i class="fa fa-check"></i> PASSED</span>
      @endif
  <strong>Torrent Status: </strong>
      @if($torrent->isRejected())<span class="badge-extra text-red"><i class="fa fa-times"></i> REJECTED</span>
      @elseif($torrent->isPending())<span class="badge-extra text-orange"><i class="fa fa-times"></i> PENDING</span>
      @else<span class="badge-extra text-green"><i class="fa fa-check"></i> APPROVED</span>
      @endif
  </center>
  <br>
  <center>
  @if($user->getRatio() < config('other.ratio') || $user->can_download == 0)
  <span class="text-red text-bold">Please Solve The Failed Results Above For Download Button To Appear</span>
  @else
  <a href="{{ route('download', array('slug' => $torrent->slug, 'id' => $torrent->id)) }}" role="button" class="btn btn-labeled btn-primary">
    <span class='btn-label'><i class='fa fa-download'></i></span>{{ trans('common.download') }}</a>
  @endif
</center>
  </div>
</div>
</div>
@stop
