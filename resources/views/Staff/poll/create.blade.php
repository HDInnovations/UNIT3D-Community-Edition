@extends('layout.default')

@section('title')
    <title>Polls - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.polls.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('poll.polls') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.polls.create') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('poll.create-poll') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="page-title">
                <h1>{{ __('poll.create-poll') }}</h1>
            </div>
            <div class="col-md-12">
                <div class="panel panel-chat">
                    <div class="panel-heading">
                        {{ __('common.create') }}
                        {{ trans_choice('common.a-an-art',false) }}
                        {{ __('poll.poll') }}
                    </div>
                    <div class="panel-body">
                        @include('Staff.poll.forms.make')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
