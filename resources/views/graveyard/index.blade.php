@extends('layout.default')

@section('title')
<title>{{ trans('graveyard.graveyard') }} - {{ Config::get('other.title') }}</title>
@stop

@section('breadcrumb')
<li>
  <a href="{{ route('graveyard') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('graveyard.graveyard') }}</span>
  </a>
</li>
@stop

@section('content')
<div class="container">
<div class="well">
    <p class="lead text-center">This system is not fully functional and is still being built.</p>
    <p class="text-red text-bold">Guidelines:</p>
    <p>
      1) You cannot resurrect your own uploads.
    <br>
      2) Dont resurrect something you cannot commit too.
    </p>
  </div>
</div>

<div class="container-fluid">
<div class="block">
  <div class="header gradient silver">
    <div class="inner_content">
      <h1>{{ trans('graveyard.graveyard') }} <span class="text-red">({{ $deadcount }} Dead!)</span></h1>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-condensed table-hover table-bordered">
      <thead>
        <tr>
          <th class="torrents-icon"></th>
          <th class="torrents-filename">File</th>
          <th>Age</th>
          <th>Size</th>
          <th>S</th>
          <th>L</th>
          <th>C</th>
          <th>Ressurect</th>
        </tr>
      </thead>
      <tbody>
        @foreach($dead as $d)
        @php $history = DB::table('history')->where('info_hash', '=', $d->info_hash)->where('user_id', '=', $user->id)->first(); @endphp
        <tr class="">
          <td>
            @if($d->category_id == "1")
            <i class="fa fa-film torrent-icon" data-toggle="tooltip" title="" data-original-title="Movie Torrent"></i>
            @elseif($d->category_id == "2")
            <i class="fa fa-tv torrent-icon" data-toggle="tooltip" title="" data-original-title="TV-Show Torrent"></i>
            @else
            <i class="fa fa-video-camera torrent-icon" data-toggle="tooltip" title="" data-original-title="FANRES Torrent"></i>
            @endif
          </td>
          <td>
            <div class="torrent-file">
              <div>
                <a href="{{ route('torrent', array('slug' => $d->slug, 'id' => $d->id)) }}" class="" title="">
                  {{ $d->name }}
                </a>
              </div>
              <div>
                <span class="badge-extra">{{ $d->type }}</span>&nbsp;&nbsp;
                @if($d->stream == "1")<span class="badge-extra"><i class="fa fa-play text-red text-bold" data-toggle="tooltip" title="" data-original-title="Stream Optimized"></i> Stream Optimized</span> @endif
                @if($d->doubleup == "1")<span class="badge-extra"><i class="fa fa-diamond text-green text-bold" data-toggle="tooltip" title="" data-original-title="Double upload"></i> Double Upload</span> @endif
                @if($d->free == "1")<span class="badge-extra"><i class="fa fa-star text-gold text-bold" data-toggle="tooltip" title="" data-original-title="100% Free"></i> 100% Free</span> @endif
                @if(config('other.freeleech') == true)<span class="badge-extra"><i class="fa fa-globe text-blue text-bold" data-toggle="tooltip" title="" data-original-title="Global FreeLeech"></i> Global FreeLeech</span> @endif
                @if(config('other.doubleup') == true)<span class="badge-extra"><i class="fa fa-globe text-green text-bold" data-toggle="tooltip" title="" data-original-title="Double Upload"></i> Global Double Upload</span> @endif
              </div>
            </div>
          </td>
          <td>
            <span data-toggle="tooltip" title="" data-original-title="">{{ $d->created_at->diffForHumans() }}</span>
          </td>
          <td>
            <span class="">{{ $d->getSize() }}</span>
          </td>
          <td>{{ $d->seeders }}</td>
          <td>{{ $d->leechers }}</td>
          <td>{{ $d->times_completed }}</td>
          <td>
            @php $resurrected = DB::table('graveyard')->where('torrent_id', '=', $d->id)->first(); @endphp
            @if(!$resurrected)
            <button data-toggle="modal" data-target="#resurrect-{{ $d->id }}" class="btn btn-sm btn-default">
              <span class="icon"><i class="fa fa-snapchat-ghost"></i> Resurrect</span>
            </button>
            @else
            <button class="btn btn-sm btn-default" disabled>
              <span class="icon"><i class="fa fa-snapchat-ghost"></i> Pending Resurrection</span>
            </button>
            @endif
          </td>
        </tr>
        {{-- Resurrect Modal --}}
        <div class="modal fade" id="resurrect-{{ $d->id }}" tabindex="-1" role="dialog" aria-labelledby="resurrect">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2><i class="fa fa-thumbs-up"></i>Resurrect This Torrent?</h2>
              </div>

              <div class="modal-body">
                <p class="text-center text-bold"><span class="text-green"><em>{{ $d->name }} </em></span></p>
                <p class="text-center text-bold">Heres The Rule!</p>
                <br>
                  <center>
                    <p>You must seed <span class="text-green"><em>{{ $d->name }} </em></span> for <span class="text-red text-bold">1M (30 days)</span> for a succesful ressurection. In which case when your current seedtime of
                      <span class="text-red text-bold">@if(!$history) 0 @else {{ App\Helpers\StringHelper::timeElapsed($history->seedtime) }} @endif</span> hits
                      <span class="text-red text-bold">@if(!$history) {{ App\Helpers\StringHelper::timeElapsed($time) }} @else {{ App\Helpers\StringHelper::timeElapsed($history->seedtime + $time) }} @endif</span> you will be rewarded
                      <span class="badge-user text-bold text-pink" style="background-image:url(https://i.imgur.com/F0UCb7A.gif);">1 Freeleech Slot(s)!</span></p>
                  <div class="btns">
                    {{ Form::open(array('route' => array('resurrect', 'id' => $d->id))) }}
                    @if(!$history)
                    <input hidden="seedtime" name="seedtime" id="seedtime" value="0">
                    @else
                    <input hidden="seedtime" name="seedtime" id="seedtime" value="$history->seedtime">
                    @endif
                    <button type="submit" class="btn btn-success">Resurrect Now!</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                    {{ Form::close() }}
                  </div>
                </center>
              </div>

            </div>
          </div>
        </div>
        @endforeach
      </tbody>
    </table>
  </div>
  {{ $dead->links() }}
</div>
</div>
@stop

@section('javascripts')

@stop
