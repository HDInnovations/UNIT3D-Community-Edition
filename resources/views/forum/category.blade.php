@extends('layout.default')

@section('title')
    <title>{{ $forum->name }} - @lang('forum.forums') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('forum.display-forum')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('forum_index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('forum.forums')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forum_category', ['slug' => $forum->slug, 'id' => $forum->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $forum->name }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        @include('forum.buttons')
        <form role="form" method="GET" action="{{ route('forum_search_form') }}">
            <input type="hidden" name="sorting" value="updated_at">
            <input type="hidden" name="direction" value="desc">
            <input type="hidden" name="category" value="{{ $forum->id }}">
            <input type="text" name="name" id="name" value="{{ (isset($params) && is_array($params) && array_key_exists('name',$params) ? $params['name'] : '') }}" placeholder="@lang('forum.category-quick-search')"
                   class="form-control">
        </form>
        <div class="forum-categories">
            <table class="table table-bordered table-hover">
                <thead class="no-space">
                <tr class="no-space">
                    <td colspan="6" class="no-space">
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
                    <th></th>
                    <th>@lang('forum.forum')</th>
                    <th>@lang('forum.topic')</th>
                    <th>@lang('forum.author')</th>
                    <th>@lang('forum.stats')</th>
                    <th>@lang('forum.last-post-info')</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($topics as $t)
                    <tr>
                        @if ($t->pinned == 0)
                            <td class="f-display-topic-icon"><img src="{{ url('img/f_icon_read.png') }}"></td>
                        @else
                            <td class="f-display-topic-icon"><span class="text-green"><i
                                            class="{{ config('other.font-awesome') }} fa-thumbtack fa-2x"></i></span></td>
                        @endif
                            <td class="f-display-topic-icon"><span
                                        class="badge-extra text-bold">{{ $t->forum->name }}</span></td>
                        <td class="f-display-topic-title">
                            <strong><a href="{{ route('forum_topic', ['slug' => $t->slug, 'id' => $t->id]) }}">{{ $t->name }}</a></strong>
                            @if ($t->state == "close") <span
                                    class='label label-sm label-default'>{{ strtoupper(trans('forum.closed')) }}</span> @endif
                            @if ($t->approved == "1") <span
                                    class='label label-sm label-success'>{{ strtoupper(trans('forum.approved')) }}</span> @endif
                            @if ($t->denied == "1") <span
                                    class='label label-sm label-danger'>{{ strtoupper(trans('forum.denied')) }}</span> @endif
                            @if ($t->solved == "1") <span
                                    class='label label-sm label-info'>{{ strtoupper(trans('forum.solved')) }}</span> @endif
                            @if ($t->invalid == "1") <span
                                    class='label label-sm label-warning'>{{ strtoupper(trans('forum.invalid')) }}</span> @endif
                            @if ($t->bug == "1") <span
                                    class='label label-sm label-danger'>{{ strtoupper(trans('forum.bug')) }}</span> @endif
                            @if ($t->suggestion == "1") <span
                                    class='label label-sm label-primary'>{{ strtoupper(trans('forum.suggestion')) }}</span> @endif
                            @if ($t->implemented == "1") <span
                                    class='label label-sm label-success'>{{ strtoupper(trans('forum.implemented')) }}</span> @endif
                        </td>
                        <td class="f-display-topic-started"><a
                                    href="{{ route('profile', ['username' => Str::slug($t->first_post_user_username), 'id' => $t->first_post_user_id]) }}">{{ $t->first_post_user_username }}</a>
                        </td>
                        <td class="f-display-topic-stats">
                            {{ $t->num_post - 1 }} @lang('forum.replies')
                            \ {{ $t->views }} @lang('forum.views')
                        </td>
                        @php $last_post = DB::table('posts')->where('topic_id', '=', $t->id)->orderBy('id', 'desc')->first(); @endphp
                        <td class="f-display-topic-last-post">
                            <a href="{{ route('profile', ['username' => Str::slug($t->last_post_user_username), 'id' => $t->last_post_user_id]) }}">{{ $t->last_post_user_username }}</a>
                            on
                            <time datetime="{{ date('M d Y', strtotime($last_post->created_at ?? "UNKNOWN")) }}">
                                {{ date('M d Y', strtotime($last_post->created_at ?? "UNKNOWN")) }}
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
    </div>
@endsection
