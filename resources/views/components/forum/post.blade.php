@props([
    'post' => (object) [
        'id'                               => 1,
        'topic'                            => (object) [
            'id' => 1,
            'name' => '',
            'state' => 'closed',
            'tips' => [
                (object) ['cost' => 0],
            ],
        ],
        'page'                             => -1,
        'created_at'                       => '',
        'user'                             => [
            'id'       => 1,
            'image'    => null,
            'group' => (object) [
                'color' => '',
                'effect' => '',
                'group' => '',
                'icon' => '',
            ],
            'posts' => [],
            'signature' => '',
            'title' => '',
            'topics' => [],
            'username' => '',
        ],
        'content'                         => '',
    ],
])

<article class="post" id="post-{{ $post->id }}">
    <header class="post__header">
        <time
            class="post__datetime"
            datetime="{{ $post->created_at }}"
            title="{{ $post->created_at }}"
        >
            {{ $post->created_at?->diffForHumans() }}
        </time>
        @if (! Route::is('forum_topic'))
            <span class="post__topic">
                {{ __('forum.in') }}
                <a href="{{ route('forum_topic', ['id' => $post->topic->id]) }}">{{ $post->topic->name }}</a>
            </span>
        @endif
        @if($post->tips?->sum('cost') > 0)
            <dl class="post__tip-stats">
                <dt>{{ __('torrent.bon-tipped') }}</dt>
                <dd>{{ $post->tips?->sum('cost') ?? 0 }}</dd>
            </dl>
        @endif
        <menu class="post__toolbar">
            <li class="post__toolbar-item">
                <form
                    class="post__tip"
                    role="form"
                    method="POST"
                    action="{{ route('tips.store', ['username' => auth()->user()->username]) }}"
                >
                    @csrf
                    <input type="hidden" name="recipient" value="{{ $post->user->id }}">
                    <input type="hidden" name="post" value="{{ $post->id }}">
                    <input
                        class="post__tip-input"
                        inputmode="numeric"
                        list="quick-tip-values"
                        name="tip"
                        pattern="[0-9]*"
                        placeholder="0"
                        type="text"
                        value="0"
                    >
                    <button class="post__tip-button" type="submit" title="{{ __('forum.tip-this-post') }}">
                        Tip
                    </button>
                    <datalist id="quick-tip-values">
                        <option value="10">
                        <option value="20">
                        <option value="50">
                        <option value="100">
                        <option value="200">
                        <option value="500">
                        <option value="1000">
                    </datalist>
                </form>
            </li>
            <li class="post__toolbar-item">
                @livewire('like-button', ['post' => $post->id])
            </li>
            <li class="post__toolbar-item">
                @livewire('dislike-button', ['post' => $post->id])
            </li>
            <li class="post__toolbar-item">
                <a
                    class="post__permalink"
                    href="{{ route('forum_topic', ['id' => $post->topic->id]) }}?page={{ $post->getPageNumber() }}#post-{{ $post->id }}"
                    title="{{ __('forum.permalink') }}"
                >
                    <i class="{{ \config('other.font-awesome') }} fa-link"></i>
                </a>
            </li>
            @if (auth()->user()->group->is_modo || $post->topic->state === 'open')
                <li class="post__toolbar-item">
                    <button
                        class="post__quote"
                        title="{{ __('forum.quote') }}"
                        x-data
                        x-on:click="
                            document.getElementById('forum_reply_form').style.display = 'block';
                            input = document.getElementById('bbcode-content');
                            input.value += '[quote={{ \htmlspecialchars('@'.$post->user->username) }}]{{ \htmlspecialchars($post->content) }}[/quote]';
                            input.dispatchEvent(new Event('input'));
                            input.focus();
                        "
                    >
                        <i class="{{ \config('other.font-awesome') }} fa-quote-left"></i>
                    </button>
                </li>
            @endif
            @if (auth()->user()->group->is_modo || $post->user->id === auth()->user()->id)
                <li class="post__toolbar-item">
                    <a
                        class="post__edit"
                        href="{{ route('forum_post_edit_form', ['id' => $post->topic->id, 'postId' => $post->id]) }}"
                        title="{{ __('common.edit') }}"
                    >
                        <i class="{{ \config('other.font-awesome') }} fa-pencil"></i>
                    </a>
                </li>
            @endif
            @if (auth()->user()->group->is_modo || ($post->user->id === auth()->user()->id && $post->topic->state === 'open'))
                <li class="post__toolbar-item">
                    <form
                        class="post__delete"
                        role="form"
                        method="POST"
                        action="{{ route('forum_post_delete', ['id' => $post->topic->id, 'postId' => $post->id]) }}"
                    >
                        @csrf
                        @method('DELETE')
                        <button
                            class="post__delete-button"
                            type="submit"
                            title="{{ __('common.delete') }}"
                        >
                            <i class="{{ \config('other.font-awesome') }} fa-trash"></i>
                        </button>
                    </form>
                </li>
            @endif
        </menu>
    </header>
    <aside class="post__aside">
        <figure class="post__figure">
            <img
                class="post__avatar"
                src="{{ url($post->user->image === null ? 'img/profile.png' : 'files/img/'.$post->user->image) }}"
                alt=""
            >
        </figure>
        <x-user_tag
            class="post__author"
            :anon="false"
            :user="$post->user"
        >
            <x-slot:appended-icons>
                @if ($post->user->isOnline())
                    <i class="{{ config('other.font-awesome') }} fa-circle text-green" title="Online"></i>
                @else
                    <i class="{{ config('other.font-awesome') }} fa-circle text-red" title="Offline"></i>
                @endif
                <a href="{{ route('create', ['receiver_id' => $post->user->id, 'username' => $post->user->username]) }}">
                    <i class="{{ config('other.font-awesome') }} fa-envelope text-info"></i>
                </a>
            </x-slot>
        </x-user_tag>
        @if (! empty($post->user->title))
            <p class="post__author-title">
                {{ $post->user->title }}
            </p>
        @endif
        <dl class="post__author-join">
            <dt>Joined</dt>
            <dd>
                <time
                    class="post__author-join-datetime"
                    datetime="{{ $post->user->created_at }}"
                    title="{{ $post->user->created_at }}"
                >
                    {{ date('d M Y', $post->user->created_at?->getTimestamp() ?? '') }}
            </time>
            </dd>
        </dl>
        <dl class="post__author-topics">
            <dt>
                <a href="{{ route('users.topics.index', ['user' => $post->user]) }}">
                {{ __('forum.topics') }}
                </a>
            </dt>
            <dd>{{ $post->user->topics?->count() ?? '0' }}</dd>
        </dl>
        <dl class="post__author-posts">
            <dt>
                <a href="{{ route('users.posts.index', ['user' => $post->user]) }}">
                    {{ __('forum.posts') }}
                </a>
            </dt>
            <dd>{{ $post->user->posts?->count() ?? '0' }}</dd>
        </dl>
    </aside>
    <div
        class="post__content"
        data-bbcode="{{ $post->content }}"
    >
        @joypixels($post->getContentHtml())
    </div>
    @if (! empty($post->user->signature))
        <footer class="post__footer" x-init>
            <p class="post__signature">
                {!! $post->user->getSignature() !!}
            </p>
        </footer>
    @endif
</article>