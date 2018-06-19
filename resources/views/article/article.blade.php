@extends('layout.default')

@section('title')
    <title>{{ $article->title }} - {{ trans('articles.articles') }} - {{ config('other.title') }}</title>
@endsection

@section('stylesheets')

@endsection

@section('meta')
    <meta name="description" content="{{ substr(strip_tags($article->content), 0, 200) }}...">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('articles') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('articles.articles') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('article', ['slug' => $article->slug, 'id' => $article->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $article->title }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="box container">
        <article class="article col-md-12">

            <h1 class="article-title">{{ $article->title }}</h1>

            <div class="article-info">
                <span>{{ trans('articles.published-at') }}</span>
                <time datetime="{{ date(DATE_W3C, $article->created_at->getTimestamp()) }}">{{ date('d M Y', $article->created_at->getTimestamp()) }}</time>
            </div>

            <div class="article-content">
                @emojione($article->getContentHtml())
            </div>
            <hr>
        </article>

        <div class="col-md-12">
            <form role="form" method="POST"
                  action="{{ route('comment_article',['slug' => $article->slug, 'id' => $article->id]) }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="content">{{ trans('common.your') }} {{ strtolower(trans('common.comment')) }}
                        :</label><span class="badge-extra">{{ trans('common.type') }}
                        <strong>:</strong> {{ trans('common.for') }} emoji</span> <span
                            class="badge-extra">BBCode {{ trans('common.is-allowed') }}</span>
                    <textarea name="content" id="content" cols="30" rows="5" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-default">{{ trans('common.submit') }}</button>
            </form>
            <hr>
        </div>

        <div class="comments col-md-12">
            <ul class="media-list comments-list">
                @foreach($comments as $comment)
                    <li class="media" style="border-left: 5px solid #01BC8C">
                        <div class="media-body">
                            @if($comment->anon == 1)
                                <a href="#" class="pull-left">
                                    <img src="{{ url('img/profile.png') }}" alt="{{ $comment->user->username }}"
                                         class="img-avatar-48"></a>
                                <strong>{{ str_upper(trans('common.anonymous')) }} @if(auth()->user()->group->is_modo)<a
                                            href="{{ route('profile', ['username' => $comment->user->username, 'id' => $comment->user->id]) }}">({{ $comment->user->username }}
                                        )</a>@endif</strong>
                            @else
                                <a href="{{ route('profile', ['username' => $comment->user->username, 'id' => $comment->user->id]) }}"
                                   class="pull-left">
                                    @if($comment->user->image != null)
                                        <img src="{{ url('files/img/' . $comment->user->image) }}"
                                             alt="{{ $comment->user->username }}" class="img-avatar-48"></a>
                                @else
                                    <img src="{{ url('img/profile.png') }}" alt="{{ $comment->user->username }}"
                                         class="img-avatar-48"></a>
                                @endif
                                <strong>By <a
                                            href="{{ route('profile', ['username' => $comment->user->username, 'id' => $comment->user->id]) }}">{{ $comment->user->username }}</a></strong> @endif
                            <span class="text-muted"><small><em>{{$comment->created_at->diffForHumans() }}</em></small></span>
                            @if($comment->user_id == auth()->user()->id || auth()->user()->group->is_modo)
                                <a title="{{ trans('common.delete') }}"
                                   href="{{route('comment_delete',['comment_id'=>$comment->id])}}"><i
                                            class="pull-right fa fa-lg fa-times" aria-hidden="true"></i></a>
                                <a title="{{ trans('common.edit') }}" data-toggle="modal"
                                   data-target="#modal-comment-edit-{{ $comment->id }}"><i
                                            class="pull-right fa fa-lg fa-pencil" aria-hidden="true"></i></a>
                            @endif
                            <div class="pt-5">
                                @emojione($comment->getContentHtml())
                            </div>
                        </div>
                    </li>
                    @include('partials.modals', ['comment' => $comment])
                @endforeach
            </ul>
        </div>
    </div>
@endsection

@section('javascripts')
    <script>
      $(document).ready(function () {

        $('#content').wysibb({})

        emoji.textcomplete()
      })
    </script>
@endsection
