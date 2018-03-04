@extends('layout.default')

@section('title')
	<title>Donations - Staff Dashboard - {{ Config::get('other.title') }}</title>
@endsection

@section('meta')
	<meta name="description" content="Donations - Staff Dashboard">
@endsection

@section('breadcrumb')
<li>
  <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
  </a>
</li>
<li class="active">
  <a href="{{ route('getDonations') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Donations</span>
  </a>
</li>
@endsection

@section('content')
<div class="container">
<div class="block">
  <h2>User Donations</h2>
  <hr>
  <div class="row">
  <div class="col-sm-12">
    <p class="text-green"><strong><i class="fa fa-money"></i> Donations</strong></p>
    <table class="table table-condensed table-striped table-bordered table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>User</th>
          <th>Plan</th>
          <th>Amount</th>
		  <th>Status</th>
          <th>Time</th>
          <th>Old Rank</th>
          <th>Created</th>
          <th>Active</th>
        </tr>
      </thead>
      <tbody>
		@if(count($donations) == 0)
            <p>The are no reports in database</p>
	    @else
        @foreach($donations as $key => $d)
        <tr>
          <td>
            {{ ++$key }}
          </td>
          <td>
            {{ $d->user_id }}
          </td>
          <td>
            {{ $d->plan }}
          </td>
          <td>
            ${{ $d->amount / 100 }}
          </td>
          <td>
            {{ $d->status }}
          </td>
          <td>
            {{ $d->time }} Days
          </td>
          <td>
            {{ $d->rank }}
          </td>
          <td>
            {{ $d->created_at }}
          </td>
          <td>
            {{ $d->active }}
          </td>
        </tr>
        @endforeach
		@endif
      </tbody>
    </table>
  </div>
</div>
</div>
</div>
@endsection
