@props([
    'topic' => (object) [
        'name'                     => '',
        'id'                       => 1,
        'first_post_user_username' => 'System',
        'num_post'                 => 0,
        'views'                    => 0,
        'pinned'                   => false,
        'state'                    => 'close',
        'approved'                 => false,
        'denied'                   => false,
        'solved'                   => false,
        'invalid'                  => false,
        'bug'                      => false,
        'suggestion'               => false,
        'implemented'              => false,
        'last_post_user_username'  => 'System',
        'last_reply_at'            => null,
        'forum'                    => (object) [
            'name' => '',
        ],
    ],
])

<article class="topic-listing">
    <header class="topic-listing__header">
        <h2 class="topic-listing__heading">
            <a
                class="topic-listing__link"
                href="{{ route('forum_topic', ['id' => $topic->id]) }}"
            >
                {{ $topic->name }}
            </a>
        </h2>
        <ul class="topic-tags">
            @if ($topic->approved)
                <li class="topic-tag topic-tag--approved">{{ __('forum.approved') }}</li>
            @endif
            @if ($topic->denied)
                <li class="topic-tag topic-tag--denied">{{ __('forum.denied') }}</li>
            @endif
            @if ($topic->solved)
                <li class="topic-tag topic-tag--solved">{{ __('forum.solved') }}</li>
            @endif
            @if ($topic->invalid)
                <li class="topic-tag topic-tag--invalid">{{ __('forum.invalid') }}</li>
            @endif
            @if ($topic->bug)
                <li class="topic-tag topic-tag--bug">{{ __('forum.bug') }}</li>
            @endif
            @if ($topic->suggestion)
                <li class="topic-tag topic-tag--suggestion">{{ __('forum.suggestion') }}</li>
            @endif
            @if ($topic->implemented)
                <li class="topic-tag topic-tag--implemented">{{ __('forum.implemented') }}</li>
            @endif
        </ul>
        <address class="topic-listing__author">
            <a href="{{ route('users.show', ['username' => $topic->first_post_user_username]) }}">
                {{ $topic->first_post_user_username }}
            </a>
        </address>
    </header>
    <figure class="topic-listing__figure">
        @if ($topic->pinned || $topic->state === 'close')
            <span class="topic-listing__icon">
                @if ($topic->pinned)
                    <abbr title="{{ __('common.sticked') }}">
                        <i class="{{ config('other.font-awesome') }} fa-thumbtack"></i>
                    </abbr>
                @endif
                @if ($topic->state === 'close')
                    <abbr title="{{ __('user.locked') }}">
                        <i class="{{ config('other.font-awesome') }} fa-lock"></i>
                    </abbr>
                @endif
            </span>
        @else
            <i class="fad fa-comments topic-listing__icon"></i>
        @endif
        <figcaption class="topic-listing__forum">
            {{ $topic->forum->name }}
        </figcaption>
    </figure>
    <dl class="topic-listing__post-stats">
        <dt>{{ __('forum.replies') }}</dt>
        <dd>{{ $topic->num_post - 1 }}</dd>
    </dl>
    <dl class="topic-listing__view-stats">
        <dt>{{ __('forum.views') }}</dt>
        <dd>{{ $topic->views }}</dd>
    </dl>
    <article class="topic-listing__latest-post">
        <address class="topic-listing__latest-author">
            <a
                class="topic-listing__latest-author-link"
                href="{{ route('users.show', ['username' => $topic->last_post_user_username]) }}"
            >
                {{ $topic->last_post_user_username }}
            </a>
        </address>
        <time
            class="topic-listing__latest-datetime"
            datetime="{{ $topic->last_reply_at ?? '' }}"
            title="{{ $topic->last_reply_at ?? '' }}"
        >
            {{ $topic->last_reply_at?->diffForHumans() ?? __('common.unknown') }}
        </time>
    </article>
</article>
