<div>
    @foreach ($messages as $message)
        @if($message->isOwn())
            <livewire:conversations.conversation-message-own :message="$message" :key="$message->id" />
        @else
            <livewire:conversations.conversation-message :message="$message" :key="$message->id" />
        @endif
    @endforeach 
</div>
