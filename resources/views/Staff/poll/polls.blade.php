@extends('layout.default')

@section('title')
<title>Polls - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
<li>
    <a href="{{ route('getPolls') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Polls</span>
    </a>
</li>
@endsection

@section('content')
<div class="container box">
  <h2>Manage Polls</h2>
  <a href="{{ route('getCreatePoll') }}" class="btn btn-primary">Add New Poll</a>
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>Title</th>
        <th>Date</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($polls as $poll)
      <tr>
        <td><a href="{{ url('/staff_dashboard/poll/' . $poll->id) }}">{{ $poll->title }}</a></td>
        <td>{{ date('d M Y', $poll->created_at->getTimestamp()) }}</td>
        <td>
          <a href="#" class="btn btn-warning">Edit</a>
          <a href="#" class="btn btn-danger">Delete</a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
