@extends('layout.default')

@section('title')
    <title>{{ trans('user.invite-tree') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
<li>
    <a href="#" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('user.invite-tree') }}</span>
    </a>
</li>
@endsection

@section('content')
<div class="container">
<div class="block">
  <h2><a class="view-user" data-id="{{ $user->id }}" data-slug="{{ $user->username }}" href="{{ route('profile', ['username' =>  $user->username, 'id' => $user->id]) }}">{{ $user->username }}</a>
{{ trans('user.invite-tree') }}</h2>
  <hr>
  <div class="row">
  <div class="col-sm-12">
  <h2>{{ trans('user.invites-send') }}</h2>
  <table class="table table-condensed table-striped table-bordered table-hover">
    <thead>
      <tr>
        <th>{{ trans('user.sender') }}</th>
        <th>{{ trans('common.email') }}</th>
        <th>{{ trans('user.code') }}</th>
        <th>{{ trans('user.created-on') }}</th>
        <th>{{ trans('user.expires-on') }}</th>
        <th>{{ trans('user.accepted-by') }}</th>
        <th>{{ trans('user.accepted-at') }}</th>
      </tr>
    </thead>
    <tbody>
      @if(count($records) == 0)
      <p>{{ trans('user.no-logs') }}</p>
      @else
    @foreach($records as $record)
      <tr>
        <td>
			     <a class="view-user" data-id="{{ $record->sender->id }}" data-slug="{{ $record->sender->username }}" href="{{ route('profile', ['username' =>  $record->sender->username, 'id' => $record->sender->id]) }}">{{ $record->sender->username }}</a>
        </td>
        <td>
          {{ $record->email }}
        </td>
        <td>
          {{ $record->code }}
        </td>
        <td>
          {{ $record->created_at }}
        </td>
        <td>
          {{ $record->expires_on }}
        </td>
        <td>
          @if($record->accepted_by != null)
          <a class="view-user" data-id="{{ $record->reciever->id }}" data-slug="{{ $record->reciever->username }}" href="{{ route('profile', ['username' =>  $record->reciever->username, 'id' => $record->reciever->id]) }}">{{ $record->reciever->username }}</a>
          @else
          N/A
          @endif
        </td>
        <td>
          @if($record->accepted_at != null)
          {{ $record->accepted_at }}
          @else
          N/A
          @endif
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
