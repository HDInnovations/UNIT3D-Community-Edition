<section>

    <style>
        .new-comment::before,
        .new-comment::after {
            content: "";
            display: table;
            clear: both;
        }

        .new-comment {
            padding-left: .5%;
            padding-right: .5%;
        }

        .new-comment ul {
            list-style-type: none;
            padding: 0;
        }

        .new-comment img {
            opacity: 1;
            filter: Alpha(opacity=100);
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            -o-border-radius: 4px;
            border-radius: 4px;
        }

        .new-comment img.avatar {
            position: relative;
            float: left;
            margin-left: 0;
            margin-top: 0;
            width: 45px;
            height: 45px;
        }

        .new-comment .post-comments {
            border: 1px solid #19191b;
            margin-bottom: 20px;
            margin-left: 85px;
            margin-right: 0px;
            padding: 10px 20px;
            position: relative;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            -o-border-radius: 4px;
            border-radius: 4px;
            background: #19191b;
            color: #6b6e80;
            position: relative;
        }

        .new-comment .meta {
            font-size: 13px;
            color: #aaaaaa;
            padding-bottom: 8px;
            margin-bottom: 10px !important;
            border-bottom: 1px solid #343434;
        }

        .comment ul.comments ul {
            list-style-type: none;
            padding: 0;
            margin-left: 85px;
        }

        .new-comment h3 {
            margin-bottom: 40px;
            font-size: 26px;
            line-height: 30px;
            font-weight: 800;
        }
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="new-comment">
                @if ($comments->count())
                    <ul class="comments">
                        @foreach($comments as $comment)
                            <livewire:comment :comment="$comment" :key="$comment->id"/>
                        @endforeach
                    </ul>

                    {{ $comments->links() }}
                @else
                    <div class="text-center">
                        <h4 class="text-bold text-danger">
                            <i class="{{ config('other.font-awesome') }} fa-frown"></i> {{ __('common.no-comments') }}!
                        </h4>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-gray-50 px-4 py-6 sm:px-6">
            <div class="flex">
                <div class="min-w-0 flex-1">
                    <form wire:submit.prevent="postComment">
                        <div>
                            <label for="comment" class="sr-only">Comment content</label>
                            <textarea id="comment" name="comment"
                                      class="form-control @error('newCommentState.content') border-red-500 @enderror"
                                      placeholder="Write something"
                                      wire:model.defer="newCommentState.content"></textarea>

                            @error('newCommentState.content')
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
                </div>
            </div>
        </div>
    </div>
</section>
