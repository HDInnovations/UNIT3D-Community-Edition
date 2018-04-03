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
  <a href="{{ route('downloaded') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('stat.top-downloaders') }}</span>
  </a>
</li>
@endsection

@section('content')
<div class="container">
@include('partials.statsusermenu')

<div class="block">
  <h2>{{ trans('stat.top-downloaders') }}</h2>
  <hr>
  <div class="row">
    <div class="col-md-12">
      <p class="text-red"><strong><i class="fa fa-arrow-down"></i> {{ trans('stat.top-downloaders') }}</strong></p>
      <table class="table table-condensed table-striped table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ trans('common.user') }}</th>
            <th>{{ trans('common.upload') }}</th>
            <th>{{ trans('common.download') }}</th>
            <th>{{ trans('common.ratio') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($downloaded as $key => $d)
          <tr>
            <td>
                {{ ++$key }}
            </td>
            <td @if(auth()->user()->username == $d->username) class="mentions" @endif>
              @if($d->private_profile == 1)
              <span class="badge-user text-bold"><span class="text-orange"><i class="fa fa-eye-slash" aria-hidden="true"></i>{{ strtoupper(trans('common.hidden')) }}</span>@if(auth()->user()->id == $d->id || auth()->user()->group->is_modo)<a href="{{ route('profile', ['username' => $d->username, 'id' => $d->id]) }}">({{ $d->username }}</a></span>
              @endif
              @else
              <span class="badge-user text-bold"><a href="{{ route('profile', ['username' => $d->username, 'id' => $d->id]) }}">{{ $d->username }}</a></span>
              @endif
            </td>
            <td>{{ \App\Helpers\StringHelper::formatBytes($d->uploaded, 2) }}</td>
            <td><span class="text-red">{{ \App\Helpers\StringHelper::formatBytes($d->downloaded, 2) }}</span></td>
            <td>
              <span>{{ $d->getRatio() }}</span>
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
