@extends('layout.default')

@section('title')
<title>Stats - {{ Config::get('other.title') }}</title>
@stop

@section('breadcrumb')
<li class="active">
  <a href="{{ route('stats') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Stats</span>
  </a>
</li>
<li>
  <a href="{{ route('downloaded') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Top Downloaders</span>
  </a>
</li>
@stop

@section('content')
<div class="container">
@include('partials.statsusermenu')

<div class="block">
  <h2>Top Downloaders (by volume)</h2>
  <hr>
  <div class="row">
    <div class="col-md-12">
      <p class="text-red"><strong><i class="fa fa-arrow-down"></i> Top Downloaders</strong> (by volume)</p>
      <table class="table table-condensed table-striped table-bordered">
        <thead>
          <tr>
            <th>User</th>
            <th>Upload</th>
            <th>Download</th>
            <th>Ratio</th>
          </tr>
        </thead>
        <tbody>
          @foreach($downloaded as $d)
          <tr>
            <td>
              @if($d->private_profile == 1)
              <span class="badge-user text-bold"><span class="text-orange"><i class="fa fa-eye-slash" aria-hidden="true"></i>HIDDEN</span>@if(Auth::user()->id == $d->id || Auth::user()->group->is_modo)<a href="{{ route('profil', ['username' => $d->username, 'id' => $d->id]) }}">({{ $d->username }}</a></span>
              @endif
              @else
              <span class="badge-user text-bold"><a href="{{ route('profil', ['username' => $d->username, 'id' => $d->id]) }}">{{ $d->username }}</a></span>
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
@stop
