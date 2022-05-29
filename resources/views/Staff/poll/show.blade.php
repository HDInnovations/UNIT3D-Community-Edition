@extends('layout.default')

@section('title')
    <title>Poll Results - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.polls.index') }}" class="breadcrumb__link">
            {{ __('poll.polls') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('poll.results') }}
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="page-title">
            <h1>{{ __('poll.poll') }} {{ __('poll.results') }}</h1>
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
                                    {{ __('poll.vote') }}
                                @else
                                    {{ __('poll.votes') }}
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
