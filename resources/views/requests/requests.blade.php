@extends('layout.default')

@section('title')
<title>Requests - {{ Config::get('other.title') }}</title>
@stop

@section('breadcrumb')
<li>
  <a href="{{ route('requests') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Requests</span>
  </a>
</li>
@stop

@section('content')
<div class="container">
  @if($user->can_request == 0)
  <div class="container">
    <div class="jumbotron shadowed">
      <div class="container">
        <h1 class="mt-5 text-center">
          <i class="fa fa-times text-danger"></i> Error: Your Request Rights Have Been Disabled
        </h1>
      <div class="separator"></div>
    <p class="text-center">If You Feel This Is In Error, Please Contact Staff!</p>
  </div>
  </div>
  </div>
  @else
    <div class="well">
      <p class="lead text-orange text-center">BON exchanges on creating, filling and bounties are final!<br><strong>NO REFUNDS!</strong></p>
    </div>
<div class="well">
  <form action="{{route('request_search')}}" style="display: inline;" method="get">
      <div class="form-group">
        <label for="category_id">Category</label>
        <select name="category_id" class="form-control">
          @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
          @endforeach
        </select>
      </div>
        <input class="form-control" placeholder="Search by Keyword" name="name" type="text" id="name">
        <button type="submit" class="btn btn-sm btn-primary" title="Search"><i class="fa fa-search"></i> Search</button>
        <a href="{{ route('requests') }}" class="btn btn-sm btn-warning" title="Reset Search"><i class="fa fa-repeat"></i> Reset</a>
        </form>
        <form action="{{route('requests')}}" style="display: inline; float:right;" method="get">
          <button type="submit" class="btn btn-sm btn-primary">All Requests</button>
          <button type="submit" class="btn btn-sm btn-primary" name="filled_requests" value="true" id="filled_requests">View Filled</button>
          <button type="submit" class="btn btn-sm btn-primary" name="unfilled_requests" value="true" id="unfilled_requests">View Unfilled</button>
          <button type="submit" class="btn btn-sm btn-primary" name="my_requests" value="true" id="my_requests">My Requests</button>
        </form>
    </div>
</div>

<div class="container-fluid">
<div class="block">
  <div class="header gradient light_blue">
    <div class="inner_content">
      <h1>Requests</h1>
    </div>
  </div>
  <br>
  <span class="badge-user" style="float: right;"><a href="{{ route('requests') }}"><strong>Requests:</strong></a> {{ $num_req }} |
  <a href="{{ route('requests') }}" name="filled_requests" value="true" id="filled_requests"><strong>Filled:</strong></a> {{ $num_fil }} |
  <a href="{{ route('requests') }}" name="unfilled_requests" value="true" id="unfilled_requests"><strong>Unfilled:</strong></a> {{ $num_unfil }}
  | <strong>Total Bounty:</strong> {{ $total_bounty }} BON | <strong>Bounty Claimed:</strong> {{ $claimed_bounty }} BON | <strong>Bounty Unclaimed:</strong> {{ $unclaimed_bounty }} BON</span>
  <a href="{{ route('add_request') }}" role="button" data-id="0" data-toggle="tooltip" title="" data-original-title="Add New Request!" class="btn btn btn-success">Add New Request</a>
  <div class="table-responsive">
    <table class="table table-condensed table-striped table-bordered">
      <thead>
        <tr>
          <th class="torrents-icon">Category</th>
          <th class="torrents-filename col-sm-6">Request</th>
          <th>By</th>
          <th>Votes</th>
          <th>Comments</th>
          <th>Bounty</th>
          <th>Age</th>
          <th>Claimed / Filled</th>
        </tr>
      </thead>
      <tbody>
      @foreach($requests as $r)
        <tr>
          <td>
            <span class="label label-info" data-toggle="tooltip" title="" data-original-title="{{ $r->category->name }}">{{ $r->category->name }}</span>
          </td>
          <td>
            <a class="view-torrent" data-id="{{ $r->id }}" href="{{ route('request', array('id' => $r->id)) }}" data-toggle="tooltip" title="" data-original-title="{{ $r->name }}">{{ $r->name }}</a>
          </td>
          <td>
            <span class="badge-user">{{ $r->user->username }}</span>
          </td>
          <td>
            <span class="badge-user">{{ $r->votes }}</span>
          </td>
          <td>{{ $r->comments->count() }}</td>
          <td>{{ $r->bounty }}</td>
          <td>{{ $r->created_at->diffForHumans() }}</td>
          <td>
            @if($r->claimed != null && $r->filled_hash == null)
            <button class="btn btn-xs btn-primary" data-toggle="tooltip" title="" data-original-title="Request Has Been Claimed">
              <i class="fa fa-suitcase"></i> Claimed</button>
            @elseif($r->filled_hash != null && $r->approved_by == null)
            <button class="btn btn-xs btn-info" data-toggle="tooltip" title="" data-original-title="Request Pending Approval">
              <i class="fa fa-question-circle"></i> Pending</button>
            @elseif($r->filled_hash == null)
            <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="" data-original-title="Request Not Fulfilled">
              <i class="fa fa-times-circle"></i> Unfilled</button>
            @else
            <button class="btn btn-xs btn-success" data-toggle="tooltip" title="" data-original-title="Request Fulfilled">
              <i class="fa fa-check-circle"></i> Filled</button>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{ $requests->links() }}
  </div>
</div>
@endif
</div>
@stop
