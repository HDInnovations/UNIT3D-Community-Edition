<section class="panelV2 blocks__posts">
    <h2 class="panel__heading">
        <a href="{{ route('posts.index') }}">
            {{ __('blocks.latest-comments') }}
        </a>
    </h2>
    <div class="panel__body">
        <ul class="comment-list">
            @forelse ($comments as $comment)
                <li class="comment__list-item">
                    <x-torrent.comment-listing :comment="$comment" />
                </li>
            @empty
                No comments.
            @endforelse
        </ul>
    </div>
</section>
