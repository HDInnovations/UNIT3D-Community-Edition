@extends('layout.default')

@section('title')
    <title>Reports - Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Reports - Staff Dashboard"> @endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('getReports') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Reports</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('getReport',['report_id'=>$report->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Report</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <h2>Report Details
                @if($report->solved == 0)
                    <span class="text-red"><strong><i class="fa fa-times"></i> UNSOLVED </strong></span>
                @else
                    <span class="text-green"><strong><i class="fa fa-check"></i> SOLVED BY <a class="name"
                                                                                              href="{{ route('profile', ['username' => $report->staffuser->username, 'id' => $report->staff_id ]) }}">{{ $report->staffuser->username }}</a></strong></span>
                @endif
            </h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <h3>Reported By:</h3>
                    <p class="well well-sm">
                        <a href="{{ route('profile', ['username' => $report->reportuser->username, 'id' => $report->reportuser->id]) }}">
                            {{ $report->reportuser->username }}
                        </a>
                    </p>

                    <h3>Title:</h3>
                    <p class="well well-sm">
                        {{-- <a href="{{ route('torrent', ['slug' => str_slug($torrent->name), 'id' => $torrent->id]) }}"> --}}
                        {{ $report->title }}
                        {{-- </a> --}}
                    </p>

                    <h3>Message:</h3>
                    <p class="well well-lg">
                        {{ $report->message }}
                    </p>
                </div>
            </div>

            <h2>Resolve Report</h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <form role="form" method="POST" action="{{ route('solveReport',['report_id'=>$report->id]) }}">
                        {{ csrf_field() }}
                        @if($report->solved == 0)
                            <div class="form-group">
                                <label for="message">Verdict</label>
                                <textarea name="verdict" class="form-control"></textarea>
                            </div>
                        @else
                            <div class="form-group">
                                <label for="message">Verdict</label>
                                <textarea name="verdict" class="form-control"
                                          disabled="true">{{ $report->verdict }}</textarea>
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
