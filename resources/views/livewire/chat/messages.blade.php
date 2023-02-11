<div>
    @forelse ($messages as $message)
        <div class="mb-2 pb-2 border-bottom">
            <div>
                <strong>{{ $message->user->username }}</strong> <time>{{ $message->created_at->toDateTimeString() }}</time>
            </div>
            <span style="white-space: pre-wrap;">{{ $message->body }}</span>
        </div>
    @empty
        <p>It's quiet here...</p>
    @endforelse
</div>
