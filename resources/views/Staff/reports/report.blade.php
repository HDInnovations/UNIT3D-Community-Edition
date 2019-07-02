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
                @if ($report->solved == 0)
                    <span class="text-red"><strong><i class="{{ config('other.font-awesome') }} fa-times"></i> UNSOLVED </strong></span>
                @else
                    <span class="text-green"><strong><i class="{{ config('other.font-awesome') }} fa-check"></i> SOLVED BY <a class="name"
                                                                                              href="{{ route('profile', ['username' => $report->staff->username, 'id' => $report->staff_id ]) }}">{{ $report->staff->username }}</a></strong></span>
                @endif
            </h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <h3>Reported User:</h3>
                    <p class="well well-sm">
                        <a href="{{ route('profile', ['username' => $report->reported->username, 'id' => $report->reported->id]) }}">
                            {{ $report->reported->username }}
                        </a>
                    </p>
                    <h3>Reported By:</h3>
                    <p class="well well-sm">
                        <a href="{{ route('profile', ['username' => $report->reporter->username, 'id' => $report->reporter->id]) }}">
                            {{ $report->reporter->username }}
                        </a>
                    </p>

                    @if ($report->torrent)
                        <h3>@lang('torrent.torrent') Title:</h3>
                        <p class="well well-sm">
                            <a href="{{ route('torrent', ['slug' => Str::slug($report->title), 'id' => $report->torrent->id]) }}">
                                {{ $report->title }}
                            </a>
                        </p>
                    @endif

                    @if ($report->request)
                        <h3>@lang('torrent.torrent-request') Title:</h3>
                        <p class="well well-sm">
                            <a href="{{ route('request', ['id' => $report->request->id]) }}">
                                {{ $report->title }}
                            </a>
                        </p>
                    @endif

                    <h3>Message:</h3>
                    <p class="well well-lg">
                        {{ $report->message }}
                    </p>

                    @if (count($urls) > 0)
                        <h3>Referenced Links:</h3>
                        <p class="well">
                            @foreach ($urls as $url)
                                <a href="{{$url}}" target="_blank">{{$url}}</a><br/>
                            @endforeach
                        </p>
                    @endif
                </div>
            </div>

            <h2>Resolve Report</h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <form role="form" method="POST" action="{{ route('solveReport',['report_id'=>$report->id]) }}">
                        @csrf
                        @if ($report->solved == 0)
                            <div class="form-group">
                                <label for="message">Verdict</label>
                                <textarea name="verdict" class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        @else
                            <div class="form-group">
                                <h3>Verdict</h3>
                                <p class="well">
                                    {{ $report->verdict }}
                                </p>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
