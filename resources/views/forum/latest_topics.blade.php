@extends('layout.default')

@section('title')
    <title>@lang('common.latest-topics') - @lang('forum.forums') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Forum @lang('common.latest-topics')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('forum_index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('forum.forums')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forum_latest_topics') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('common.latest-topics')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="box container">
        @include('forum.buttons')
        <form role="form" method="GET" action="{{ route('forum_search_form') }}">
            <input type="hidden" name="sorting" value="created_at">
            <input type="hidden" name="direction" value="desc">
            <input type="text" name="name" id="name" value="{{ (isset($params) && is_array($params) && array_key_exists('name',$params) ? $params['name'] : '') }}" placeholder="@lang('forum.topic-quick-search')"
                   class="form-control">
        </form>
        <div class="forum-categories">
            <table class="table table-bordered table-hover">
                <thead class="no-space">
                <tr class="no-space">
                    <td colspan="5" class="no-space">
                        <div class="header gradient teal some-padding">
                            <div class="inner_content">
                                <h1 class="no-space">Latest Topics</h1>
                            </div>
                        </div>
                    </td>
                </tr>
                </thead>
                <thead>
                    <thead>
                    <tr>
                        <th>@lang('forum.forum')</th>
                        <th>@lang('forum.topic')</th>
                        <th>@lang('forum.author')</th>
                        <th>@lang('forum.stats')</th>
                        <th>@lang('forum.last-post-info')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($results as $r)
                        <tr>
                            <td class="f-display-topic-icon"><span
                                        class="badge-extra text-bold">{{ $r->forum->name }}</span></td>
                            <td class="f-display-topic-title">
                                <strong><a href="{{ route('forum_topic', ['slug' => $r->slug, 'id' => $r->id]) }}">{{ $r->name }}</a></strong>
                                @if ($r->state == "close") <span
                                        class='label label-sm label-default'>{{ strtoupper(trans('forum.closed')) }}</span> @endif
                                @if ($r->approved == "1") <span
                                        class='label label-sm label-success'>{{ strtoupper(trans('forum.approved')) }}</span> @endif
                                @if ($r->denied == "1") <span
                                        class='label label-sm label-danger'>{{ strtoupper(trans('forum.denied')) }}</span> @endif
                                @if ($r->solved == "1") <span
                                        class='label label-sm label-info'>{{ strtoupper(trans('forum.solved')) }}</span> @endif
                                @if ($r->invalid == "1") <span
                                        class='label label-sm label-warning'>{{ strtoupper(trans('forum.invalid')) }}</span> @endif
                                @if ($r->bug == "1") <span
                                        class='label label-sm label-danger'>{{ strtoupper(trans('forum.bug')) }}</span> @endif
                                @if ($r->suggestion == "1") <span
                                        class='label label-sm label-primary'>{{ strtoupper(trans('forum.suggestion')) }}</span> @endif
                                @if ($r->implemented == "1") <span
                                        class='label label-sm label-success'>{{ strtoupper(trans('forum.implemented')) }}</span> @endif
                            </td>
                            <td class="f-display-topic-started"><a
                                        href="{{ route('profile', ['username' => Str::slug($r->first_post_user_username), 'id' => $r->first_post_user_id]) }}">{{ $r->first_post_user_username }}</a>
                            </td>
                            <td class="f-display-topic-stats">
                                {{ $r->num_post - 1 }} @lang('forum.replies')
                                \ {{ $r->views }} @lang('forum.views')
                            </td>
                            <td class="f-display-topic-last-post">
                                <a href="{{ route('profile', ['username' => Str::slug($r->last_post_user_username), 'id' => $r->last_post_user_id]) }}">{{ $r->last_post_user_username }}</a>,
                                @if($r->last_reply_at && $r->last_reply_at != null)
                                    <time datetime="{{ date('d-m-Y h:m', strtotime($r->last_reply_at)) }}">
                                        {{ date('M d Y', strtotime($r->last_reply_at)) }}
                                    </time>
                                @else
                                    <time datetime="N/A">
                                        N/A
                                    </time>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-center col-md-12">
                {{ $results->links() }}
            </div>
        </div>
    </div>
@endsection
