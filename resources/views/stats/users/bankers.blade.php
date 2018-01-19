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
  <a href="{{ route('bankers') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Top Bankers</span>
  </a>
</li>
@stop

@section('content')
<div class="container">
@include('partials.statsusermenu')

<div class="block">
  <h2>Top Bankers (BON)</h2>
  <hr>
  <div class="row">
    <div class="col-md-12">
      <p class="text-purple"><strong><i class="fa fa-star"></i> Top Bankers</strong> (BON)</p>
      <table class="table table-condensed table-striped table-bordered">
        <thead>
          <tr>
            <th>User</th>
            <th>Balance</th>
          </tr>
        </thead>
        <tbody>
          @foreach($bankers as $b)
          <tr>
            <td>
              @if($b->private_profile == 1)
              <span class="badge-user text-bold"><span class="text-orange"><i class="fa fa-eye-slash" aria-hidden="true"></i>HIDDEN</span>@if(Auth::user()->id == $b->id || Auth::user()->group->is_modo)<a href="{{ route('profil', ['username' => $b->username, 'id' => $b->id]) }}">({{ $b->username }}</a></span>
              @endif
              @else
              <span class="badge-user text-bold"><a href="{{ route('profil', ['username' => $b->username, 'id' => $b->id]) }}">{{ $b->username }}</a></span>
              @endif
            </td>
            <td>
              <span class="text-purple">{{ $b->seedbonus }}</span>
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
