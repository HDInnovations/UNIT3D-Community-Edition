@extends('layout.default')

@section('title')
    <title>Polls - {{ config('other.title') }}</title>
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
@endsection

@section('content')
    <div class="container box">
        <h2>@lang('poll.poll')</h2>
        <a href="{{ route('staff.polls.create') }}" class="btn btn-primary">
            @lang('common.add')
            @lang(trans_choice('common.a-an-art',false))
            @lang('poll.poll')
        </a>
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>@lang('poll.title')</th>
                        <th>@lang('common.date')</th>
                        <th>@lang('common.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($polls as $poll)
                        <tr>
                            <td><a href="{{ url('/dashboard/polls/' . $poll->id) }}">{{ $poll->title }}</a></td>
                            <td>{{ date('d M Y', $poll->created_at->getTimestamp()) }}</td>
                            <td>
                                <a href="#" class="btn btn-warning">@lang('common.edit')</a>
                                <a href="#" class="btn btn-danger">@lang('common.delete')</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
