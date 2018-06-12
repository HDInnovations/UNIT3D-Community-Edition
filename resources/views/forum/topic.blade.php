@extends('layout.default')

@section('title')
    <title>{{ $topic->name }} - Forums - {{ config('other.title') }}</title>
@endsection

@section('stylesheets')

@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('forum_index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('forum.forums') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forum_display', array('slug' => $forum->slug, 'id' => $forum->id)) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $forum->name }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forum_topic', array('slug' => $topic->slug, 'id' => $topic->id)) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $topic->name }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="topic container-fluid">

        <h2>{{ $topic->name }}</h2>

        <div class="topic-info">
            {{ trans('forum.author') }} <a
                    href="{{ route('profile', ['username' => $topic->first_post_user_username, 'id' => $topic->first_post_user_id]) }}">{{ $topic->first_post_user_username }}</a>, {{ date('M d Y H:m', strtotime($topic->created_at)) }}
            <span class='label label-primary'>{{ $topic->num_post - 1 }} {{ strtolower(trans('forum.replies')) }}</span>
            <span class='label label-info'>{{ $topic->views - 1 }} {{ strtolower(trans('forum.views')) }}</span>
            @if(auth()->user()->isSubscribed($topic->id))
                <a href="{{ route('unsubscribe', ['topic' => $topic->id]) }}" class="label label-sm label-danger">
                    <i class="fa fa-envelope"></i> Unsubscribe</a>
            @else
                <a href="{{ route('subscribe', ['topic' => $topic->id]) }}" class="label label-sm label-success">
                    <i class="fa fa-envelope"></i> Subscribe</a>
            @endif
            <span style="float: right;"> {{ $posts->links() }}</span>
        </div>
        <br>
        <div class="topic-posts">
            @foreach($posts as $k => $p)
                <div class="post" id="post-{{$p->id}}">
                    <div class="block">
                        <div class="profil">
                            <div class="head">
                                <p>{{ date('M d Y', $p->created_at->getTimestamp()) }}
                                    ({{ $p->created_at->diffForHumans() }}) <a class="text-bold permalink"
                                                                               href="{{ route('forum_topic', array('slug' => $p->topic->slug, 'id' => $p->topic->id)) }}?page={{$p->getPageNumber()}}#post-{{$p->id}}">{{ trans('forum.permalink') }}</a>
                                </p>
                            </div>
                            <aside class="col-md-2 post-info">
                                @if($p->user->image != null)
                                    <img src="{{ url('files/img/' . $p->user->image) }}" alt="{{ $p->user->username }}"
                                         class="img-thumbnail post-info-image">
                                @else
                                    <img src="{{ url('img/profile.png') }}" alt="{{ $p->user->username }}"
                                         class="img-thumbnail post-info-image">
                                @endif
                <p>
                <span class="badge-user text-bold">
                   <a href="{{ route('profile', ['username' => $p->user->username, 'id' => $p->user->id]) }}"
                      class="post-info-username" style="color:{{ $p->user->group->color }}; display:inline;">{{ $p->user->username }}</a>
                    @if($p->user->isOnline())
                        <i class="fa fa-circle text-green" data-toggle="tooltip" title=""
                           data-original-title="Online"></i>
                    @else
                        <i class="fa fa-circle text-red" data-toggle="tooltip" title=""
                           data-original-title="Offline"></i>
                    @endif
                    <a href="{{ route('create', ['receiver_id' => $p->user->id, 'username' => $p->user->username]) }}">
                        <i class="fa fa-envelope text-info"></i>
                    </a>
                </span>
                </p>
                                <p><span class="badge-user text-bold"
                                         style="color:{{ $p->user->group->color }}; background-image:{{ $p->user->group->effect }};"><i
                                                class="{{ $p->user->group->icon }}" data-toggle="tooltip" title=""
                                                data-original-title="{{ $p->user->group->name }}"></i> {{ $p->user->group->name }}</span>
                                </p>
                                <p class="pre">{{ $p->user->title }}</p>
                                <p>{{ trans('user.member-since') }}
                                    : {{ date('M d Y', $p->user->created_at->getTimestamp()) }}</p>
                                <span class="inline">
            @if($topic->state == 'open')
                                        <button id="quote"
                                                class="btn btn-xs btn-xxs btn-info">{{ trans('forum.quote') }}</button>
                                    @endif
                                    @if(auth()->check() && (auth()->user()->group->is_modo || $p->user_id == auth()->user()->id) && $topic->state == 'open')
                                        <a href="{{ route('forum_post_edit_form', ['slug' => $topic->slug, 'id' => $topic->id, 'postId' => $p->id]) }}"><button
                                                    class="btn btn-xs btn-xxs btn-warning">{{ trans('common.edit') }}</button></a>
                                        <a href="{{ route('forum_post_delete', ['slug' => $topic->slug, 'id' => $topic->id, 'postId' => $p->id]) }}"><button
                                                    class="btn btn-xs btn-xxs btn-danger">{{ trans('common.delete') }}</button></a>
                                    @endif
            </span>
                            </aside>

                            <article class="col-md-10 post-content">
                                @emojione($p->getContentHtml())
                            </article>

                            @php $likes = DB::table('likes')->where('post_id', '=', $p->id)->where('like', '=', 1)->count(); @endphp
                            @php $dislikes = DB::table('likes')->where('post_id', '=', $p->id)->where('dislike', '=', 1)->count(); @endphp
                            <div class="likes">
          <span class="badge-extra">
            @if(auth()->user()->likes()->where('post_id', $p->id)->where('like', '=', 1)->first())
                  <a href="{{ route('like', ['postId' => $p->id]) }}" class="text-green" data-toggle="tooltip"
                     style="margin-right: 16px;" data-original-title="{{ trans('forum.like-post') }}"><i
                              class="icon-like fa fa-thumbs-up fa-2x fa-beat"></i>
              <span class="count" style="font-size: 20px;">{{ $likes }}</span></a>
              @else
                  <a href="{{ route('like', ['postId' => $p->id]) }}" class="text-green" data-toggle="tooltip"
                     style="margin-right: 16px;" data-original-title="{{ trans('forum.like-post') }}"><i
                              class="icon-like fa fa-thumbs-up fa-2x"></i>
              <span class="count" style="font-size: 20px;">{{ $likes }}</span></a>
              @endif
              @if(auth()->user()->likes()->where('post_id', $p->id)->where('dislike', '=', 1)->first())
                  <a href="{{ route('dislike', ['postId' => $p->id]) }}" class="text-red" data-toggle="tooltip"
                     data-original-title="{{ trans('forum.dislike-post') }}"><i
                              class="icon-dislike fa fa-thumbs-down fa-2x fa-beat"></i>
              <span class="count" style="font-size: 20px;">{{ $dislikes }}</span></a>
              @else
                  <a href="{{ route('dislike', ['postId' => $p->id]) }}" class="text-red" data-toggle="tooltip"
                     data-original-title="{{ trans('forum.dislike-post') }}"><i
                              class="icon-dislike fa fa-thumbs-down fa-2x"></i>
              <span class="count" style="font-size: 20px;">{{ $dislikes }}</span></a>
              @endif
          </span>
                            </div>

                            <div class="post-signature col-md-12">
                                @if($p->user->signature != null)
                                    {!! $p->user->getSignature() !!}
                                @endif
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
                        @if($topic->state == "close" && auth()->user()->group->is_modo)
                            <form role="form" method="POST"
                                  action="{{ route('forum_reply',['slug' => $topic->slug, 'id' => $topic->id]) }}">
                                {{ csrf_field() }}
                                <div class="text-danger">This topic is closed, but you can still reply due to you
                                    being {{auth()->user()->group->name}}.
                                </div>
                                <div class="from-group">
                                    <textarea name="content" id="topic-response" cols="30" rows="10"></textarea>
                                </div>
                                @if(auth()->check())
                                    <button type="submit" class="btn btn-primary">{{ trans('common.submit') }}</button>
                                @else
                                    <button type="submit"
                                            class="btn btn-default disabled">{{ trans('forum.not-connected') }}</button>
                                @endif
                            </form>
                        @elseif($topic->state == "close")
                            <div class="col-md-12 alert alert-danger">{{ trans('forum.topic-closed') }}</div>
                        @else
                            <form role="form" method="POST"
                                  action="{{ route('forum_reply',['slug' => $topic->slug, 'id' => $topic->id]) }}">
                                {{ csrf_field() }}
                                <div class="from-group">
                                    <textarea name="content" id="topic-response" cols="30" rows="10"></textarea>
                                </div>
                                @if(auth()->check())
                                    <button type="submit" class="btn btn-primary">{{ trans('common.submit') }}</button>
                                @else
                                    <button type="submit"
                                            class="btn btn-default disabled">{{ trans('forum.not-connected') }}</button>
                                @endif
                            </form>
                        @endif

                        <div class="text-center">
                            @if(auth()->check() && (auth()->user()->group->is_modo || $topic->user_id == auth()->user()->id))
                                <h3>{{ trans('forum.moderation') }}</h3>
                                @if($topic->state == "close")
                                    <a href="{{ route('forum_open', ['slug' => $topic->slug, 'id' => $topic->id, ])}}"
                                       class="btn btn-success">{{ trans('forum.open-topic') }}</a>
                                @else
                                    <a href="{{ route('forum_close', ['slug' => $topic->slug, 'id' => $topic->id, ])}}"
                                       class="btn btn-info">{{ trans('forum.mark-as-resolved') }}</a>
                                @endif
                            @endif
                            @if(auth()->check() && auth()->user()->group->is_modo)
                                <a href="{{ route('forum_edit_topic_form', ['slug' => $topic->slug, 'id' => $topic->id]) }}"
                                   class="btn btn-warning">{{ trans('forum.edit-topic') }}</a>
                                <a href="{{ route('forum_delete_topic', ['slug' => $topic->slug, 'id' => $topic->id]) }}"
                                   class="btn btn-danger">{{ trans('forum.delete-topic') }}</a>
                            @endif
                            @if(auth()->check() && auth()->user()->group->is_modo)
                                @if($topic->pinned == 0)
                                    <a href="{{ route('forum_pin_topic', ['slug' => $topic->slug, 'id' => $topic->id]) }}"
                                       class="btn btn-primary">{{ trans('forum.pin') }} {{ strtolower(trans('forum.topic')) }}</a>
                                @else
                                    <a href="{{ route('forum_unpin_topic', ['slug' => $topic->slug, 'id' => $topic->id]) }}"
                                       class="btn btn-default">{{ trans('forum.unpin') }} {{ strtolower(trans('forum.topic')) }}</a>
                                @endif
                            @endif

                            <br>

                            @if(auth()->check() && auth()->user()->group->is_modo)
                                <h3>{{ trans('forum.label-system') }}</h3>
                                @if($topic->approved == "0")
                                    <a href="{{ route('forum_approved', ['slug' => $topic->slug, 'id' => $topic->id, ])}}"
                                       class='label label-sm label-success'>{{ trans('common.add') }} {{ strtoupper(trans('forum.approved')) }}</a>
                                @else
                                    <a href="{{ route('forum_approved', ['slug' => $topic->slug, 'id' => $topic->id, ])}}"
                                       class='label label-sm label-success'>{{ trans('common.remove') }} {{ strtoupper(trans('forum.approved')) }}</a>
                                @endif
                                @if($topic->denied == "0")
                                    <a href="{{ route('forum_denied', ['slug' => $topic->slug, 'id' => $topic->id, ])}}"
                                       class='label label-sm label-danger'>{{ trans('common.add') }} {{ strtoupper(trans('forum.denied')) }}</a>
                                @else
                                    <a href="{{ route('forum_denied', ['slug' => $topic->slug, 'id' => $topic->id, ])}}"
                                       class='label label-sm label-danger'>{{ trans('common.remove') }} {{ strtoupper(trans('forum.denied')) }}</a>
                                @endif
                                @if($topic->solved == "0")
                                    <a href="{{ route('forum_solved', ['slug' => $topic->slug, 'id' => $topic->id, ])}}"
                                       class='label label-sm label-info'>{{ trans('common.add') }} {{ strtoupper(trans('forum.solved')) }}</a>
                                @else
                                    <a href="{{ route('forum_solved', ['slug' => $topic->slug, 'id' => $topic->id, ])}}"
                                       class='label label-sm label-info'>{{ trans('common.remove') }} {{ strtoupper(trans('forum.solved')) }}</a>
                                @endif
                                @if($topic->invalid == "0")
                                    <a href="{{ route('forum_invalid', ['slug' => $topic->slug, 'id' => $topic->id, ])}}"
                                       class='label label-sm label-warning'>{{ trans('common.add') }} {{ strtoupper(trans('forum.invalid')) }}</a>
                                @else
                                    <a href="{{ route('forum_invalid', ['slug' => $topic->slug, 'id' => $topic->id, ])}}"
                                       class='label label-sm label-warning'>{{ trans('common.remove') }} {{ strtoupper(trans('forum.invalid')) }}</a>
                                @endif
                                @if($topic->bug == "0")
                                    <a href="{{ route('forum_bug', ['slug' => $topic->slug, 'id' => $topic->id, ])}}"
                                       class='label label-sm label-danger'>{{ trans('common.add') }} {{ strtoupper(trans('forum.bug')) }}</a>
                                @else
                                    <a href="{{ route('forum_bug', ['slug' => $topic->slug, 'id' => $topic->id, ])}}"
                                       class='label label-sm label-danger'>{{ trans('common.remove') }} {{ strtoupper(trans('forum.bug')) }}</a>
                                @endif
                                @if($topic->suggestion == "0")
                                    <a href="{{ route('forum_suggestion', ['slug' => $topic->slug, 'id' => $topic->id, ])}}"
                                       class='label label-sm label-primary'>{{ trans('common.add') }} {{ strtoupper(trans('forum.suggestion')) }}</a>
                                @else
                                    <a href="{{ route('forum_suggestion', ['slug' => $topic->slug, 'id' => $topic->id, ])}}"
                                       class='label label-sm label-primary'>{{ trans('common.remove') }} {{ strtoupper(trans('forum.suggestion')) }}</a>
                                @endif
                                @if($topic->implemented == "0")
                                    <a href="{{ route('forum_implemented', ['slug' => $topic->slug, 'id' => $topic->id, ])}}"
                                       class='label label-sm label-success'>{{ trans('common.add') }} {{ strtoupper(trans('forum.implemented')) }}</a>
                                @else
                                    <a href="{{ route('forum_implemented', ['slug' => $topic->slug, 'id' => $topic->id, ])}}"
                                       class='label label-sm label-success'>{{ trans('common.remove') }} {{ strtoupper(trans('forum.implemented')) }}</a>
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
    <script>
        $(document).ready(function () {
            let wbbOpt = {
                allButtons: {
                    quote: {
                        transform: {
                            '<div class="wbbquote"><cite><b>{AUTHOR}</b> wrote:</cite> <br /> <br />{SELTEXT}</div>': '[quote={AUTHOR}]{SELTEXT}[/quote]'
                        }
                    }
                }
            }

            let editor = $("#topic-response").wysibb(wbbOpt);

            // Initialize emojis
            emoji.textcomplete();

            $('.profil').on('click', 'button#quote', function () {
                let author = $(this).closest('.post-info').find('.badge-user').first().text();
                let text = $(this).closest('.profil').find('.post-content').first().text().replace('@here', '');

                editor.execCommand('quote', {author: '@'+author+' ', seltext: text});
            });
        });
    </script>
@endsection
