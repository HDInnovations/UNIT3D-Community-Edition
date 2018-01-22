@extends('layout.default')

@section('title')
    <title>Invites - {{ Config::get('other.title') }}</title>
@stop

@section('breadcrumb')
<li>
    <a href="{{ route('invite') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Invites</span>
    </a>
</li>
@stop

@section('content')
<div class="container-fluid">
@if (config('other.invite-only') == false)
<div class="container">
    <div class="jumbotron shadowed">
    <div class="container">
      <h1 class="mt-5 text-center">
        <i class="fa fa-times text-danger"></i> Attention: Invites Are Disabled Due To Open Registration!
      </h1>
    <div class="separator"></div>
  <p class="text-center">Please Check Back Soon!</p>
</div>
</div>
</div>
@elseif($user->can_invite == 0)
<div class="container">
  <div class="jumbotron shadowed">
    <div class="container">
      <h1 class="mt-5 text-center">
        <i class="fa fa-times text-danger"></i> Error: Your Invite Rights Have Been Disabled
      </h1>
    <div class="separator"></div>
  <p class="text-center">If You Feel This Is In Error, Please Contact Staff!</p>
</div>
</div>
</div>
@else
<div class="block block-titled">
<h2>You Have {{ $user->invites }} Invite Tokens</h2>
<p class="text-danger text-bold">Important</p>
<ul>
<li class="text-success">Only invite people you know and trust.</li>
<li class="text-danger">You will be held personally responsible for those you invite.</li>
<li class="text-danger">Don't invite yourself, we check every invited user.</li>
<li class="text-danger">Don't trade or sell Invites.</li>
<li class="text-danger">If a person you invited is caught cheating, trading account or selling/trading invites, you will be warned.</li>
</ul>
</div>

<h3>Invite your friend (Email + Message Required)</h3>
<div class="block block-form">
<form action="{{ route('invite') }}" method="post">
{{ csrf_field() }}
<label for="email" class="col-sm-2 control-label">Email Address</label>
<input class="form-control" name="email" type="email" id="email" size="10" required>
<label for="message" class="col-sm-2 control-label">Message</label>
<textarea class="form-control" name="message" cols="50" rows="10" id="message"></textarea>
<button type="submit" class="btn btn-primary">{{ trans('common.submit') }}</button>
</form>
</div>
</div>
@endif
@stop
