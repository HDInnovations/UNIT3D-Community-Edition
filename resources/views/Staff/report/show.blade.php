@extends('layout.default')

@section('title')
    <title>Reports - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Reports - {{ __('staff.staff-dashboard') }}"> @endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.reports.index') }}" class="breadcrumb__link">
            {{ __('staff.reports-log') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.report') }} Details
    </li>
@endsection

@section('page', 'page__poll--show')

@section('main')
    @if ($report->torrent)
        <section class="panelV2">
            <h2 class="panel__heading">{{ __('torrent.torrent') }} {{ __('torrent.title') }}</h2>
            <div class="panel__body">
                <a href="{{ route('torrent', ['id' => $report->torrent->id]) }}">
                    {{ $report->title }}
                </a>
            </div>
        </section>
    @endif
    @if ($report->request)
        <section class="panelV2">
            <h2>{{ __('torrent.torrent-request') }} {{ __('request.title') }}</h2>
            <div class="panel__body">
                <a href="{{ route('request', ['id' => $report->request->id]) }}">
                    {{ $report->title }}
                </a>
            </div>
        </section>
    @endif
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.message') }}</h2>
        <div class="panel__body">{{ $report->message }}</div>
    </section>
    @if (count($urls) > 0)
        <section class="panelV2">
            <h2 class="panel__heading">Referenced Links:</h2>
            <div class="panel__body">
                <ul style="margin: 0; padding-left: 20px">
                    @foreach ($urls as $url)
                        <li>
                            <a href="{{ $url }}" target="_blank">{{ $url }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif
    @if ($report->solved)
        <section class="panelV2">
            <h2 class="panel__heading">Verdict</h2>
            <div class="panel__body">{{ $report->verdict }}</div>
        </section>
    @else
        <section class="panelV2">
            <h2 class="panel__heading">Resolve {{ __('common.report') }}</h2>
            <div class="panel__body">
                <form
                    class="form"
                    method="POST"
                    action="{{ route('staff.reports.update', ['id' => $report->id]) }}"
                >
                    @csrf
                    @livewire('bbcode-input', ['name' => 'verdict', 'label' => 'Verdict', 'required' => true])
                    <p class="form__group">
                        <button class="form__button form__button--filled">
                            {{ __('common.submit') }}
                        </button>
                    </p>
                </form>
            </div>
        </section>
    @endif
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">Reported {{ __('common.user') }}</h2>
        <div class="panel__body">
            <x-user_tag :anon="false" :user="$report->reported" />
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.reporter') }}</h2>
        <div class="panel__body"
            <x-user_tag :anon="false" :user="$report->reporter" />
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">Solved by</h2>
        <div class="panel__body">
            @if ($report->solved)
                <x-user_tag :anon="false" :user="$report->staff" />
            @else
                <span class="text-red">
                    <i class="{{ config('other.font-awesome') }} fa-times"></i>
                    UNSOLVED
                </span>
            @endif
        </div>
    </section>
@endsection
