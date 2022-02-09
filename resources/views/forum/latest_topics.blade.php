@extends('layout.default')

@section('title')
    <title>{{ __('common.latest-topics') }} - {{ __('forum.forums') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Forum {{ __('common.latest-topics') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('forums.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('forum.forums') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forum_latest_topics') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('common.latest-topics') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="box container">
        <div class="button-holder">
            @include('forum.buttons')
            <div class="button-right">
                <form role="form" method="GET" action="{{ route('forum_search_form') }}" class="form-inline">
                    <input type="hidden" name="sorting" value="created_at">
                    <input type="hidden" name="direction" value="desc">
                    <label for="name"></label><input type="text" name="name" id="name"
                                                     value="{{ isset($params) && is_array($params) && array_key_exists('name', $params) ? $params['name'] : '' }}"
                                                     placeholder="{{ __('forum.topic-quick-search') }}"
                                                     class="form-control">
                    <button type="submit" class="btn btn-success">
                        <i class="{{ config('other.font-awesome') }} fa-search"></i> {{ __('common.search') }}
                    </button>
                </form>
            </div>
        </div>
        <div class="forum-categories">
            <table class="table table-bordered table-hover">
                <thead class="no-space">
                <tr class="no-space">
                    <td colspan="5" class="no-space">
                        <div class="header gradient teal some-padding">
                            <div class="inner_content">
                                <h1 class="no-space">{{ __('common.latest-topics') }}</h1>
                            </div>
                        </div>
                    </td>
                </tr>
                </thead>
                <thead>
                <thead>
                <tr>
                    <th>{{ __('forum.forum') }}</th>
                    <th>{{ __('forum.topic') }}</th>
                    <th>{{ __('forum.author') }}</th>
                    <th>{{ __('forum.stats') }}</th>
                    <th>{{ __('forum.last-post-info') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($results as $r)
                    <tr>
                        <td class="f-display-topic-icon"><span
                                    class="badge-extra text-bold">{{ $r->forum->name }}</span>
                        </td>
                        <td class="f-display-topic-title">
                            <strong><a href="{{ route('forum_topic', ['id' => $r->id]) }}">{{ $r->name }}</a></strong>
                            @if ($r->state == "close") <span
                                    class='label label-sm label-default'>{{ strtoupper(__('forum.closed')) }}</span> @endif
                            @if ($r->approved == "1") <span
                                    class='label label-sm label-success'>{{ strtoupper(__('forum.approved')) }}</span> @endif
                            @if ($r->denied == "1") <span
                                    class='label label-sm label-danger'>{{ strtoupper(__('forum.denied')) }}</span> @endif
                            @if ($r->solved == "1") <span
                                    class='label label-sm label-info'>{{ strtoupper(__('forum.solved')) }}</span> @endif
                            @if ($r->invalid == "1") <span
                                    class='label label-sm label-warning'>{{ strtoupper(__('forum.invalid')) }}</span> @endif
                            @if ($r->bug == "1") <span
                                    class='label label-sm label-danger'>{{ strtoupper(__('forum.bug')) }}</span> @endif
                            @if ($r->suggestion == "1") <span
                                    class='label label-sm label-primary'>{{ strtoupper(__('forum.suggestion')) }}</span>
                            @endif
                            @if ($r->implemented == "1") <span
                                    class='label label-sm label-success'>{{ strtoupper(__('forum.implemented')) }}</span>
                            @endif
                        </td>
                        <td class="f-display-topic-started"><a
                                    href="{{ route('users.show', ['username' => $r->first_post_user_username]) }}">{{ $r->first_post_user_username }}</a>
                        </td>
                        <td class="f-display-topic-stats">
                            {{ $r->num_post - 1 }} {{ __('forum.replies') }}
                            \ {{ $r->views }} {{ __('forum.views') }}
                        </td>
                        <td class="f-display-topic-last-post">
                            <a
                                    href="{{ route('users.show', ['username' => $r->last_post_user_username]) }}">{{ $r->last_post_user_username }}</a>,
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
        @include('forum.stats')
    </div>
@endsection
