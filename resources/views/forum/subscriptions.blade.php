@extends('layout.default')

@section('title')
    <title>{{ __('common.subscriptions') }} - {{ __('forum.forums') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Forum {{ __('common.subscriptions') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('forums.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('forum.forums') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forum_subscriptions') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('common.subscriptions') }}</span>
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
                    <input type="hidden" name="subscribed" value="1">
                    <label for="name"></label><input type="text" name="name" id="name"
                                                     value="{{ isset($params) && is_array($params) && array_key_exists('name', $params) ? $params['name'] : '' }}"
                                                     placeholder="{{ __('forum.subscription-quick-search') }}"
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
                    <td colspan="6" class="no-space">
                        <div class="header gradient teal some-padding">
                            <div class="inner_content">
                                <h1 class="no-space">{{ __('common.subscriptions') }}</h1>
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
                    <th>{{ __('forum.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($results as $r)
                    @php
                        if(in_array($r->id, $forum_neos, true)) {
                    @endphp
                    <tr>
                        <td class="f-display-topic-icon"><a href="{{ route('forums.show', ['id' => $r->id]) }}"><span
                                        class="badge-extra text-bold">{{ $r->name }}</span></a></td>
                        <td class="f-display-topic-title">
                            --
                        </td>
                        <td class="f-display-topic-started">
                            --
                        </td>
                        <td class="f-display-topic-stats">
                            --
                        </td>
                        <td class="f-display-topic-last-post">
                            --
                        </td>
                        <td class="f-display-topic-stats">
                            @if (auth()->user()->isSubscribed('forum', $r->id))
                                <form action="{{ route('unsubscribe_forum', ['forum' => $r->id, 'route' => 'subscriptions']) }}"
                                      method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-danger">
                                        <i class="{{ config('other.font-awesome') }} fa-bell-slash"></i> {{ __('forum.unsubscribe') }}
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('subscribe_forum', ['forum' => $r->id, 'route' => 'subscriptions']) }}"
                                      method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-success">
                                        <i class="{{ config('other.font-awesome') }} fa-bell"></i> {{ __('forum.subscribe') }}
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @php
                        }
                        if($r->subscription_topics) {
                    @endphp
                    @foreach($r->subscription_topics as $t)
                        <tr>
                            <td class="f-display-topic-icon"><span
                                        class="badge-extra text-bold">{{ $t->forum->name }}</span>
                            </td>
                            <td class="f-display-topic-title">
                                <strong><a href="{{ route('forum_topic', ['id' => $t->id]) }}">{{ $t->name }}</a></strong>
                                @if ($t->state == "close") <span
                                        class='label label-sm label-default'>{{ strtoupper(__('forum.closed')) }}</span> @endif
                                @if ($t->approved == "1") <span
                                        class='label label-sm label-success'>{{ strtoupper(__('forum.approved')) }}</span> @endif
                                @if ($t->denied == "1") <span
                                        class='label label-sm label-danger'>{{ strtoupper(__('forum.denied')) }}</span> @endif
                                @if ($t->solved == "1") <span
                                        class='label label-sm label-info'>{{ strtoupper(__('forum.solved')) }}</span> @endif
                                @if ($t->invalid == "1") <span
                                        class='label label-sm label-warning'>{{ strtoupper(__('forum.invalid')) }}</span> @endif
                                @if ($t->bug == "1") <span
                                        class='label label-sm label-danger'>{{ strtoupper(__('forum.bug')) }}</span> @endif
                                @if ($t->suggestion == "1") <span
                                        class='label label-sm label-primary'>{{ strtoupper(__('forum.suggestion')) }}</span>
                                @endif
                            </td>
                            <td class="f-display-topic-started"><a
                                        href="{{ route('users.show', ['username' => $t->first_post_user_username]) }}">{{ $t->first_post_user_username }}</a>
                            </td>
                            <td class="f-display-topic-stats">
                                {{ $t->num_post - 1 }} {{ __('forum.replies') }}
                                \ {{ $t->views }} {{ __('forum.views') }}
                            </td>
                            <td class="f-display-topic-last-post">
                                <a
                                        href="{{ route('users.show', ['username' => $t->last_post_user_username]) }}">{{ $t->last_post_user_username }}</a>,
                                @if($t->last_reply_at && $t->last_reply_at != null)
                                    <time datetime="{{ date('d-m-Y h:m', strtotime($t->last_reply_at)) }}">
                                        {{ date('M d Y', strtotime($t->last_reply_at)) }}
                                    </time>
                                @else
                                    <time datetime="N/A">
                                        N/A
                                    </time>
                                @endif
                            </td>
                            <td class="f-display-topic-stats">
                                @if (auth()->user()->isSubscribed('topic',$t->id))
                                    <a href="{{ route('unsubscribe_topic', ['topic' => $t->id, 'route' => 'subscriptions']) }}"
                                       class="label label-sm label-danger">
                                        <i class="{{ config('other.font-awesome') }} fa-bell-slash"></i> {{ __('forum.unsubscribe') }}
                                    </a>
                                @else
                                    <a href="{{ route('subscribe_topic', ['topic' => $t->id, 'route' => 'subscriptions']) }}"
                                       class="label label-sm label-success">
                                        <i class="{{ config('other.font-awesome') }} fa-bell"></i> {{ __('forum.subscribe') }}
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @php
                        }
                    @endphp
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
