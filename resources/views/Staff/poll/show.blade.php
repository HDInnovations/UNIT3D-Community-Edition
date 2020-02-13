@extends('layout.default')

@section('title')
    <title>Poll Results - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.polls.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('poll.polls')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.polls.show', ['id' => $poll->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $poll->slug }} @lang('poll.results')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="page-title">
            <h1>@lang('poll.poll') @lang('poll.results')</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-chat">
                    <div class="panel-heading">
                        <h1 class="panel-title">{{ $poll->title }}</h1>
                    </div>
                    <div class="panel-body">
                        @foreach ($poll->options as $option)
                            <strong>{{ $option->name }}</strong><span class="pull-right">{{ $option->votes }}
                                @if ($option->votes == 1)
                                    @lang('poll.vote')
                                @else
                                    @lang('poll.votes')
                                @endif
                                </span>
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped active" role="progressbar"
                                    aria-valuenow="{{ $option->votes }}" aria-valuemin="0" aria-valuemax="100"
                                    style="width: {{ $option->votes }}%;">
                                    {{ $option->votes }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
