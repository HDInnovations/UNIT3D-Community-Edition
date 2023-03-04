<section class="panelV2">
    <h4 class="panel__heading">
        <i class="{{ config('other.font-awesome') }} fa-comment"></i>
        {{ __('common.comments') }}
    </h4>
    <div class="panel__body">
        <form wire:submit.prevent="postComment" class="form new-comment">
            <p class="form__group">
                <textarea
                    name="comment"
                    id="new-comment__textarea"
                    class="form__textarea"
                    aria-describedby="new-comment__textarea-hint"
                    wire:model.defer="newCommentState.content"
                    required
                ></textarea>
                <label for="new-comment__textarea" class="form__label form__label--floating">
                    @error('newCommentState.content')
                        <strong>{{ __('common.error') }}: </strong>
                    @enderror
                    Add a comment...
                </label>
                @error('newCommentState.content')
                    <span class="form__hint" id="new-comment__textarea-hint">{{ $message }}</p>
                @enderror
            </p>
            <p class="form__group">
                <input type="checkbox" id="anon" class="form__checkbox" wire:model="anon">
                <label for="anon" class="form__label">{{ __('common.anonymous') }}?</label>
            </p>
            <p class="form__group">
                <button type="submit" class="form__button form__button--filled">
                    Comment
                </button>
                <button type="reset" class="form__button form__button--text">
                    {{ __('common.cancel') }}
                </button>
            </p>
        </form>
        <ul class="comments">
            @forelse($comments as $comment)
                <livewire:comment :comment="$comment" :key="$comment->id"/>
            @empty
                <li>
                    <i class="{{ config('other.font-awesome') }} fa-frown"></i>
                    {{ __('common.no-comments') }}!
                </li>                        
            @endforelse
        </ul>
        @if ($comments->hasMorePages())
            <div class="text-center">
                <button class="form__button form__button--filled" wire:click.prevent="loadMore">Load More Comments</button>
            </div>
        @endif
    </div>
</section>