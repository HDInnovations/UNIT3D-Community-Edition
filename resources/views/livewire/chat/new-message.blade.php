<div x-data="formScope()" x-init="watchTyping">
    <form action="#" wire:submit.prevent="send">
        <div class="form-group">
            <textarea rows="3" class="form-control" wire:model="body" x-on:keydown="determineTypingState" x-on:keydown.enter="submit"></textarea>
        </div>

        <button type="submit" class="btn btn-md btn-primary" x-ref="submit">Send</button>
    </form>
</div>

<script>
    function formScope() {
        let typingTimer

        return {
            typing: false,

            submit (e) {
                if (e.shiftKey) return
                
                this.$refs.submit.click()
            },

            determineTypingState () {
                this.typing = true

                clearTimeout(typingTimer)

                typingTimer = setTimeout(() => {
                    this.typing = false
                }, 2000)
            },

            watchTyping () {
                setTimeout(() => {
                    this.whisperTyping(false)
                }, 2000)

                this.$watch('typing', (typing) => {
                    this.whisperTyping(typing)
                })
            },

            whisperTyping (typing) {
                Echo.private('chat.{{ $room->id }}')
                    .whisper('typing', {
                        id: User.id,
                        typing
                    })
            }
        }
    }
</script>
