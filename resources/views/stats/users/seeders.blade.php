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
  <a href="{{ route('seeders') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Top Seeders</span>
  </a>
</li>
@stop

@section('content')
<div class="container">
@include('partials.statsusermenu')

<div class="block">
  <h2>Top Seeders (COUNT)</h2>
  <hr>
  <div class="row">
    <div class="col-md-12">
      <p class="text-green"><strong><i class="fa fa-arrow-up"></i> Top Seeders</strong> (COUNT)</p>
      <table class="table table-condensed table-striped table-bordered">
        <thead>
          <tr>
            <th>User</th>
            <th>Seeding</th>
          </tr>
        </thead>
        <tbody>
          @foreach($seeders as $s)
          <tr>
            <td>
              @if($s->user->private_profile == 1)
              <span class="badge-user text-bold"><span class="text-orange"><i class="fa fa-eye-slash" aria-hidden="true"></i>HIDDEN</span>@if(Auth::user()->id == $s->user->id || Auth::user()->group->is_modo)<a href="{{ route('profil', ['username' => $s->user->username, 'id' => $s->user->id]) }}">({{ $s->user->username }})</a></span>
              @endif
              @else
              <span class="badge-user text-bold"><a href="{{ route('profil', ['username' => $s->user->username, 'id' => $s->user->id]) }}">{{ $s->user->username }}</a></span>
              @endif
            </td>
            <td>
              <span class="text-green">{{ $s->user->getSeeding() }}</span>
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
