@extends('layout.default')

@section('title')
    <title>{{ $topic->name }} - Forums - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('forums.index') }}" class="breadcrumb__link">
            {{ __('forum.forums') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a
            href="{{ route('forums.categories.show', ['id' => $forum->category->id]) }}"
            class="breadcrumb__link"
        >
            {{ $forum->category->name }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('forums.show', ['id' => $forum->id]) }}" class="breadcrumb__link">
            {{ $forum->name }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $topic->name }}
    </li>
@endsection

@section('nav-tabs')
    @include('forum.partials.buttons')
@endsection

@section('page', 'page__forum--topic')

@section('main')
    @livewire('topic-post-search', ['topic' => $topic])
    @if ($topic->state === 'close' && auth()->user()->group->is_modo)
        <p>
            This topic is closed, but you can still reply due to you being
            {{ auth()->user()->group->name }}.
        </p>
    @endif

    @if (($topic->state === 'open' && $forum->getPermission()->reply_topic) || auth()->user()->group->is_modo)
        <form id="forum_reply_form" method="POST" action="{{ route('posts.store') }}">
            @csrf
            <input type="hidden" name="topic_id" value="{{ $topic->id }}" />
            @livewire('bbcode-input', ['name' => 'content', 'label' => __('forum.post') ])
            <p class="form__group">
                <button type="submit" class="form__button form__button--filled">
                    {{ __('common.submit') }}
                </button>
            </p>
        </form>
    @elseif ($topic->state === 'close')
        <p>{{ __('forum.topic-closed') }}</p>
    @endif
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('stat.stats') }}</h2>
        <dl class="key-value">
            <dt>{{ __('forum.author') }}</dt>
            <dd>
                <a href="{{ route('users.show', ['user' => $topic->user]) }}">
                    {{ $topic->first_post_user_username }}
                </a>
            </dd>
            <dt>{{ __('forum.created-at') }}</dt>
            <dd>{{ date('M d Y H:m', strtotime($topic->created_at)) }}</dd>
            <dt>{{ __('forum.replies') }}</dt>
            <dd>{{ $topic->num_post - 1 }}</dd>
            <dt>{{ __('forum.views') }}</dt>
            <dd>{{ $topic->views }}</dd>
        </dl>
        <div class="panel__body">
            @if ($subscription === null)
                <form class="form" action="{{ route('subscriptions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="topic_id" value="{{ $topic->id }}" />
                    <p class="form__group form__group--horizontal">
                        <button class="form__button form__button--filled form__button--centered">
                            <i class="{{ config('other.font-awesome') }} fa-bell"></i>
                            {{ __('forum.subscribe') }}
                        </button>
                    </p>
                </form>
            @else
                <form
                    class="form"
                    action="{{ route('subscriptions.destroy', ['id' => $subscription->id]) }}"
                    method="POST"
                >
                    @csrf
                    <input type="hidden" name="topic_id" value="{{ $topic->id }}" />
                    <p class="form__group form__group--horizontal">
                        <button class="form__button form__button--filled form__button--centered">
                            <i class="{{ config('other.font-awesome') }} fa-bell-slash"></i>
                            {{ __('forum.unsubscribe') }}
                        </button>
                    </p>
                </form>
            @endif
        </div>
    </section>
    @if (auth()->user()->group->is_modo || $topic->first_post_user_id == auth()->id())
        <section class="panelV2">
            <h2 class="panel__heading">{{ __('forum.topic') }} {{ __('user.settings') }}</h2>
            <div class="panel__body">
                @if ($topic->state === 'close')
                    <form
                        class="form"
                        action="{{ route('topics.open', ['id' => $topic->id]) }}"
                        method="POST"
                    >
                        @csrf
                        <p class="form__group form__group--horizontal">
                            <button
                                class="form__button form__button--filled form__button--centered"
                            >
                                {{ __('forum.open') }}
                            </button>
                        </p>
                    </form>
                @else
                    <form
                        class="form"
                        action="{{ route('topics.close', ['id' => $topic->id]) }}"
                        method="POST"
                    >
                        @csrf
                        <p class="form__group form__group--horizontal">
                            <button
                                class="form__button form__button--filled form__button--centered"
                            >
                                {{ __('common.close') }}
                            </button>
                        </p>
                    </form>
                @endif
                <div class="form">
                    <p class="form__group form__group--horizontal">
                        <a
                            href="{{ route('topics.edit', ['id' => $topic->id]) }}"
                            class="form__button form__button--filled form__button--centered"
                        >
                            {{ __('common.edit') }}
                        </a>
                    </p>
                </div>
                @if (auth()->user()->group->is_modo)
                    <form
                        class="form"
                        action="{{ route('topics.destroy', ['id' => $topic->id]) }}"
                        method="POST"
                        x-data="confirmation"
                    >
                        @csrf
                        @method('DELETE')
                        <p class="form__group form__group--horizontal">
                            <button
                                class="form__button form__button--filled form__button--centered"
                                x-on:click.prevent="confirmAction"
                                data-b64-deletion-message="{{ base64_encode('Are you sure you want to delete this topic: ' . $topic->name . '?') }}"
                            >
                                {{ __('common.delete') }}
                            </button>
                        </p>
                    </form>
                    @if (! $topic->pinned)
                        <form
                            class="form"
                            action="{{ route('topics.pin', ['id' => $topic->id]) }}"
                            method="POST"
                        >
                            @csrf
                            <p class="form__group form__group--horizontal">
                                <button
                                    class="form__button form__button--filled form__button--centered"
                                >
                                    {{ __('forum.pin') }}
                                </button>
                            </p>
                        </form>
                    @else
                        <form
                            class="form"
                            action="{{ route('topics.unpin', ['id' => $topic->id]) }}"
                            method="POST"
                        >
                            @csrf
                            <p class="form__group form__group--horizontal">
                                <button
                                    class="form__button form__button--filled form__button--centered"
                                >
                                    {{ __('forum.unpin') }}
                                </button>
                            </p>
                        </form>
                    @endif
                @endif
            </div>
        </section>
    @endif

    @if (auth()->user()->group->is_modo)
        <section class="panelV2" x-data>
            <h2 class="panel__heading">{{ __('forum.label-system') }}</h2>
            <div class="panel__body">
                <form
                    class="form"
                    action="{{ route('topics.approve', ['id' => $topic->id]) }}"
                    method="POST"
                >
                    @csrf
                    <p class="form__group">
                        <input
                            id="approved-label"
                            class="form__checkbox"
                            type="checkbox"
                            @checked($topic->approved)
                            x-on:change="$el.form.submit()"
                        />
                        <label for="approved-label">{{ __('forum.approved') }}</label>
                    </p>
                </form>
                <form
                    class="form"
                    action="{{ route('topics.deny', ['id' => $topic->id]) }}"
                    method="POST"
                >
                    @csrf
                    <p class="form__group">
                        <input
                            id="denied-label"
                            class="form__checkbox"
                            type="checkbox"
                            @checked($topic->denied)
                            x-on:change="$el.form.submit()"
                        />
                        <label for="denied-label">{{ __('forum.denied') }}</label>
                    </p>
                </form>
                <form
                    class="form"
                    action="{{ route('topics.solve', ['id' => $topic->id]) }}"
                    method="POST"
                >
                    @csrf
                    <p class="form__group">
                        <input
                            id="solved-label"
                            class="form__checkbox"
                            type="checkbox"
                            @checked($topic->solved)
                            x-on:change="$el.form.submit()"
                        />
                        <label for="solved-label">{{ __('forum.solved') }}</label>
                    </p>
                </form>
                <form
                    class="form"
                    action="{{ route('topics.invalid', ['id' => $topic->id]) }}"
                    method="POST"
                >
                    @csrf
                    <p class="form__group">
                        <input
                            id="invalid-label"
                            class="form__checkbox"
                            type="checkbox"
                            @checked($topic->invalid)
                            x-on:change="$el.form.submit()"
                        />
                        <label for="invalid-label">{{ __('forum.invalid') }}</label>
                    </p>
                </form>
                <form
                    class="form"
                    action="{{ route('topics.bug', ['id' => $topic->id]) }}"
                    method="POST"
                >
                    @csrf
                    <p class="form__group">
                        <input
                            id="bug-label"
                            class="form__checkbox"
                            type="checkbox"
                            @checked($topic->bug)
                            x-on:change="$el.form.submit()"
                        />
                        <label for="bug-label">{{ __('forum.bug') }}</label>
                    </p>
                </form>
                <form
                    class="form"
                    action="{{ route('topics.suggest', ['id' => $topic->id]) }}"
                    method="POST"
                >
                    @csrf
                    <p class="form__group">
                        <input
                            id="suggestion-label"
                            class="form__checkbox"
                            type="checkbox"
                            @checked($topic->suggestion)
                            x-on:change="$el.form.submit()"
                        />
                        <label for="suggestion-label">{{ __('forum.suggestion') }}</label>
                    </p>
                </form>
                <form
                    class="form"
                    action="{{ route('topics.implement', ['id' => $topic->id]) }}"
                    method="POST"
                >
                    @csrf
                    <p class="form__group">
                        <input
                            id="implemented-label"
                            class="form__checkbox"
                            type="checkbox"
                            @checked($topic->implemented)
                            x-on:change="$el.form.submit()"
                        />
                        <label for="implemented-label">{{ __('forum.implemented') }}</label>
                    </p>
                </form>
            </div>
        </section>
    @endif
@endsection
