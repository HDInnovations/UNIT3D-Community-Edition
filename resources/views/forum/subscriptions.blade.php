@extends('layout.default')

@section('title')
    <title>{{ __('common.subscriptions') }} - {{ __('forum.forums') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Forum {{ __('common.subscriptions') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('forums.index') }}" class="breadcrumb__link">
            {{ __('forum.forums') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.subscriptions') }}
    </li>
@endsection

@section('nav-tabs')
    @include('forum.buttons')
@endsection

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('forum.forums') }}</h2>
        @foreach ($results->whereIn('id', $forum_neos) as $subforum)
                <x-forum.subforum-listing :subforum="$subforum" />
        @endforeach
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('forum.topics') }}</h2>
        @foreach ($results as $result)
            @foreach($result->subscription_topics as $topic)
                <x-forum.topic-listing :topic="$topic" />
            @endforeach
        @endforeach
    </section>
    <div class="box container">
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
                    @if (in_array($r->id, $forum_neos, true))
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
                                @if (auth()->user()->subscriptions()->ofForum($r->id)->exists())
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
                    @endif
                    @if ($r->subscription_topics)
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
                                    @if (auth()->user()->subscriptions()->ofTopic($t->id)->exists())
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
                    @endif
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

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('forum.post-quick-search') }}</h2>
        <div class="panel__body">
            <form class="form form--horizontal" method="GET" action="{{ route('forum_search_form') }}">
                <input type="hidden" name="sorting" value="created_at">
                <input type="hidden" name="direction" value="desc">
                <p class="form__group">
                    <input
                        id="body"
                        class="form__text"
                        name="body"
                        placeholder=""
                        type="text"
                        value="{{ isset($params) && is_array($params) && array_key_exists('body', $params) ? $params['body'] : '' }}"
                    >
                    <label class="form__label form__label--floating" for="body">
                        {{ __('forum.forums-post-search') }}
                    </label>
                </p>
                <button type="submit" class="form__button form__button--filled">
                    {{ __('common.search') }}
                </button>
            </form>
        </div>
    </section>
    @include('forum.stats')
@endsection
