<form action="" class="bg-white" wire:submit.prevent="create">
    <div class="p-4 border-bottom">
        <div class="mb-2 text-muted">
            Send to
            @foreach($users as $index => $user)
                <a href="#" class="font-weight-bold">{{ $user['username'] }}</a>@if($index + 1 !== count($users)), @endif
            @endforeach
        </div>

        <div x-data="{ ...conversationCreateState(), ...userSearchState() }">
            <x-conversations.user-search>
                <x-slot name="suggestions">
                    <template x-for="user in suggestions" :key="user.id">
                        <a href="#" x-on:click="addUser(user)" class="d-block" x-text="user.username"></a>
                    </template>
                </x-slot>
            </x-conversations.user-search>
        </div>

    </div>
    <div class="p-4 border-top">
        <div class="form-group">
            <label for="body" class="sr-only">Message</label>
            <textarea rows="3" id="body" class="form-control" wire:model="body"></textarea>
        </div>

        <button type="submit" class="btn btn-secondary btn-block">
            Start conversation
        </button>
    </div>
</form>

<script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
    function conversationCreateState() {
        return {
            addUser (user) {
                @this.call('addUser', user)
                this.$refs.search.value = ''
                this.suggestions = []
            }
        }
    }
</script>