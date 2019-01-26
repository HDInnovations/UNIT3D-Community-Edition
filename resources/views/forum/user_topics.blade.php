@extends('layout.default')

@section('title')
    <title>{{ $user->username }} - @lang('common.topics') - @lang('forum.forums') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ $user->username }} @lang('common.topics')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('forum_index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('forum.forums')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forum_user_topics', [ 'slug' => $user->slug, 'id' => $user->id ]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }} @lang('common.topics')</span>
        </a>
    </li>
@endsection

@section('content')
    @if ( $user->private_profile == 1 && auth()->user()->id != $user->id && !auth()->user()->group->is_modo )
        <div class="container">
            <div class="jumbotron shadowed">
                <div class="container">
                    <h1 class="mt-5 text-center">
                        <i class="{{ config('other.font-awesome') }} fa-times text-danger"></i>@lang('user.private-forum-profile')
                    </h1>
                    <div class="separator"></div>
                    <p class="text-center">@lang('user.not-authorized')</p>
                </div>
            </div>
        </div>
    @else

        <div class="box container">
            @include('forum.buttons')

        <div class="forum-categories">
            <table class="table table-bordered table-hover">
                <thead class="no-space">
                <tr class="no-space">
                    <td colspan="5" class="no-space">
                        <div class="header gradient teal some-padding">
                            <div class="inner_content">
                                <h1 class="no-space">{{ $user->username }} Topics</h1>
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
                        </td>
                        <td class="f-display-topic-started"><a
                                    href="{{ route('profile', ['username' => $r->first_post_user_username, 'id' => $r->first_post_user_id]) }}">{{ $r->first_post_user_username }}</a>
                        </td>
                        <td class="f-display-topic-stats">
                            {{ $r->num_post - 1 }} @lang('forum.replies')
                            \ {{ $r->views }} @lang('forum.views')
                        </td>
                        <td class="f-display-topic-last-post">
                            <a href="{{ route('profile', ['username' => $r->last_post_user_username, 'id' => $r->last_post_user_id]) }}">{{ $r->last_post_user_username }}</a>,
                            <time datetime="{{ date('d-m-Y h:m', strtotime($r->updated_at)) }}">
                                {{ date('M d Y', strtotime($r->updated_at)) }}
                            </time>
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
    @endif
@endsection
