<div>
    <li class="clearfix">
        <img src="{{ url($comment->user->image ? 'files/img/'.$comment->user->image : '/img/profile.png') }}" alt="{{ $comment->user->username }}"
             class="avatar">
        <div class="post-comments">
            <p class="meta">
                {{ $comment->created_at->toDayDateTimeString() }}
                <a href="#">{{ $comment->user->username }}</a> says :
                <i class="pull-right">
                    @if ($comment->isParent())
                        <button wire:click="$toggle('isReplying')" type="button" class="btn btn-xs btn-primary">
                            Reply
                        </button>
                    @endif

                    @if ($comment->user_id === auth()->id() || auth()->user()->group->is_modo)
                        <button wire:click="$toggle('isEditing')" type="button" class="btn btn-xs btn-primary">
                            Edit
                        </button>

                        <button
                                type="button"
                                class="btn btn-xs btn-primary"
                                x-on:click="confirmCommentDeletion"
                                x-data="{
                confirmCommentDeletion () {
                  if (window.confirm('You sure?')) {
                    @this.call('deleteComment')
                  }
                }
              }"
                        >
                            Delete
                        </button>
                    @endif
                </i>
            </p>
            @if ($isEditing)
                <form wire:submit.prevent="editComment">
                    <div>
                        <label for="comment" class="sr-only">Comment content</label>
                        <textarea id="comment" name="comment"
                                  class="form-control @error('editState.content') border-red-500 @enderror"
                                  placeholder="Write something" wire:model.defer="editState.content"></textarea>

                        @error('editState.content')
                        <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-3 flex items-center justify-between">
                        <button type="submit" class="inline-flex items-center btn btn-xs btn-warning">
                            Edit
                        </button>
                    </div>
                </form>
            @else
                <p>@joypixels($comment->getContentHtml())</p>
            @endif
        </div>
    </li>

    <div class="ml-14 mt-6">
        @if ($isReplying)
            <form wire:submit.prevent="postReply" class="my-4">
                <div>
                    <label for="comment" class="sr-only">Reply content</label>
                    <textarea id="comment" name="comment"
                              class="form-control @error('replyState.content') border-red-500 @enderror"
                              placeholder="Write something" wire:model.defer="replyState.content"></textarea>

                    @error('replyState.content')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3 flex items-center justify-between">
                    <button type="submit" class="btn btn-xs btn-success">
                        Comment
                    </button>
                    <input type="checkbox" wire:modal="anon" value="1"> Anonymous Comment?
                </div>
            </form>
        @endif

        @foreach ($comment->children as $child)
            <livewire:comment :comment="$child" :key="$child->id"/>
        @endforeach
    </div>
</div>
