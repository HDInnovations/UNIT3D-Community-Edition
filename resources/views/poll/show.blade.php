@extends('layout.default')

@section('title')
    <title>@lang('poll.poll') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('polls') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('poll.polls')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('poll', ['id' => $poll->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $poll->title }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="box container">
        <div class="page-title">
            <h1>{{ $poll->title }}</h1>
        </div>
        <hr>
        <div class="forum-categories">
            <div class="forum-category">
                <div class="forum-category-title col-md-12">
                    <div class="forum-category-childs">
                        @include('poll.forms.vote')
                        @if ($poll->multiple_choice)
                            <span class="badge-user text-bold text-red">@lang('poll.multiple-choice')</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
