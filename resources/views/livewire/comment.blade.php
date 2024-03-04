<li class="comment__list-item">
    <article class="comment">
        <header class="comment__header">
            <time
                class="comment__datetime"
                datetime="{{ $comment->created_at }}"
                title="{{ $comment->created_at }}"
            >
                {{ $comment->created_at?->diffForHumans() }}
            </time>
            <menu class="comment__toolbar">
                @if ($comment->isParent() && $comment->children()->doesntExist())
                    <li class="comment__toolbar-item">
                        <button wire:click="$toggle('isReplying')" class="comment__reply">
                            <abbr class="comment__reply-abbr" title="Reply to this comment">
                                <i class="{{ config('other.font-awesome') }} fa-reply"></i>
                                <span class="sr-only">__('pm.reply')</span>
                            </abbr>
                        </button>
                    </li>
                @endif

                @if ($comment->user_id === auth()->id() || auth()->user()->group->is_modo)
                    <li class="comment__toolbar-item">
                        <button wire:click="$toggle('isEditing')" class="comment__edit">
                            <abbr
                                class="comment__edit-abbr"
                                title="{{ __('common.edit-your-comment') }}"
                            >
                                <i class="{{ config('other.font-awesome') }} fa-pencil"></i>
                                <span class="sr-only">__('common.edit')</span>
                            </abbr>
                        </button>
                    </li>
                    <li class="comment__toolbar-item">
                        <button
                            class="comment__delete-button"
                            x-on:click="confirmCommentDeletion"
                            x-data="{
                               confirmCommentDeletion () {
                                   if (window.confirm('You sure?')) {
                                        @this.call('deleteComment')
                                   }
                               }
                           }"
                        >
                            <abbr
                                class="comment__delete-abbr"
                                title="{{ __('common.delete-your-comment') }}"
                            >
                                <i class="{{ config('other.font-awesome') }} fa-trash"></i>
                                <span class="sr-only">__('common.delete')</span>
                            </abbr>
                        </button>
                    </li>
                @endif
            </menu>
        </header>
        <aside class="comment__aside">
            <figure class="comment__figure" style="text-align: center">
                <img
                    class="comment__avatar"
                    style="width: 50%"
                    src="{{ url(! $comment->anon && $comment->user->image !== null ? 'files/img/' . $comment->user->image : '/img/profile.png') }}"
                    alt=""
                />
            </figure>
            <x-user_tag
                class="comment__author"
                :anon="$comment->anon"
                :user="$comment->user"
            ></x-user_tag>
            @if (! $comment->anon && ! empty($comment->user->title))
                <p class="comment__author-title">
                    {{ $comment->user->title }}
                </p>
            @endif
        </aside>
        @if ($isEditing)
            <form wire:submit="editComment" class="form edit-comment">
                <p class="form__group">
                    <textarea
                        name="comment"
                        id="edit-comment"
                        class="form__textarea"
                        aria-describedby="edit-comment__textarea-hint"
                        wire:model="editState.content"
                        required
                    ></textarea>
                    <label for="edit-comment" class="form__label form__label--floating">
                        @error('editState.content')
                            <strong>{{ __('common.error') }}:</strong>
                        @enderror

                        Edit your comment...
                    </label>
                    @error('editState.content')
                        <span class="form__hint" id="edit-comment__textarea-hint">
                            {{ $message }}
                        </span>
                    @enderror
                </p>
                <p class="form__group">
                    <button type="submit" class="form__button form__button--filled">
                        {{ __('common.edit') }}
                    </button>
                    <button
                        type="button"
                        wire:click="$toggle('isEditing')"
                        class="form__button form__button--text"
                    >
                        {{ __('common.cancel') }}
                    </button>
                </p>
            </form>
        @else
            <div class="comment__content bbcode-rendered">
                @joypixels($comment->getContentHtml())
            </div>
        @endif
    </article>

    @if ($comment->isParent())
        <section class="comment__replies">
            <h5 class="sr-only">Replies</h5>
            @if ($comment->children()->exists())
                <ul class="comment__reply-list">
                    @foreach ($comment->children as $child)
                        <livewire:comment :comment="$child" :key="$child->id" />
                    @endforeach
                </ul>
            @endif

            @if ($isReplying || $comment->children()->exists())
                <form wire:submit="postReply" class="form reply-comment" x-data="toggle">
                    <p class="form__group">
                        <textarea
                            name="comment"
                            id="reply-comment"
                            class="form__textarea"
                            aria-describedby="reply-comment__textarea-hint"
                            wire:model="replyState.content"
                            required
                            x-on:focus="toggleOn"
                        ></textarea>
                        <label for="reply-comment" class="form__label form__label--floating">
                            @error('editState.content')
                                <strong>{{ __('common.error') }}:</strong>
                            @enderror

                            Reply to parent comment...
                        </label>
                        @error('replyState.content')
                            <span class="form__hint" id="reply-comment__textarea-hint">
                                {{ $message }}
                            </span>
                        @enderror
                    </p>
                    <p class="form__group" x-show="isToggledOn" x-cloak>
                        <input
                            type="checkbox"
                            id="reply-anon"
                            class="form__checkbox"
                            wire:model.live="anon"
                        />
                        <label for="reply-anon" class="form__label">
                            {{ __('common.anonymous') }}?
                        </label>
                    </p>
                    <p class="form__group" x-show="isToggledOn" x-cloak>
                        <button type="submit" class="form__button form__button--filled">
                            {{ __('common.comment') }}
                        </button>
                        <button
                            type="button"
                            wire:click="$toggle('isReplying')"
                            class="form__button form__button--text"
                        >
                            {{ __('common.cancel') }}
                        </button>
                    </p>
                </form>
            @endif
        </section>
    @endif
</li>
