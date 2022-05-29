@extends('layout.default')

@section('title')
    <title>{{ __('poll.poll') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('polls') }}" class="breadcrumb__link">
            {{ __('poll.polls') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $poll->title }}
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="page-title">
            <h1>{{ __('poll.poll') }}</h1>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-chat">
                    <div class="panel-heading">
                        <h1 class="panel-title">{{ $poll->title }}</h1>
                    </div>
                    <div class="panel-body">
                        @include('poll.forms.vote')
                        @if ($poll->multiple_choice)
                            <span class="badge-user text-bold text-red poll-note">{{ __('poll.multiple-choice') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
