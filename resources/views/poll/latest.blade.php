@extends('layout.default')

@section('title')
    <title>@lang('poll.polls') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('polls') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('poll.polls')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="header gradient green">
            <div class="inner_content">
                <h1>@lang('poll.current')</h1>
            </div>
        </div>
        <div class="blocks">
            @foreach ($polls as $poll)
                <a href="{{ route('poll', ['id' => $poll->id]) }}" style="padding: 0 2px;">
                    <div class="general media_blocks">
                        <h2 style="font-size: 20px;"><i class="{{ config('other.font-awesome') }} fa-pie-chart"></i>
                            {{ $poll->title }}</h2>
                        <span></span>
                        <h2 style="font-size: 12px;">@lang('poll.vote-now')</h2>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
