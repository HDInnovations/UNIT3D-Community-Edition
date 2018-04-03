@extends('layout.default')

@section('title')
    <title>{{ trans('user.invites') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
<li>
    <a href="{{ route('invite') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('user.invites') }}</span>
    </a>
</li>
@endsection

@section('content')
<div class="container-fluid">
@if (config('other.invite-only') == false)
<div class="container">
    <div class="jumbotron shadowed">
    <div class="container">
      <h1 class="mt-5 text-center">
        <i class="fa fa-times text-danger"></i> {{ trans('user.invites-disabled') }}
      </h1>
    <div class="separator"></div>
  <p class="text-center">{{ trans('user.invites-disabled-desc') }}</p>
</div>
</div>
</div>
@elseif($user->can_invite == 0)
<div class="container">
  <div class="jumbotron shadowed">
    <div class="container">
      <h1 class="mt-5 text-center">
        <i class="fa fa-times text-danger"></i> {{ trans('user.invites-banned') }}
      </h1>
    <div class="separator"></div>
  <p class="text-center">{{ trans('user.invites-banned-desc') }}</p>
</div>
</div>
</div>
@else
<div class="block block-titled">
<h2>{{ trans('user.invites-count', ['count' => $user->invites]) }}</h2>
<p class="text-danger text-bold">{{ trans('user.important') }}</p>
<ul>
{!! trans('user.invites-rules') !!}
</ul>
</div>

<h3>{{ trans('user.invite-friend') }}</h3>
<div class="block block-form">
<form action="{{ route('invite') }}" method="post">
{{ csrf_field() }}
<label for="email" class="col-sm-2 control-label">{{ trans('common.email') }}</label>
<input class="form-control" name="email" type="email" id="email" size="10" required>
<label for="message" class="col-sm-2 control-label">{{ trans('common.message') }}</label>
<textarea class="form-control" name="message" cols="50" rows="10" id="message"></textarea>
<button type="submit" class="btn btn-primary">{{ trans('common.submit') }}</button>
</form>
</div>
</div>
@endif
@endsection
