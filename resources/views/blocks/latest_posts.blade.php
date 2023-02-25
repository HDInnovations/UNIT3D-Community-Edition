<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">
            <a href="{{ route('forum_latest_posts') }}">
                {{ __('blocks.latest-posts') }}
            </a>
        </h2>
    </header>
    <div class="panel__body">
        @if ($posts->count() > 0)
            <ul class="topic-posts">
                @foreach ($posts as $post)
                    <li class="post-listings__item">
                        <x-forum.post :post="$post" />
                    </li>
                @endforeach
            </ul>
        @else
            No posts.
        @endif
    </div>
</section>
