@extends('layout.default')

@section('title')
<title>{{ trans('stat.stats') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
<li class="active">
  <a href="{{ route('stats') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('stat.stats') }}</span>
  </a>
</li>
<li>
  <a href="{{ route('seedsize') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('stat.top-seedsize') }}</span>
  </a>
</li>
@endsection

@section('content')
<div class="container">
@include('partials.statsusermenu')

<div class="block">
  <h2>{{ trans('stat.top-seedsize') }}</h2>
  <hr>
  <div class="row">
    <div class="col-md-12">
      <p class="text-purple"><strong><i class="fa fa-star"></i> {{ trans('stat.top-seedsize') }}</strong></p>
      <table class="table table-condensed table-striped table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ trans('common.user') }}</th>
            <th>{{ trans('torrent.seedsize') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($seedsize as $key => $s)
          <tr>
            <td>
                {{ ++$key }}
            </td>
            <td @if(auth()->user()->username == $s->user->username) class="mentions" @endif>
              @if($s->private_profile == 1)
              <span class="badge-user text-bold"><span class="text-orange"><i class="fa fa-eye-slash" aria-hidden="true"></i>{{ strtoupper(trans('common.hidden')) }}</span>@if(auth()->user()->id == $b->id || auth()->user()->group->is_modo)<a href="{{ route('profile', ['username' => $s->username, 'id' => $s->id]) }}">({{ $s->username }}</a></span>
              @endif
              @else
              <span class="badge-user text-bold"><a href="{{ route('profile', ['username' => $s->username, 'id' => $s->id]) }}">{{ $s->username }}</a></span>
              @endif
            </td>
            <td>
              <span class="text-purple">{{ $s }}</span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
@endsection
