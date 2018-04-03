@extends('layout.default')

@section('title')
    <title>WarningLog - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
<li>
    <a href="#" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">WarningLog</span>
    </a>
</li>
@endsection

@section('content')
<div class="container">
<div class="block">
  <h2><a class="view-user" data-id="{{ $user->id }}" data-slug="{{ $user->username }}" href="{{ route('profile', ['username' =>  $user->username, 'id' => $user->id]) }}">{{ $user->username }}</a>
Warnings Log</h2>
  <hr>
  <div class="row">
  <div class="col-sm-12">
  <h2>Warnings <span class="text-blue"><strong><i class="fa fa-note"></i> {{ $warningcount }} </strong></span></h2>
  <table class="table table-condensed table-striped table-bordered table-hover">
    <thead>
      <tr>
        <th>User</th>
        <th>Warned By</th>
        <th>Torrent</th>
        <th>Reason</th>
        <th>Created On</th>
        <th>Expires On</th>
        <th>Active</th>
        <th>Deactivate?</th>
      </tr>
    </thead>
    <tbody>
      @if(count($warnings) == 0)
      <p>The are no warnings in the database for this user!</p>
      @else
    @foreach($warnings as $warning)
      <tr>
        <td>
			<a class="view-user" data-id="{{ $warning->warneduser->id }}" data-slug="{{ $warning->warneduser->username }}" href="{{ route('profile', ['username' =>  $warning->warneduser->username, 'id' => $warning->warneduser->id]) }}">{{ $warning->warneduser->username }}</a>
        </td>
        <td>
			<a class="view-torrent" data-id="{{ $warning->staffuser->id }}" data-slug="{{ $warning->staffuser->username }}" href="{{ route('profile', ['username' => $warning->staffuser->username, 'id' => $warning->staffuser->id]) }}">{{ $warning->staffuser->username }}</a>
        </td>
        <td>
		  <a class="view-torrent" data-id="{{ $warning->torrenttitle->id }}" data-slug="{{ $warning->torrenttitle->name }}" href="{{ route('torrent', array('slug' =>$warning->torrenttitle->slug, 'id' => $warning->torrenttitle->id)) }}">{{ $warning->torrenttitle->name }}</a>
        </td>
        <td>
          {{ $warning->reason }}
        </td>
        <td>
          {{ $warning->created_at }}
        </td>
        <td>
          {{ $warning->expires_on }}
        </td>
        <td>
					@if($warning->active == 1)
					<span class='label label-success'>Yes</span>
					@else
					<span class='label label-danger'>Expired</span>
					@endif
        </td>
        <td>
          @if($warning->active == 1)
          <a title="Deactivate Warning" href="{{ route('deactivateWarning', ['id' => $warning->id]) }}"><i class="fa fa-lg fa-check"></i></a>
          @endif
        </td>
      </tr>
      @endforeach
      @endif
    </tbody>
  </table>
  </div>
</div>
{{ $warnings->links() }}
</div>
</div>
@endsection
