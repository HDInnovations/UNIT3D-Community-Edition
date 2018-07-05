@extends('layout.default')

@section('title')
    <title>User Notes - Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="User Notes - Staff Dashboard">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('getNotes') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">User Notes</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <h2>User Notes Log</h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <h2>Notes <span class="text-blue"><strong><i class="fa fa-note"></i> {{ $notes->count() }} </strong></span>
                    </h2>
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>Staff</th>
                                <th>Message</th>
                                <th>Created On</th>
                                <th>Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($notes) == 0)
                                <p>The are no notes in database for this user!</p>
                            @else
                                @foreach($notes as $n)
                                    <tr>
                                        <td>
                                            <a class="name"
                                               href="{{ route('profile', ['username' => $n->noteduser->username, 'id' => $n->user_id ]) }}">{{ $n->noteduser->username }}</a>
                                        </td>
                                        <td>
                                            <a class="name"
                                               href="{{ route('profile', ['username' => $n->staffuser->username, 'id' => $n->staff_id ]) }}">{{ $n->staffuser->username }}</a>
                                        </td>
                                        <td>
                                            {{ $n->message }}
                                        </td>
                                        <td>
                                            {{ $n->created_at->toDayDateTimeString() }}
                                            ({{ $n->created_at->diffForHumans() }})
                                        </td>
                                        <td>
                                            <a href="{{ route('deleteNote', ['id' => $n->id]) }}"
                                               class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="text-center">
                {{ $notes->links() }}
            </div>
        </div>
    </div>
@endsection
