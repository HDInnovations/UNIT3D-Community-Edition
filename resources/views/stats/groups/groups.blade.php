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
  <a href="{{ route('groups') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Groups</span>
  </a>
</li>
@stop

@section('content')
<div class="container">
@include('partials.statsgroupmenu')
<div class="block">
  <h2>Groups</h2>
  <hr>
    <p class="text-red"><strong><i class="fa fa-users"></i> Groups</strong> (Users Per Group)</p>
      <div class="row col-md-offset-2">
        @foreach($groups as $group)
        <div class="well col-md-3" style="margin: 10px;">
        <center>
          <a href="{{ route('group', ['id' => $group->id]) }}"><h2 style="color:{{ $group->color }};"><i class="{{ $group->icon }}" aria-hidden="true"></i>&nbsp;{{ $group->name }}</h2></a>
          <p class="lead text-blue">{{ $group->users->count() }} Users</p>
        </center>
        </div>
        @endforeach
      </div>
    </div>
  </div>
@stop
