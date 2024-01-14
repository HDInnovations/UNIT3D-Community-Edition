@props([
    'post',
])

<article class="post" id="post-{{ $post->id }}" x-data>
    <header class="post__header">
        <time
            class="post__datetime"
            datetime="{{ $post->created_at }}"
            title="{{ $post->created_at }}"
        >
            {{ $post->created_at?->diffForHumans() }}
        </time>
        @if (! Route::is('topics.show'))
            <span class="post__topic">
                {{ __('forum.in') }}
                <a href="{{ route('topics.show', ['id' => $post->topic->id]) }}">
                    {{ $post->topic->name }}
                </a>
            </span>
        @endif

        @if ($post->tips_sum_cost > 0)
            <dl class="post__tip-stats">
                <dt>{{ __('torrent.bon-tipped') }}</dt>
                <dd>{{ $post->tips_sum_cost ?? 0 }}</dd>
            </dl>
        @endif

        <menu class="post__toolbar">
            <li class="post__toolbar-item">
                <form
                    class="post__tip"
                    role="form"
                    method="POST"
                    action="{{ route('users.tips.store', ['user' => auth()->user()]) }}"
                >
                    @csrf
                    <input type="hidden" name="recipient" value="{{ $post->user->id }}" />
                    <input type="hidden" name="post" value="{{ $post->id }}" />
                    <input
                        class="post__tip-input"
                        inputmode="numeric"
                        list="quick-tip-values"
                        name="tip"
                        pattern="[0-9]*"
                        placeholder="0"
                        type="text"
                        value="0"
                    />
                    <button
                        class="post__tip-button"
                        type="submit"
                        title="{{ __('forum.tip-this-post') }}"
                    >
                        Tip
                    </button>
                    <datalist id="quick-tip-values">
                        <option value="10"></option>
                        <option value="20"></option>
                        <option value="50"></option>
                        <option value="100"></option>
                        <option value="200"></option>
                        <option value="500"></option>
                        <option value="1000"></option>
                    </datalist>
                </form>
            </li>
            <li class="post__toolbar-item">
                @livewire('like-button', ['post' => $post, 'likesCount' => $post->likes_count], key('like-'.$post->id))
            </li>
            <li class="post__toolbar-item">
                @livewire('dislike-button', ['post' => $post, 'dislikesCount' => $post->dislikes_count], key('dislike-'.$post->id))
            </li>
            <li class="post__toolbar-item">
                <a
                    class="post__permalink"
                    href="{{ route('topics.permalink', ['topicId' => $post->topic_id, 'postId' => $post->id]) }}"
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
                        x-on:click="
                            document.getElementById('forum_reply_form').style.display = 'block';
                            input = document.getElementById('bbcode-content');
                            input.value += '[quote={{ \htmlspecialchars('@' . $post->user->username) }}]';
                            input.value += (() => {
                                var text = document.createElement('textarea');
                                text.innerHTML = decodeURIComponent(
                                    atob($refs.content.dataset.base64Bbcode)
                                        .split('')
                                        .map((c) => '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2))
                                        .join('')
                                );
                                return text.value;
                            })();
                            input.value += '[/quote]';
                            input.dispatchEvent(new Event('input'));
                            input.focus();
                        "
                    >
                        <i class="{{ \config('other.font-awesome') }} fa-quote-left"></i>
                    </button>
                </li>
            @endif

            @if (auth()->user()->group->is_modo || ($post->user->id === auth()->id() && $post->topic->state === 'open'))
                <li class="post__toolbar-item">
                    <a
                        class="post__edit"
                        href="{{ route('posts.edit', ['id' => $post->id]) }}"
                        title="{{ __('common.edit') }}"
                    >
                        <i class="{{ \config('other.font-awesome') }} fa-pencil"></i>
                    </a>
                </li>
                <li class="post__toolbar-item">
                    <form
                        class="post__delete"
                        role="form"
                        method="POST"
                        action="{{ route('posts.destroy', ['id' => $post->id]) }}"
                        x-data
                    >
                        @csrf
                        @method('DELETE')
                        <button
                            class="post__delete-button"
                            type="submit"
                            title="{{ __('common.delete') }}"
                            x-on:click.prevent="
                                Swal.fire({
                                    title: 'Are you sure?',
                                    text: 'Are you sure you want to delete this post?',
                                    icon: 'warning',
                                    showConfirmButton: true,
                                    showCancelButton: true,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $root.submit();
                                    }
                                })
                            "
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
                src="{{ url($post->user->image === null ? 'img/profile.png' : 'files/img/' . $post->user->image) }}"
                alt=""
            />
        </figure>
        <x-user_tag class="post__author" :anon="false" :user="$post->user">
            <x-slot:appended-icons>
                @if ($post->user->isOnline())
                    <i
                        class="{{ config('other.font-awesome') }} fa-circle text-green"
                        title="Online"
                    ></i>
                @else
                    <i
                        class="{{ config('other.font-awesome') }} fa-circle text-red"
                        title="Offline"
                    ></i>
                @endif
                <a
                    href="{{ route('users.sent_messages.create', ['user' => auth()->user(), 'username' => $post->user->username]) }}"
                >
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
            <dd>{{ $post->author_topics_count ?? '0' }}</dd>
        </dl>
        <dl class="post__author-posts">
            <dt>
                <a href="{{ route('users.posts.index', ['user' => $post->user]) }}">
                    {{ __('forum.posts') }}
                </a>
            </dt>
            <dd>{{ $post->author_posts_count ?? '0' }}</dd>
        </dl>
    </aside>
    <div
        class="post__content bbcode-rendered"
        x-ref="content"
        data-base64-bbcode="{{ base64_encode($post->content) }}"
    >
        @joypixels($post->getContentHtml())
    </div>
    @if (! empty($post->user->signature))
        <footer class="post__footer" x-init>
            <p class="post__signature">
                {!! $post->user->signature_html !!}
            </p>
        </footer>
    @endif
</article>
