<form x-data="conversationReplyState()" action="#" wire:submit.prevent="reply">
    <div class="form-group mb-0">
        <textarea rows="3" class="form-control" wire:model="body" x-on:keydown.enter="submit" placeholder="Type your reply"></textarea>
    </div>

    <button type="submit" x-ref="submit" class="sr-only">Send</button>
</form>

<script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
    function conversationReplyState() {
        return {
            submit () {
                this.$refs.submit.click()
            }
        }
    }
</script>