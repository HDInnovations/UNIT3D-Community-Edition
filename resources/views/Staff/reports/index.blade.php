@extends('layout.default')

@section('title')
    <title>Reports - Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Reports - Staff Dashboard">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('getReports') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Reports</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <h2>Reports</h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <p class="text-red"><strong><i class="fa fa-list"></i> Reports</strong></p>
                    <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Title</th>
                            <th>Reporter</th>
                            <th class="col-md-2">Created</th>
                            <th>Judge</th>
                            <th>Solved</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($reports) == 0)
                            <p>The are no reports in database</p>
                        @else
                            @foreach($reports as $r)
                                <tr>
                                    <td>
                                        {{ $r->id }}
                                    </td>
                                    <td>
                                        {{ $r->type }}
                                    </td>
                                    <td>
                                        <a href="{{ route('getReport',['report_id'=>$r->id]) }}">{{ $r->title }}</a>
                                    </td>
                                    <td class="user-name">
                                        <a class="name"
                                           href="{{ route('profile', ['username' => $r->reporter->username, 'id' => $r->reporter_id ]) }}">
                                            {{ $r->reporter->username }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $r->created_at->toDayDateTimeString() }}
                                    </td>
                                    <td class="user-name">
                                        <a class="name"
                                           href="{{ $r->staff_id ? route('profile', ['username' => $r->staff->username, 'id' => $r->staff_id ]) : route('home')}}">
                                            {{ $r->staff_id ? $r->staff->username : "" }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($r->solved == 0)
                                            <span class="text-red">
                                                <strong><i class="fa fa-times"></i> NO</strong>
                                            </span>
                                        @else
                                            <span class="text-green">
                                                <strong><i class="fa fa-check"></i> YES</strong>
                                            </span>
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
            <div class="text-center">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
@endsection
