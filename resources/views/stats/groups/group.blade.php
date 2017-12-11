@extends('layout.default')

@section('title')
<title>Stats - {{ Config::get('other.title') }}</title>
@stop

@section('breadcrumb')
<li>
  <a href="{{ route('stats') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Stats</span>
  </a>
</li>
<li>
  <a href="{{ route('groups') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Groups</span>
  </a>
</li>
<li class="active">
  <a href="{{ route('group', ['id' => $group->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Group</span>
  </a>
</li>
@stop

@section('content')
<div class="container">
@include('partials.statsgroupmenu')
<div class="block">
  <h2>{{ $group->name }} Group</h2>
  <hr>
  <div class="row">
    <div class="col-md-12">
      <p class="text-red"><strong><i class="{{ $group->icon }}"></i> {{ $group->name }} Group</strong> (Users In Group)</p>
      <table class="table table-condensed table-striped table-bordered">
        <thead>
          <tr>
            <th>User</th>
            <th>Registration Date</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $u)
          <tr>
            <td>
              @if($u->private_profile == 1)
              <span class="badge-user text-bold"><span class="text-orange"><i class="fa fa-eye-slash" aria-hidden="true"></i>HIDDEN</span>@if(Auth::user()->id == $u->id || Auth::user()->group->is_modo)<a href="{{ route('profil', ['username' => $u->username, 'id' => $u->id]) }}">({{ $u->username }})</a></span>
              @endif
              @else
              <span class="badge-user text-bold"><a href="{{ route('profil', ['username' => $u->username, 'id' => $u->id]) }}">{{ $u->username }}</a></span>
              @endif
            </td>
            <td>
              <span>{{ date('d M Y', strtotime($u->created_at)) }}</span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      {{ $users->links() }}
    </div>
  </div>
</div>
</div>
@stop
