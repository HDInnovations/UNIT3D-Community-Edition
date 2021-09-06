<div>
    @if($conversations->count())
        @foreach ($conversations as $conversation)
            <a href="{{ route('conversations.show', $conversation) }}" class="d-block bg-white p-4 mb-2">
                <div class="font-weight-bold text-muted">
                    @foreach($conversation->users as $user)
                        {{ $user->username }}@if($conversation->users->last() != $user), @endif
                    @endforeach
                </div>

                <p class="text-muted mb-0 text-truncate d-flex align-items-center">
                    @if(!auth()->user()->hasRead($conversation))
                        <i style="color: #0dffff;vertical-align: 1px;" class="fas fa-circle fa-xs"></i>
                    @endif
                    <span>@joypixels(preg_replace('#\[[^\]]+\]#', '', Str::limit($conversation->messages->first()->body), 10))...</span>
                </p>
            </a>
        @endforeach
    @else
        <p class="text-muted">No conversations</p>
    @endif
</div>
