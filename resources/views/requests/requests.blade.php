@extends('layout.default')

@section('title')
<title>Requests - {{ Config::get('other.title') }}</title>
@stop

@section('breadcrumb')
<li>
  <a href="{{ route('requests') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('request.requests') }}</span>
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
          <i class="fa fa-times text-danger"></i> {{ trans('request.no-privileges') }}
        </h1>
      <div class="separator"></div>
    <p class="text-center">{{ trans('request.no-privileges-desc') }}!</p>
  </div>
  </div>
  </div>
  @else
    <div class="well">
      <p class="lead text-orange text-center">{!! trans('request.no-refunds') !!}</p>
    </div>
<div class="well">
  <form action="{{route('request_search')}}" style="display: inline;" method="get">
      <div class="form-group">
        <label for="category_id">{{ trans('torrent.category') }}</label>
        <select name="category_id" class="form-control">
          @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
          @endforeach
        </select>
      </div>
        <input class="form-control" placeholder="{{ trans('common.search') }}" name="name" type="text" id="name">
        <button type="submit" class="btn btn-sm btn-primary" title="{{ trans('common.search') }}"><i class="fa fa-search"></i> {{ trans('common.search') }}</button>
        <a href="{{ route('requests') }}" class="btn btn-sm btn-warning" title="{{ trans('common.reset') }}"><i class="fa fa-repeat"></i> {{ trans('common.reset') }}</a>
        </form>
        <form action="{{route('requests')}}" style="display: inline; float:right;" method="get">
          <button type="submit" class="btn btn-sm btn-primary">{{ trans('request.all-requests') }}</button>
          <button type="submit" class="btn btn-sm btn-primary" name="filled_requests" value="true" id="filled_requests">{{ trans('request.view-filled') }}</button>
          <button type="submit" class="btn btn-sm btn-primary" name="unfilled_requests" value="true" id="unfilled_requests">{{ trans('request.view-unfilled') }}</button>
          <button type="submit" class="btn btn-sm btn-primary" name="my_requests" value="true" id="my_requests">{{ trans('request.my-requests') }}</button>
        </form>
    </div>
</div>

<div class="container-fluid">
<div class="block">
  <div class="header gradient light_blue">
    <div class="inner_content">
      <h1>{{ trans('request.requests') }}</h1>
    </div>
  </div>
  <br>
  <span class="badge-user" style="float: right;"><a href="{{ route('requests') }}"><strong>{{ trans('request.requests') }}:</strong></a> {{ $num_req }} |
  <a href="{{ route('requests') }}" name="filled_requests" value="true" id="filled_requests"><strong>{{ trans('request.filled') }}:</strong></a> {{ $num_fil }} |
  <a href="{{ route('requests') }}" name="unfilled_requests" value="true" id="unfilled_requests"><strong>{{ trans('request.unfilled') }}:</strong></a> {{ $num_unfil }}
  | <strong>{{ trans('request.total-bounty') }}:</strong> {{ $total_bounty }} {{ trans('bon.bon') }} | <strong>{{ trans('request.bounty-claimed') }}:</strong> {{ $claimed_bounty }} {{ trans('bon.bon') }} | <strong>{{ trans('request.bounty-unclaimed') }}:</strong> {{ $unclaimed_bounty }} {{ trans('bon.bon') }}</span>
  <a href="{{ route('add_request') }}" role="button" data-id="0" data-toggle="tooltip" title="" data-original-title="{{ trans('request.add-request') }}!" class="btn btn btn-success">{{ trans('request.add-request') }}</a>
  <div class="table-responsive">
    <table class="table table-condensed table-striped table-bordered">
      <thead>
        <tr>
          <th class="torrents-icon">{{ trans('torrent.category') }}</th>
          <th class="torrents-filename col-sm-6">{{ trans('request.request') }}</th>
          <th>{{ trans('common.author') }}</th>
          <th>{{ trans('request.votes') }}</th>
          <th>{{ trans('common.comments') }}</th>
          <th>{{ trans('request.bounty') }}</th>
          <th>{{ trans('request.age') }}</th>
          <th>{{ trans('request.claimed') }} / {{ trans('request.filled') }}</th>
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
            <button class="btn btn-xs btn-primary" data-toggle="tooltip" title="" data-original-title="{{ trans('request.request') }} {{ strtolower(trans('request.claimed')) }}">
              <i class="fa fa-suitcase"></i> {{ trans('request.claimed') }}</button>
            @elseif($r->filled_hash != null && $r->approved_by == null)
            <button class="btn btn-xs btn-info" data-toggle="tooltip" title="" data-original-title="{{ trans('request.request') }} {{ strtolower(trans('request.pending')) }}">
              <i class="fa fa-question-circle"></i> {{ trans('request.pending') }}</button>
            @elseif($r->filled_hash == null)
            <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="" data-original-title="{{ trans('request.request') }} {{ strtolower(trans('request.unfilled')) }}">
              <i class="fa fa-times-circle"></i> {{ trans('request.unfilled') }}</button>
            @else
            <button class="btn btn-xs btn-success" data-toggle="tooltip" title="" data-original-title="{{ trans('request.request') }} {{ strtolower(trans('request.filled')) }}">
              <i class="fa fa-check-circle"></i> {{ trans('request.filled') }}</button>
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
