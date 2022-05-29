@extends('layout.default')

@section('title')
    <title>Polls - {{ config('other.title') }}</title>
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
        {{ __('common.edit') }}
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="page-title">
                <h1>{{ __('poll.edit-poll') }}</h1>
            </div>
            <div class="col-md-12">
                <div class="panel panel-chat">
                    <div class="panel-heading">
                        {{ __('poll.edit-poll') }} {{$poll->title}}
                    </div>
                    <div class="panel-body">
                        @include('Staff.poll.forms.update')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
