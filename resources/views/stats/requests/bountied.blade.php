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
  <a href="{{ route('bountied') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Top Bountied</span>
  </a>
</li>
@stop

@section('content')
<div class="container">
@include('partials.statsrequestmenu')

<div class="block">
  <h2>Top Bountied</h2>
  <hr>
  <div class="row">
    <div class="col-md-12">
    <p class="text-success"><strong><i class="fa fa-trophy"></i> Top Bountied</strong> (Highest BONUS Bounties)</p>
    <table class="table table-condensed table-striped table-bordered">
      <thead>
        <tr>
          <th>Request</th>
          <th>Bounty</th>
          <th>Filled</th>
        </tr>
      </thead>
      <tbody>
        @foreach($bountied as $b)
        <tr>
          <td>
            <a class="view-torrent" data-id="{{ $b->id }}" href="{{ route('request', array('id' => $b->id)) }}" data-toggle="tooltip" title="" data-original-title="{{ $b->name }}">{{ $b->name }}</a>
          </td>
          <td><span class="text-green">{{ $b->bounty }}</span></td>
          <td>
            @if($b->filled_hash == null)
            <button class="btn btn-xxs" data-toggle="tooltip" title="" data-original-title="Request Not Fulfilled">
              <i class="fa fa-times-circle text-danger"></i></button>
            @elseif($b->filled_hash != null && $b->approved_by == null)
            <button class="btn btn-xxs" data-toggle="tooltip" title="" data-original-title="Request Pending Approval">
              <i class="fa fa-question-circle text-info"></i></button>
            @else
            <button class="btn btn-xxs" data-toggle="tooltip" title="" data-original-title="Request Fulfilled">
              <i class="fa fa-check-circle text-success"></i></button>
            @endif
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
