@extends('layout.default')

@section('title')
    <title>{{ $user->username }} {{ __('user.topics') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('users.show', ['username' => $user->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user_topics', ['username' => $user->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title"
                  class="l-breadcrumb-item-link-title">{{ $user->username }} {{ __('user.topics') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        @if (!auth()->user()->isAllowed($user,'forum','show_topic'))
            <div class="container pl-0 text-center">
                <div class="jumbotron shadowed">
                    <div class="container">
                        <h1 class="mt-5 text-center">
                            <i class="{{ config('other.font-awesome') }} fa-times text-danger"></i>{{ __('user.private-profile') }}
                        </h1>
                        <div class="separator"></div>
                        <p class="text-center">{{ __('user.not-authorized') }}</p>
                    </div>
                </div>
            </div>
        @else
            <div class="block">
                @if (auth()->user()->id == $user->id || auth()->user()->group->is_modo)
                    @include('user.buttons.forum')
                @else
                    @include('user.buttons.public')
                @endif
                <div class="forum-categories">
                    <table class="table table-bordered table-hover">
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
                            @if ($r->viewable())
                                <tr>
                                    <td class="f-display-topic-icon"><span
                                                class="badge-extra text-bold">{{ $r->forum->name }}</span></td>
                                    <td class="f-display-topic-title">
                                        <strong><a href="{{ route('forum_topic', ['id' => $r->id]) }}">{{ $r->name }}</a></strong>
                                        @if ($r->state == "close") <span
                                                class='label label-sm label-default'>{{ strtoupper(__('forum.closed')) }}</span>
                                        @endif
                                        @if ($r->approved == "1") <span
                                                class='label label-sm label-success'>{{ strtoupper(__('forum.approved')) }}</span>
                                        @endif
                                        @if ($r->denied == "1") <span
                                                class='label label-sm label-danger'>{{ strtoupper(__('forum.denied')) }}</span>
                                        @endif
                                        @if ($r->solved == "1") <span
                                                class='label label-sm label-info'>{{ strtoupper(__('forum.solved')) }}</span> @endif
                                        @if ($r->invalid == "1") <span
                                                class='label label-sm label-warning'>{{ strtoupper(__('forum.invalid')) }}</span>
                                        @endif
                                        @if ($r->bug == "1") <span
                                                class='label label-sm label-danger'>{{ strtoupper(__('forum.bug')) }}</span> @endif
                                        @if ($r->suggestion == "1") <span
                                                class='label label-sm label-primary'>{{ strtoupper(__('forum.suggestion')) }}</span>
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
                                        <time datetime="{{ date('d-m-Y h:m', strtotime($r->updated_at)) }}">
                                            {{ date('M d Y', strtotime($r->updated_at)) }}
                                        </time>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-center col-md-12">
                    {{ $results->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
