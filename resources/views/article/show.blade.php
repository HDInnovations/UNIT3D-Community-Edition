@extends('layout.default')

@section('title')
    <title>{{ $article->title }} - {{ __('articles.articles') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ substr(strip_tags($article->content), 0, 200) }}...">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('articles.index') }}" class="breadcrumb__link">
            {{ __('articles.articles') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $article->title }}
    </li>
@endsection

@section('page', 'page__articles--show')

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h1 class="panel__heading">{{ $article->title }}</h1>
            <div class="panel__actions">
                <time class="panel__action page__published" datetime="{{ $article->created_at }}">
                    {{ $article->created_at->toDayDateTimeString() }}
                </time>
            </div>
        </header>
        <div class="panel__body">
            @joypixels($article->getContentHtml())
        </div>
    </section>
    <section class="panelV2">
        <h4 class="panel__heading">
            <i class="{{ config('other.font-awesome') }} fa-comment"></i>
            {{ __('common.comments') }}
        </h4>
        <div class="panel-body no-padding">
            <ul class="media-list comments-list">
                @if (count($article->comments) == 0)
                    <div class="text-center">
                        <h4 class="text-bold text-danger">
                            <i class="{{ config('other.font-awesome') }} fa-frown"></i>
                            {{ __('common.no-comments') }}!
                        </h4>
                    </div>
                @else
                    @foreach ($article->comments as $comment)
                        <li class="media" style="border-left: 5px solid #01bc8c;">
                            <div class="media-body">
                                @if ($comment->anon == 1)
                                    <a href="#" class="pull-left" style="padding-right: 10px;">
                                        <img src="{{ url('img/profile.png') }}" class="img-avatar-48">
                                        <strong>{{ strtoupper(__('common.anonymous')) }}</strong>
                                    </a>
                                    @if (auth()->user()->id == $comment->user->id || auth()->user()->group->is_modo)
                                        <a
                                            href="{{ route('users.show', ['username' => $comment->user->username]) }}"
                                            style="color:{{ $comment->user->group->color }};"
                                        >
                                            (
                                            <span>
                                                <i class="{{ $comment->user->group->icon }}"></i>
                                                {{ $comment->user->username }}
                                            </span>
                                            )
                                        </a>
                                    @endif
                                @else
                                    <a
                                        href="{{ route('users.show', ['username' => $comment->user->username]) }}"
                                        class="pull-left" style="padding-right: 10px;"
                                    >
                                        @if ($comment->user->image != null)
                                                <img
                                                    src="{{ url('files/img/' . $comment->user->image) }}"
                                                    alt="{{ $comment->user->username }}" class="img-avatar-48"
                                                >
                                            </a>
                                        @else
                                                <img
                                                    src="{{ url('img/profile.png') }}"
                                                    alt="{{ $comment->user->username }}" class="img-avatar-48"
                                                >
                                            </a>
                                        @endif
                                    <strong>
                                        <a
                                            href="{{ route('users.show', ['username' => $comment->user->username]) }}"
                                            style="color:{{ $comment->user->group->color }};"
                                        >
                                            <span>
                                                <i class="{{ $comment->user->group->icon }}"></i>
                                                {{ $comment->user->username }}
                                            </span>
                                        </a>
                                    </strong>
                                @endif
                                <span class="text-muted">
                                    <small>
                                        <em>{{$comment->created_at->diffForHumans() }}</em>
                                    </small>
                                </span>
                                @if ($comment->user_id == auth()->id() || auth()->user()->group->is_modo)
                                    <div class="pull-right" style="display: inline-block;">
                                        <a data-toggle="modal" data-target="#modal-comment-edit-{{ $comment->id }}">
                                            <button class="btn btn-circle btn-info">
                                                <i class="{{ config('other.font-awesome') }} fa-pencil"></i>
                                            </button>
                                        </a>
                                        <form
                                            action="{{ route('comment_delete', ['comment_id' => $comment->id]) }}"
                                            method="POST"
                                            style="display: inline-block;"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-circle btn-danger">
                                                <i class="{{ config('other.font-awesome') }} fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                                <div class="pt-5">
                                    @joypixels($comment->getContentHtml())
                                </div>
                            </div>
                        </li>
                        @include('partials.modals', ['comment' => $comment])
                    @endforeach
                @endif
            </ul>
        </div>
    </section>
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('common.your-comment') }}</h2>
            <div class="panel__actions">
                <span class="panel__action" x-data="{ emoji: false }">
                    <img src="{{ url('img/emoji-add.png') }}" width="32px" x-on:click="emoji = ! emoji">
                    <div style="position: absolute; z-index: 1; width: 240px; right: 0;" x-show="emoji" x-on:click.away="emoji = false">
                        <emoji-picker></emoji-picker>
                    </div>
                </span>
            </div>
        </header>
        <div class="panel__body">
            <form class="form" method="POST" action="{{ route('comment_article', ['id' => $article->id]) }}">
                @csrf
                <p class="form__group">
                    <textarea id="editor" name="content" cols="30" rows="5" class="form-control"></textarea>
                </p>
                <p class="form__group">
                    <input type="hidden" value="0" name="anonymous">
                    <input type="checkbox" id="anon-comment" class="form__checkbox" value="1" name="anonymous">
                    <label for="anon-comment">{{ __('common.anonymous') }} {{ __('common.comment') }}</label>
                </p>
                <p class="form__group">
                    <button type="submit" class="form__button form__button--filled">{{ __('common.submit') }}</button>
                </p>
            </form>
        </div>
    </section>
@endsection
