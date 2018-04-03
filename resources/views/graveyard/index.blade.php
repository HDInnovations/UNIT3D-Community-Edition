@extends('layout.default')

@section('title')
<title>{{ trans('graveyard.graveyard') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
<li>
  <a href="{{ route('graveyard') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('graveyard.graveyard') }}</span>
  </a>
</li>
@endsection

@section('content')
<div class="container">
<div class="well">
    <p class="text-red text-bold">{{ trans('graveyard.guidelines') }}</p>
    <p>
      {!! trans('graveyard.guidelines-content') !!}
    </p>
  </div>
</div>

<div class="container-fluid">
<div class="block">
  <div class="header gradient silver">
    <div class="inner_content">
      <h1>{{ trans('graveyard.graveyard') }} <span class="text-red">({{ $deadcount }} {{ trans('graveyard.dead') }}!)</span></h1>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-condensed table-hover table-bordered">
      <thead>
        <tr>
          <th class="torrents-icon"></th>
          <th class="torrents-filename">{{ trans('torrent.name') }}</th>
          <th>{{ trans('torrent.age') }}</th>
          <th>{{ trans('torrent.size') }}</th>
          <th>{{ trans('torrent.short-seeds') }}</th>
          <th>{{ trans('torrent.short-leechs') }}</th>
          <th>{{ trans('torrent.short-completed') }}</th>
          <th>{{ trans('graveyard.ressurect') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($dead as $d)
        @php $history = DB::table('history')->where('info_hash', '=', $d->info_hash)->where('user_id', '=', $user->id)->first(); @endphp
        <tr class="">
          <td>
              <center>
                  <i class="{{ $d->category->icon }} torrent-icon" data-toggle="tooltip" title="" data-original-title="{{ $d->category->name }} {{ trans('torrent.torrent') }}"></i>
              </center>
          </td>
          <td>
            <div class="torrent-file">
              <div>
                  <a class="view-torrent" data-id="{{ $d->id }}" data-slug="{{ $d->slug }}" href="{{ route('torrent', array('slug' => $d->slug, 'id' => $d->id)) }}" data-toggle="tooltip" title="" data-original-title="{{ $d->name }}">{{ $d->name }} </a><span class="label label-success">{{ $d->type }}</span>
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
              <span class="icon">@emojione(':zombie:') {{ trans('graveyard.ressurect') }}</span>
            </button>
            @else
            <button class="btn btn-sm btn-info" disabled>
              <span class="icon">@emojione(':angel_tone2:') {{ strtolower(trans('graveyard.pending')) }}</span>
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
                <h2><i class="fa fa-thumbs-up"></i>{{ trans('graveyard.ressurect') }} {{ strtolower(trans('torrent.torrent')) }}?</h2>
              </div>

              <div class="modal-body">
                <p class="text-center text-bold"><span class="text-green"><em>{{ $d->name }} </em></span></p>
                <p class="text-center text-bold">{{ trans('graveyard.howto') }}</p>
                <br>
                  <center>
                    <p>{!! trans('graveyard.howto-desc1', ['name' => $d->name]) !!}
                      <span class="text-red text-bold">@if(!$history) 0 @else {{ App\Helpers\StringHelper::timeElapsed($history->seedtime) }} @endif</span> {{ strtolower(trans('graveyard.howto-hits')) }}
                      <span class="text-red text-bold">@if(!$history) {{ App\Helpers\StringHelper::timeElapsed($time) }} @else {{ App\Helpers\StringHelper::timeElapsed($history->seedtime + $time) }} @endif</span> {{ strtolower(trans('graveyard.howto-desc2')) }}
                      <span class="badge-user text-bold text-pink" style="background-image:url(https://i.imgur.com/F0UCb7A.gif);">{{ $tokens }} {{ trans('torrent.freeleech') }} Token(s)!</span></p>
                  <div class="btns">
                    <form role="form" method="POST" action="{{ route('resurrect',['id' => $d->id]) }}">
                    {{ csrf_field() }}
                    @if(!$history)
                    <input hidden="seedtime" name="seedtime" id="seedtime" value="{{ $time }}">
                    @else
                    <input hidden="seedtime" name="seedtime" id="seedtime" value="{{ $history->seedtime + $time }}">
                    @endif
                    <button type="submit" class="btn btn-success">{{ trans('graveyard.ressurect') }}!</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal">{{ trans('common.cancel') }}</button>
                    </form>
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
@endsection
