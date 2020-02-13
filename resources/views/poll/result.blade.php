@extends('layout.default')

@section('title')
    <title>@lang('poll.results') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('polls') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('poll.polls')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('poll', ['slug' => $poll->slug]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $poll->slug }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('poll_results', ['slug' => $poll->slug]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('poll.results')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="page-title">
            <h1>@lang('poll.results')</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-chat">
                    <div class="panel-heading">
                        <h1 class="panel-title">{{ $poll->title }}</h1>
                    </div>
                    <div class="panel-body">
                        @foreach ($poll->options as $option)
                            @php
                            $vote_value = $total_votes !== 0 ? ($option->votes / $total_votes) * 100 : 0;
                            $vote_value = round($vote_value, 2);
                            @endphp
                            <strong>{{ $option->name }}</strong><span class="pull-right">{{ $option->votes }}
                                @if ($option->votes == 1)
                                    @lang('poll.vote')
                                @else
                                    @lang('poll.votes')
                                @endif
                            </span>
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped active" role="progressbar"
                                    aria-valuenow="{{ $vote_value }}" aria-valuemin="0" aria-valuemax="100"
                                    style="width: {{ $vote_value }}%;">
                                    {{ $vote_value }}%
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
