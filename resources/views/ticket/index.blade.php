@extends('layout.default')

@section('title')
    <title>@lang('ticket.helpdesk') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('tickets.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('ticket.helpdesk')</span>
        </a>
    </li>
@endsection

@section('content')
    <style>
        td {
            vertical-align: middle !important;
        }
    </style>
    <div class="box container">
        @livewire('ticket-search')
    </div>
@endsection
