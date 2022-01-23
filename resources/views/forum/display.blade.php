@extends('layout.default')

@section('title')
    <title>{{ $forum->name }} - {{ __('forum.forums') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('forum.display-forum') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('forums.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('forum.forums') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forums.show', ['id' => $forum->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $forum->name }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="button-holder">
            @include('forum.buttons')
            <div class="button-right">
                <form role="form" method="GET" action="{{ route('forum_search_form') }}" class="form-inline">
                    <input type="hidden" name="sorting" value="created_at">
                    <input type="hidden" name="direction" value="desc">
                    <input type="hidden" name="category" value="{{ $forum->id }}">
                    <label for="name"></label>
                    <input type="text" name="name" id="name"
                           value="{{ isset($params) && is_array($params) && array_key_exists('name', $params) ? $params['name'] : '' }}"
                           placeholder="{{ __('forum.category-quick-search') }}" class="form-control">
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
                                <h1 class="no-space">{{ $forum->name }}</h1>
                                <div class="text-center some-padding">{{ $forum->description }}</div>
                            </div>
                        </div>
                    </td>
                </tr>
                </thead>
                <thead>
                <tr>
                    <td colspan="5">
                        <div class="button-holder">
                            <div class="button-left"></div>
                            <div class="button-right">
                                @if ($category->getPermission()->start_topic == true)
                                    <a href="{{ route('forum_new_topic_form', ['id' => $forum->id]) }}"
                                       class="btn btn-sm btn-primary">{{ __('forum.create-new-topic') }}</a>
                                @endif
                                @if ($category->getPermission()->show_forum == true)
                                    @if (auth()->user()->isSubscribed('forum',$forum->id))
                                        <form action="{{ route('unsubscribe_forum', ['forum' => $forum->id, 'route' => 'forum']) }}"
                                              method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="{{ config('other.font-awesome') }} fa-bell-slash"></i> {{ __('forum.unsubscribe') }}
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('subscribe_forum', ['forum' => $forum->id, 'route' => 'forum']) }}"
                                              method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="{{ config('other.font-awesome') }} fa-bell"></i> {{ __('forum.subscribe') }}
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                </thead>
                <thead>
                <tr>
                    <th></th>
                    <th>{{ __('forum.topic') }}</th>
                    <th>{{ __('forum.author') }}</th>
                    <th>{{ __('forum.stats') }}</th>
                    <th>{{ __('forum.last-post-info') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($topics as $t)
                    <tr>
                        @if ($t->pinned == 0)
                            <td class="f-display-topic-icon"><img src="{{ url('img/f_icon_read.png') }}" alt="read">
                            </td>
                        @else
                            <td class="f-display-topic-icon"><span class="text-green"><i
                                            class="{{ config('other.font-awesome') }} fa-thumbtack fa-2x"></i></span>
                            </td>
                        @endif
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
                            @if ($t->implemented == "1") <span
                                    class='label label-sm label-success'>{{ strtoupper(__('forum.implemented')) }}</span>
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
                                    href="{{ route('users.show', ['username' => $t->last_post_user_username]) }}">{{ $t->last_post_user_username }}</a>
                            on
                            <time datetime="{{ optional($t->last_reply_at)->format('M d Y') ?? 'UNKNOWN' }}">
                                {{ optional($t->last_reply_at)->format('M d Y') ?? 'UNKNOWN' }}
                            </time>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-center col-md-12">
            {{ $topics->links() }}
        </div>
        @include('forum.stats')
    </div>
@endsection
