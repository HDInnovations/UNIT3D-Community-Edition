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
    <div class="container">
        <div class="block">
            <div class="header gradient silver">
                <div class="inner_content">
                    <div class="page-title">
                        <h1 style="margin: 0;">@lang('ticket.helpdesk')</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box container">
        @livewire('ticket-search')
    </div>
@endsection
