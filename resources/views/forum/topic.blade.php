@extends('layout.default')

@section('title')
    <title>{{ $topic->name }} - Forums - {{ config('other.title') }}</title>
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
    <li>
        <a href="{{ route('forum_topic', ['id' => $topic->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $topic->name }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="topic container">

        <h2>{{ $topic->name }}</h2>

        <div class="topic-info">
            {{ __('forum.author') }}
            <a href="{{ route('users.show', ['username' => $topic->first_post_user_username]) }}">
                {{ $topic->first_post_user_username }}
            </a>,
            {{ date('M d Y H:m', strtotime($topic->created_at)) }}
            <span class='label label-primary'>{{ $topic->num_post - 1 }} {{ strtolower(__('forum.replies')) }}</span>
            <span class='label label-info'>{{ $topic->views - 1 }} {{ strtolower(__('forum.views')) }}</span>
            @if(auth()->user()->isSubscribed('topic', $topic->id))
                <form action="{{ route('unsubscribe_topic', ['topic' => $topic->id, 'route' => 'topic']) }}"
                      method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-xs btn-danger">
                        <i class="{{ config('other.font-awesome') }} fa-bell-slash"></i> {{ __('forum.unsubscribe') }}
                    </button>
                </form>
            @else
                <form action="{{ route('subscribe_topic', ['topic' => $topic->id, 'route' => 'topic']) }}" method="POST"
                      style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-xs btn-success">
                        <i class="{{ config('other.font-awesome') }} fa-bell"></i> {{ __('forum.subscribe') }}
                    </button>
                </form>
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
                                                                               href="{{ route('forum_topic', ['id' => $p->topic->id]) }}?page={{ $p->getPageNumber() }}#post-{{ $p->id }}">{{ __('forum.permalink') }}</a>
                                </p>
                            </div>
                            <aside class="col-md-2 post-info">
                                @if ($p->user->image != null)
                                    <img src="{{ url('files/img/' . $p->user->image) }}" alt="{{ $p->user->username }}"
                                         class="img-thumbnail post-info-image">
                                @else
                                    <img src="{{ url('img/profile.png') }}" alt="{{ $p->user->username }}"
                                         class="img-thumbnail post-info-image">
                                @endif
                                <p>
                                    <span class="badge-user text-bold">
                                        <a href="{{ route('users.show', ['username' => $p->user->username]) }}"
                                           class="post-info-username"
                                           style="color:{{ $p->user->group->color }}; display:inline;">{{ $p->user->username }}</a>
                                        @if ($p->user->isOnline())
                                            <i class="{{ config('other.font-awesome') }} fa-circle text-green"
                                               data-toggle="tooltip"
                                               data-original-title="Online"></i>
                                        @else
                                            <i class="{{ config('other.font-awesome') }} fa-circle text-red"
                                               data-toggle="tooltip"
                                               data-original-title="Offline"></i>
                                        @endif
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
                                @if (!empty($p->user->title))
                                    <p><span class="badge-user title">{{ $p->user->title }}</span></p>
                                @endif
                                <p>
                                    <span class="badge-user text-bold">Joined: {{ date('d M Y', $p->user->created_at->getTimestamp()) }}</span>
                                </p>

                                <p>
                                    @if($p->user->topics && $p->user->topics->count() > 0)
                                        <span class="badge-user text-bold">
                                            <a href="{{ route('user_topics', ['username' => $p->user->username]) }}"
                                               class="post-info-username">{{ $p->user->topics->count() }} {{ __('forum.topics') }}</a>
                                        </span>
                                    @endif
                                    @if($p->user->posts && $p->user->posts->count() > 0)
                                        <span class="badge-user text-bold">
                                            <a href="{{ route('user_posts', ['username' => $p->user->username]) }}"
                                               class="post-info-username">{{ $p->user->posts->count() }} {{ __('forum.posts') }}</a>
                                        </span>
                                    @endif
                                </p>


                                <span class="inline">
                                    @if ($topic->state == 'open')
                                        <button id="quote"
                                                class="btn btn-xs btn-xxs btn-info">{{ __('forum.quote') }}</button>
                                    @endif
                                    @if (auth()->user()->group->is_modo || $p->user_id === auth()->user()->id)
                                        <a href="{{ route('forum_post_edit_form', ['id' => $topic->id, 'postId' => $p->id]) }}"><button
                                                    class="btn btn-xs btn-xxs btn-warning">{{ __('common.edit') }}</button></a>
                                    @endif
                                    @if (auth()->user()->group->is_modo || ($p->user_id === auth()->user()->id && $topic->state === 'open'))
                                        <form role="form" method="POST"
                                              action="{{ route('forum_post_delete', ['id' => $topic->id, 'postId' => $p->id]) }}"
                                              style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-xxs btn-danger">
                                                {{ __('common.delete') }}
                                            </button>
                                        </form>
                                    @endif
                                </span>
                            </aside>

                            <article class="col-md-10 post-content" data-bbcode="{{ $p->content }}">
                                @joypixels($p->getContentHtml())
                            </article>

                            <div class="post-signature col-md-12 mt-10">
                                <div id="forumTip{{ $p->id }}" class="text-center">
                                    @if($p->tips && $p->tips->sum('cost') > 0)
                                        <div>{{ __('forum.tip-post-total') }} {{ $p->tips->sum('cost') }}
                                            BON
                                        </div>
                                    @endif
                                    <div id="forumTip" route="{{ route('tip_poster') }}"
                                         leaveTip="{{ __('torrent.leave-tip') }}" quickTip="{{ __('torrent.quick-tip') }}">
                                        <a class="forumTip" href="#/" post="{{ $p->id }}"
                                           user="{{ $p->user->id }}">{{ __('forum.tip-this-post') }}</a></div>
                                </div>
                            </div>

                            <div class="likes">
                                <span class="badge-extra">
                                    @livewire('like-button', ['post' => $p->id])
                                    @livewire('dislike-button', ['post' => $p->id])
                                </span>
                            </div>

                            @if ($p->user->signature != null)
                                <div class="post-signature col-md-12">
                                    {!! $p->user->getSignature() !!}
                                </div>
                            @endif

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <br>
            @endforeach
            <div class="text-center">{{ $posts->links() }}</div>
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
                                <label for="topic-response"></label>
                                <textarea name="content" id="topic-response" cols="30" rows="10"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('common.submit') }}</button>
                        </form>
                    @elseif ($topic->state == "close")
                        <div class="col-md-12 alert alert-danger">{{ __('forum.topic-closed') }}</div>
                    @else
                        <form role="form" method="POST" action="{{ route('forum_reply', ['id' => $topic->id]) }}">
                            @csrf
                            <div class="from-group">
                                <label for="topic-response"></label>
                                <textarea name="content" id="topic-response" cols="30" rows="10"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('common.submit') }}</button>
                        </form>
                    @endif

                    <div class="text-center">
                        @if (auth()->user()->group->is_modo || $topic->first_post_user_id == auth()->user()->id)
                            <h3>{{ __('forum.moderation') }}</h3>
                            @if ($topic->state === 'close')
                                <form action="{{ route('forum_open', ['id' => $topic->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        {{ __('forum.open-topic') }}
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('forum_close', ['id' => $topic->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        {{ __('forum.close-topic') }}
                                    </button>
                                </form>
                            @endif
                        @endif
                        @if (auth()->user()->group->is_modo || $topic->first_post_user_id == auth()->user()->id)
                            <a href="{{ route('forum_edit_topic_form', ['id' => $topic->id]) }}"
                               class="btn btn-warning">
                                {{ __('forum.edit-topic') }}
                            </a>
                            <form action="{{ route('forum_delete_topic', ['id' => $topic->id]) }}" method="POST"
                                  style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    {{ __('forum.delete-topic') }}
                                </button>
                            </form>
                        @endif
                        @if (auth()->user()->group->is_modo)
                            @if ($topic->pinned === 0)
                                <form action="{{ route('forum_pin_topic', ['id' => $topic->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('forum.pin') }} {{ __('forum.topic') }}
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('forum_unpin_topic', ['id' => $topic->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-default">
                                        {{ __('forum.unpin') }} {{ __('forum.topic') }}
                                    </button>
                                </form>
                            @endif
                        @endif

                        <br>

                        @if (auth()->user()->group->is_modo)
                            <h3>{{ __('forum.label-system') }}</h3>
                            @if ($topic->approved === 0)
                                <form action="{{ route('topics.approve', ['id' => $topic->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-success">
                                        {{ __('common.add') }} {{ strtoupper(__('forum.approved')) }}
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('topics.approve', ['id' => $topic->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-danger">
                                        {{ __('common.remove') }} {{ strtoupper(__('forum.approved')) }}
                                    </button>
                                </form>
                            @endif
                            @if ($topic->denied === 0)
                                <form action="{{ route('topics.deny', ['id' => $topic->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-success">
                                        {{ __('common.add') }} {{ strtoupper(__('forum.denied')) }}
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('topics.deny', ['id' => $topic->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-danger">
                                        {{ __('common.remove') }} {{ strtoupper(__('forum.denied')) }}
                                    </button>
                                </form>
                            @endif
                            @if ($topic->solved === 0)
                                <form action="{{ route('topics.solve', ['id' => $topic->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-success">
                                        {{ __('common.add') }} {{ strtoupper(__('forum.solved')) }}
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('topics.solve', ['id' => $topic->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-danger">
                                        {{ __('common.remove') }} {{ strtoupper(__('forum.solved')) }}
                                    </button>
                                </form>
                            @endif
                            @if ($topic->invalid === 0)
                                <form action="{{ route('topics.invalid', ['id' => $topic->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-success">
                                        {{ __('common.add') }} {{ strtoupper(__('forum.invalid')) }}
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('topics.invalid', ['id' => $topic->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-danger">
                                        {{ __('common.remove') }} {{ strtoupper(__('forum.invalid')) }}
                                    </button>
                                </form>
                            @endif
                            @if ($topic->bug === 0)
                                <form action="{{ route('topics.bug', ['id' => $topic->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-success">
                                        {{ __('common.add') }} {{ strtoupper(__('forum.bug')) }}
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('topics.bug', ['id' => $topic->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-danger">
                                        {{ __('common.remove') }} {{ strtoupper(__('forum.bug')) }}
                                    </button>
                                </form>
                            @endif
                            @if ($topic->suggestion === 0)
                                <form action="{{ route('topics.suggest', ['id' => $topic->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-success">
                                        {{ __('common.add') }} {{ strtoupper(__('forum.suggestion')) }}
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('topics.suggest', ['id' => $topic->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-danger">
                                        {{ __('common.remove') }} {{ strtoupper(__('forum.suggestion')) }}
                                    </button>
                                </form>
                            @endif
                            @if ($topic->implemented === 0)
                                <form action="{{ route('topics.implement', ['id' => $topic->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-success">
                                        {{ __('common.add') }} {{ strtoupper(__('forum.implemented')) }}
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('topics.implement', ['id' => $topic->id]) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-danger">
                                        {{ __('common.remove') }} {{ strtoupper(__('forum.implemented')) }}
                                    </button>
                                </form>
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
      $(document).ready(function () {
        $('#topic-response').wysibb()
      })
    </script>

    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
      $(document).ready(function () {
        $('.profil').on('click', 'button#quote', function () {
          let author = $(this).closest('.profil').find('.post-info-username').first().text()
          let text = $(this).closest('.profil').find('.post-content').data('bbcode')
          $('#topic-response').wysibb().insertAtCursor('[quote=@' + author.trim() + ']' + text.trim() + '[/quote]\r\n', true)
        })
      })

    </script>
@endsection
