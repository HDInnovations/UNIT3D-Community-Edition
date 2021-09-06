<div class="d-flex mb-2">
    <div>
        @if ($message->user->image !== null)
            <img src="{{ url('files/img/' . $message->user->image) }}" style="width: 35px;" class="rounded-circle mr-2">
        @else
            <img src="{{ url('img/profile.png') }}" style="width: 35px;" class="rounded-circle mr-2">
        @endif
    </div>
    <div>
        <div class="bg-light p-2 rounded">
            @joypixels($message->getBodyHtml())
        </div>
        <span class="text-muted" style="font-size: 0.8rem;">
            {{ $message->user->username }}
        </span>
    </div>
</div>