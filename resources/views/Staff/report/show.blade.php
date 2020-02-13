@extends('layout.default')

@section('title')
    <title>Reports - @lang('staff.staff-dashboard') - {{ config('other.title') }}</title>
@endsection

@section('meta')
<meta name="description" content="Reports - @lang('staff.staff-dashboard')"> @endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.reports.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.reports-log')</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.reports.show', ['id' => $report->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('common.report')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <h2>Report Details
                @if ($report->solved == 0)
                    <span class="text-red"><strong><i class="{{ config('other.font-awesome') }} fa-times"></i> UNSOLVED
                        </strong></span>
                @else
                    <span class="text-green"><strong><i class="{{ config('other.font-awesome') }} fa-check"></i> SOLVED BY
                            <a class="name" href="{{ route('users.show', ['username' => $report->staff->username]) }}">
                                {{ $report->staff->username }}
                            </a>
                        </strong>
                    </span>
                @endif
            </h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <h3>Reported @lang('common.user'):</h3>
                    <p class="well well-sm">
                        <a href="{{ route('users.show', ['username' => $report->reported->username]) }}">
                            {{ $report->reported->username }}
                        </a>
                    </p>
                    <h3>@lang('common.reporter'):</h3>
                    <p class="well well-sm">
                        <a href="{{ route('users.show', ['username' => $report->reporter->username]) }}">
                            {{ $report->reporter->username }}
                        </a>
                    </p>
    
                    @if ($report->torrent)
                        <h3>@lang('torrent.torrent') @lang('torrent.title'):</h3>
                        <p class="well well-sm">
                            <a href="{{ route('torrent', ['id' => $report->torrent->id]) }}">
                                {{ $report->title }}
                            </a>
                        </p>
                    @endif
    
                    @if ($report->request)
                        <h3>@lang('torrent.torrent-request') @lang('request.title'):</h3>
                        <p class="well well-sm">
                            <a href="{{ route('request', ['id' => $report->request->id]) }}">
                                {{ $report->title }}
                            </a>
                        </p>
                    @endif
    
                    <h3>@lang('common.message'):</h3>
                    <p class="well well-lg">
                        {{ $report->message }}
                    </p>
    
                    @if (count($urls) > 0)
                        <h3>Referenced Links:</h3>
                        <p class="well">
                            @foreach ($urls as $url)
                                <a href="{{ $url }}" target="_blank">{{ $url }}</a><br />
                            @endforeach
                        </p>
                    @endif
                </div>
            </div>
    
            <h2>Resolve @lang('common.report')</h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <form role="form" method="POST" action="{{ route('staff.reports.update', ['id' => $report->id]) }}">
                        @csrf
                        @if ($report->solved == 0)
                            <div class="form-group">
                                <label for="message">Verdict</label>
                                <label>
                                    <textarea name="verdict" class="form-control"></textarea>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary">@lang('common.submit')</button>
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
