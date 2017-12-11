@extends('layout.default')

@section('title')
<title>Results - Forums - {{ Config::get('other.title') }}</title>
@stop

@section('meta')
<meta name="description" content="Forum Search Results">
@stop

@section('breadcrumb')
<li>
    <a href="{{ route('forum_index') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Forums</span>
    </a>
</li>
<li>
    <a href="{{ route('search') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Search Results</span>
    </a>
</li>
@stop

@section('content')
<div class="box container">
    <div class="f-display">
        <div class="f-display-info col-md-12">
            <h1 class="f-display-info-title">Search Results</h1>
            <p class="f-display-info-description">Please See Your Results Below</p>
        </div>
        <div class="f-display-table-wrapper col-md-12">
            <table class="f-display-topics table col-md-12">
                <thead>
                    <tr>
                        <th>Forum</th>
                        <th>Topic</th>
                        <th>Started by</th>
                        <th>Stats</th>
                        <th>Last Post Info</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $r)
                    <tr>
                        <td class="f-display-topic-icon"><span class="badge-extra text-bold">{{ $r->forum->name }}</span></td>
                        <td class="f-display-topic-title">
                            <strong><a href="{{ route('forum_topic', array('slug' => $r->slug, 'id' => $r->id)) }}">{{ $r->name }}</a></strong>
                            @if($r->state == "close") <span class='label label-sm label-default'>CLOSED</span> @endif
                            @if($r->approved == "1") <span class='label label-sm label-success'>APPROVED</span> @endif
                            @if($r->denied == "1") <span class='label label-sm label-danger'>DENIED</span> @endif
                            @if($r->solved == "1") <span class='label label-sm label-info'>SOLVED</span> @endif
                            @if($r->invalid == "1") <span class='label label-sm label-warning'>INVALID</span> @endif
                            @if($r->bug == "1") <span class='label label-sm label-danger'>BUG</span> @endif
                            @if($r->suggestion == "1") <span class='label label-sm label-primary'>SUGGESTION</span> @endif
                        </td>
                        <td class="f-display-topic-started"><a href="{{ route('profil', ['username' => $r->first_post_user_username, 'id' => $r->first_post_user_id]) }}">{{ $r->first_post_user_username }}</a></td>
                        <td class="f-display-topic-stats">
                            {{ $r->num_post - 1 }} {{ trans('forum.replies') }} \ {{ $r->views }} {{ trans('forum.views') }}
                        </td>
                        <td class="f-display-topic-last-post">
                            <a href="{{ route('profil', ['username' => $r->last_post_user_username, 'id' => $r->last_post_user_id]) }}">{{ $r->last_post_user_username }}</a> on <time datetime="{{ date('d-m-Y h:m', strtotime($r->updated_at)) }}">
                                {{ date('M d Y', strtotime($r->updated_at)) }}
                             </time>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="f-display-pagination col-md-12">
            {{ $results->links() }}
        </div>
    </div>
</div>
@stop
