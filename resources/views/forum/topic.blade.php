@extends('layout.default')

@section('title')
    <title>{{ $topic->name }} - Forums - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('forums.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('forum.forums')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forums.show', ['id' => $forum->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $forum->name }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forum_topic', ['id' => $topic->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $topic->name }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="topic container-fluid">

        <h2>{{ $topic->name }}</h2>

        <div class="topic-info">
            @lang('forum.author') <a
                href="{{ route('users.show', ['username' => $topic->first_post_user_username]) }}">{{ $topic->first_post_user_username }}</a>,
            {{ date('M d Y H:m', strtotime($topic->created_at)) }}
            <span class='label label-primary'>{{ $topic->num_post - 1 }} {{ strtolower(trans('forum.replies')) }}</span>
            <span class='label label-info'>{{ $topic->views - 1 }} {{ strtolower(trans('forum.views')) }}</span>
            @if(auth()->user()->isSubscribed('topic', $topic->id))
                <a href="{{ route('unsubscribe_topic', ['topic' => $topic->id, 'route' => 'topic']) }}"
                    class="label label-sm label-danger">
                    <i class="{{ config('other.font-awesome') }} fa-bell-slash"></i> Unsubscribe</a>
            @else
                <a href="{{ route('subscribe_topic', ['topic' => $topic->id, 'route' => 'topic']) }}"
                    class="label label-sm label-success">
                    <i class="{{ config('other.font-awesome') }} fa-bell"></i> Subscribe</a>
            @endif
            <span style="float: right;"> {{ $posts->links() }}</span>
        </div>
        <br>
        <div class="topic-posts">
            @foreach ($posts as $k => $p)
                <div class="post" id="post-{{ $p->id }}">
                    <div class="block">
                        <div class="profil">
                            <div class="head">
                                <p>{{ date('M d Y', $p->created_at->getTimestamp()) }}
                                    ({{ $p->created_at->diffForHumans() }}) <a class="text-bold permalink"
                                        href="{{ route('forum_topic', ['id' => $p->topic->id]) }}?page={{ $p->getPageNumber() }}#post-{{ $p->id }}">@lang('forum.permalink')</a>
                                </p>
                            </div>
                            <aside class="col-md-2 post-info">
                                @if ($p->user->image != null)
                                    <img src="{{ url('files/img/' . $p->user->image) }}" alt="{{ $p->user->username }}"
                                        class="img-thumbnail post-info-image">
                                @else
                                    <img src="{{ url('img/profile.png') }}" alt="{{ $p->user->username }}"
                                        class="img-thumbnail post-info-image">
                                @endauth
                                <p>
                                    <span class="badge-user text-bold">
                                        <a href="{{ route('users.show', ['username' => $p->user->username]) }}"
                                            class="post-info-username"
                                            style="color:{{ $p->user->group->color }}; display:inline;">{{ $p->user->username }}</a>
                                        @if ($p->user->isOnline())
                                            <i class="{{ config('other.font-awesome') }} fa-circle text-green" data-toggle="tooltip"
                                                data-original-title="Online"></i>
                                        @else
                                            <i class="{{ config('other.font-awesome') }} fa-circle text-red" data-toggle="tooltip"
                                                data-original-title="Offline"></i>
                                        @endauth
                                        <a
                                            href="{{ route('create', ['receiver_id' => $p->user->id, 'username' => $p->user->username]) }}">
                                            <i class="{{ config('other.font-awesome') }} fa-envelope text-info"></i>
                                        </a>
                                    </span>
                                </p>

                                <p><span class="badge-user text-bold"
                                        style="color:{{ $p->user->group->color }}; background-image:{{ $p->user->group->effect }};"><i
                                            class="{{ $p->user->group->icon }}" data-toggle="tooltip"
                                            data-original-title="{{ $p->user->group->name }}"></i>
                                        {{ $p->user->group->name }}</span>
                                </p>
                                <p class="pre">{{ $p->user->title }}</p>
                                <p>@lang('user.member-since')
                                    : {{ date('M d Y', $p->user->created_at->getTimestamp()) }}</p>

                                <p>
                                    @if($p->user->topics && $p->user->topics->count() > 0)
                                        <span class="badge-user text-bold">
                                            <a href="{{ route('user_topics', ['username' => $p->user->username]) }}"
                                                class="post-info-username">{{ $p->user->topics->count() }} @lang('forum.topics')</a>
                                        </span>
                                    @endauth
                                    @if($p->user->posts && $p->user->posts->count() > 0)
                                        <span class="badge-user text-bold">
                                            <a href="{{ route('user_posts', ['username' => $p->user->username]) }}"
                                                class="post-info-username">{{ $p->user->posts->count() }} @lang('forum.posts')</a>
                                        </span>
                                    @endauth
                                </p>


                                <span class="inline">
                                    @if ($topic->state == 'open')
                                        <button id="quote" class="btn btn-xs btn-xxs btn-info">@lang('forum.quote')</button>
                                    @endauth
                                    @if (auth()->user()->group->is_modo || $p->user_id == auth()->user()->id)
                                        <a href="{{ route('forum_post_edit_form', ['id' => $topic->id, 'postId' => $p->id]) }}"><button
                                                class="btn btn-xs btn-xxs btn-warning">@lang('common.edit')</button></a>
                                    @endauth
                                    @if (auth()->user()->group->is_modo || $p->user_id == auth()->user()->id && $topic->state ==
                                        'open')
                                        <a href="{{ route('forum_post_delete', ['id' => $topic->id, 'postId' => $p->id]) }}"><button
                                                class="btn btn-xs btn-xxs btn-danger">@lang('common.delete')</button></a>
                                    @endauth
                                </span>
                            </aside>

                            <article class="col-md-9 post-content">
                                @emojione($p->getContentHtml())

                            </article>

                            <div class="post-signature col-md-12 some-margin post-tips">
                                <div id="forumTip{{ $p->id }}" class="text-center">
                                    @if($p->tips && $p->tips->sum('cost') > 0)
                                        <div class="some-padding">@lang('forum.tip-post-total') {{ $p->tips->sum('cost') }}
                                            BON</div>
                                    @endauth
                                    <div class="some-padding" id="forumTip" route="{{ route('tip_poster') }}"
                                            leaveTip="@lang('torrent.leave-tip')" quickTip="@lang('torrent.quick-tip')">
                                        <a class="forumTip" href="#/" post="{{ $p->id }}"
                                            user="{{ $p->user->id }}">@lang('forum.tip-this-post')</a></div>
                                </div>
                            </div>

                            @php $likes = DB::table('likes')->where('post_id', '=', $p->id)->where('like', '=', 1)->count();
                            @endphp
                            @php $dislikes = DB::table('likes')->where('post_id', '=', $p->id)->where('dislike', '=',
                            1)->count(); @endphp
                            <div class="likes">
                                <span class="badge-extra">
                                    @if (auth()->user()->likes()->where('post_id', '=', $p->id)->where('like', '=', 1)->first())
                                        <a href="{{ route('like', ['postId' => $p->id]) }}" class="text-green" data-toggle="tooltip"
                                            style="margin-right: 16px;" data-original-title="@lang('forum.like-post')"><i
                                                class="icon-like {{ config('other.font-awesome') }} fa-thumbs-up fa-2x fa-beat"></i>
                                            <span class="count" style="font-size: 20px;">{{ $likes }}</span></a>
                                    @else
                                        <a href="{{ route('like', ['postId' => $p->id]) }}" class="text-green" data-toggle="tooltip"
                                            style="margin-right: 16px;" data-original-title="@lang('forum.like-post')"><i
                                                class="icon-like {{ config('other.font-awesome') }} fa-thumbs-up fa-2x"></i>
                                            <span class="count" style="font-size: 20px;">{{ $likes }}</span></a>
                                    @endauth
                                    @if (auth()->user()->likes()->where('post_id', '=', $p->id)->where('dislike', '=',
                                        1)->first())
                                        <a href="{{ route('dislike', ['postId' => $p->id]) }}" class="text-red"
                                            data-toggle="tooltip" data-original-title="@lang('forum.dislike-post')"><i
                                                class="icon-dislike {{ config('other.font-awesome') }} fa-thumbs-down fa-2x fa-beat"></i>
                                            <span class="count" style="font-size: 20px;">{{ $dislikes }}</span></a>
                                    @else
                                        <a href="{{ route('dislike', ['postId' => $p->id]) }}" class="text-red"
                                            data-toggle="tooltip" data-original-title="@lang('forum.dislike-post')"><i
                                                class="icon-dislike {{ config('other.font-awesome') }} fa-thumbs-down fa-2x"></i>
                                            <span class="count" style="font-size: 20px;">{{ $dislikes }}</span></a>
                                    @endauth
                                </span>
                            </div>



                            <div class="post-signature col-md-12">
                                @if ($p->user->signature != null)
                                    {!! $p->user->getSignature() !!}
                                @endauth
                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <br>
                @endforeach
                <div class="text-center">{{ $posts->links() }}</div>
            </div>
            <br>
            <br>
            <div class="block">
                <div class="topic-new-post">
                    @if ($topic->state == "close" && auth()->user()->group->is_modo)
                        <form role="form" method="POST" action="{{ route('forum_reply', ['id' => $topic->id]) }}">
                            @csrf
                            <div class="text-danger">This topic is closed, but you can still reply due to you
                                being {{ auth()->user()->group->name }}.
                            </div>
                            <div class="from-group">
                                <label for="topic-response"></label><textarea name="content" id="topic-response" cols="30"
                                    rows="10"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">@lang('common.submit')</button>
                        </form>
                    @elseif ($topic->state == "close")
                        <div class="col-md-12 alert alert-danger">@lang('forum.topic-closed')</div>
                    @else
                        <form role="form" method="POST" action="{{ route('forum_reply', ['id' => $topic->id]) }}">
                            @csrf
                            <div class="from-group">
                                <label for="topic-response"></label><textarea name="content" id="topic-response" cols="30"
                                    rows="10"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">@lang('common.submit')</button>
                        </form>
                    @endif

                    <div class="text-center">
                        @if (auth()->user()->group->is_modo || $topic->first_post_user_id == auth()->user()->id)
                            <h3>@lang('forum.moderation')</h3>
                            @if ($topic->state == "close")
                                <a href="{{ route('forum_open', ['id' => $topic->id]) }}"
                                    class="btn btn-success">@lang('forum.open-topic')</a>
                            @else
                                <a href="{{ route('forum_close', ['id' => $topic->id]) }}"
                                    class="btn btn-info">@lang('forum.close-topic')</a>
                            @endif
                        @endif
                        @if (auth()->user()->group->is_modo || $topic->first_post_user_id == auth()->user()->id)
                            <a href="{{ route('forum_edit_topic_form', ['id' => $topic->id]) }}"
                                class="btn btn-warning">@lang('forum.edit-topic')</a>
                            <a href="{{ route('forum_delete_topic', ['id' => $topic->id]) }}"
                                class="btn btn-danger">@lang('forum.delete-topic')</a>
                        @endif
                        @if (auth()->user()->group->is_modo)
                            @if ($topic->pinned == 0)
                                <a href="{{ route('forum_pin_topic', ['id' => $topic->id]) }}"
                                    class="btn btn-primary">@lang('forum.pin') {{ strtolower(trans('forum.topic')) }}</a>
                            @else
                                <a href="{{ route('forum_unpin_topic', ['id' => $topic->id]) }}"
                                    class="btn btn-default">@lang('forum.unpin') {{ strtolower(trans('forum.topic')) }}</a>
                            @endif
                        @endif

                        <br>

                        @if (auth()->user()->group->is_modo)
                            <h3>@lang('forum.label-system')</h3>
                            @if ($topic->approved == "0")
                                <a href="{{ route('topics.approve', ['id' => $topic->id]) }}"
                                    class='label label-sm label-success'>@lang('common.add')
                                    {{ strtoupper(trans('forum.approved')) }}</a>
                            @else
                                <a href="{{ route('topics.approve', ['id' => $topic->id]) }}"
                                    class='label label-sm label-success'>@lang('common.remove')
                                    {{ strtoupper(trans('forum.approved')) }}</a>
                            @endif
                            @if ($topic->denied == "0")
                                <a href="{{ route('topics.deny', ['id' => $topic->id]) }}"
                                    class='label label-sm label-danger'>@lang('common.add')
                                    {{ strtoupper(trans('forum.denied')) }}</a>
                            @else
                                <a href="{{ route('topics.deny', ['id' => $topic->id]) }}"
                                    class='label label-sm label-danger'>@lang('common.remove')
                                    {{ strtoupper(trans('forum.denied')) }}</a>
                            @endif
                            @if ($topic->solved == "0")
                                <a href="{{ route('topics.solve', ['id' => $topic->id]) }}"
                                    class='label label-sm label-info'>@lang('common.add')
                                    {{ strtoupper(trans('forum.solved')) }}</a>
                            @else
                                <a href="{{ route('topics.solve', ['id' => $topic->id]) }}"
                                    class='label label-sm label-info'>@lang('common.remove')
                                    {{ strtoupper(trans('forum.solved')) }}</a>
                            @endif
                            @if ($topic->invalid == "0")
                                <a href="{{ route('topics.invalid', ['id' => $topic->id]) }}"
                                    class='label label-sm label-warning'>@lang('common.add')
                                    {{ strtoupper(trans('forum.invalid')) }}</a>
                            @else
                                <a href="{{ route('topics.invalid', ['id' => $topic->id]) }}"
                                    class='label label-sm label-warning'>@lang('common.remove')
                                    {{ strtoupper(trans('forum.invalid')) }}</a>
                            @endif
                            @if ($topic->bug == "0")
                                <a href="{{ route('topics.bug', ['id' => $topic->id]) }}"
                                    class='label label-sm label-danger'>@lang('common.add') {{ strtoupper(trans('forum.bug')) }}</a>
                            @else
                                <a href="{{ route('topics.bug', ['id' => $topic->id]) }}"
                                    class='label label-sm label-danger'>@lang('common.remove')
                                    {{ strtoupper(trans('forum.bug')) }}</a>
                            @endif
                            @if ($topic->suggestion == "0")
                                <a href="{{ route('topics.suggest', ['id' => $topic->id]) }}"
                                    class='label label-sm label-primary'>@lang('common.add')
                                    {{ strtoupper(trans('forum.suggestion')) }}</a>
                            @else
                                <a href="{{ route('topics.suggest', ['id' => $topic->id]) }}"
                                    class='label label-sm label-primary'>@lang('common.remove')
                                    {{ strtoupper(trans('forum.suggestion')) }}</a>
                            @endif
                            @if ($topic->implemented == "0")
                                <a href="{{ route('topics.implement', ['id' => $topic->id]) }}"
                                    class='label label-sm label-success'>@lang('common.add')
                                    {{ strtoupper(trans('forum.implemented')) }}</a>
                            @else
                                <a href="{{ route('topics.implement', ['id' => $topic->id]) }}"
                                    class='label label-sm label-success'>@lang('common.remove')
                                    {{ strtoupper(trans('forum.implemented')) }}</a>
                            @endif
                        @endif
                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
        $(document).ready(function() {
            let wbbOpt = {
                allButtons: {
                    quote: {
                        transform: {
                            '<div class="wbbquote"><cite><b>{AUTHOR}</b> wrote:</cite> <br /> <br />{SELTEXT}</div>': '[quote={AUTHOR}]{SELTEXT}[/quote]'
                        }
                    }
                }
            };

            let editor = $("#topic-response").wysibb(wbbOpt);

            // Initialize emojis
            emoji.textcomplete();

            $('.profil').on('click', 'button#quote', function() {
                let author = $(this).closest('.post-info').find('.badge-user').first().text();
                let text = $(this).closest('.profil').find('.post-content').first().text().replace('@here',
                    '');

                editor.execCommand('quote', {
                    author: '@' + author + ' ',
                    seltext: text
                });
            });
        });

    </script>
@endsection
