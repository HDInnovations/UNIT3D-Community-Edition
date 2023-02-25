<template>
    <div class="chatbox__message-input">
        <div
            class="chatbox__bot-info"
            v-if="$parent.bot > 0 && $parent.activeTab.substr(0, 3) == 'bot'"
        >
            <span>
                {{ $parent.botName }} can accept messages from any tab if you type:
                <strong>/{{ $parent.botCommand }} help</strong>
            </span>
        </div>
        <div
            class="chatbox__typing"
            v-if="
                $parent.target < 1 &&
                $parent.bot < 1 &&
                $parent.activePeer &&
                $parent.activePeer.username != ''
            "
        >
            <span>{{
                $parent.activePeer ? $parent.activePeer.username + ' is typing ...' : '*'
            }}</span>
        </div>
        <div class="chatbox__textarea-container form__group">
            <textarea
                class="chatbox__textarea form__textarea"
                name="message"
                placeholder=""
                send="true"
            ></textarea>
            <label class="form__label form__label--floating">Write your message...</label>
        </div>
    </div>
</template>
<style lang="scss">
.chatbox__message-input {
    background: inherit;
}
.chatbox__typing {
    font-size: 12px;
    padding:0 5px 5px 5px;
}
.chatbox__bot-info {
    font-size: 12px;
    padding: 6px;
}
.chatbox__textarea-container {
    margin: 0 6px 6px 6px;
    width: calc(100% - 2 * 6px);
}
</style>
<script>
import ChatroomsDropdown from './ChatroomsDropdown';

export default {
    components: {
        ChatroomsDropdown,
    },
    data() {
        return {
            user: null,
            editor: null,
            input: null,
        };
    },
    computed: {
        receiver_id() {
            return this.$parent.receiver_id;
        },
        bot_id() {
            return this.$parent.bot_id;
        },
    },
    methods: {
        keyup(e) {
            this.$emit('typing', this.user);
        },
        keydown(e) {
            if (e.keyCode === 13 && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        },
        sendMessage() {
            let msg = this.input.val().trim();

            if (msg !== null && msg !== '') {
                this.$emit('message-sent', {
                    message: msg,
                    save: true,
                    user_id: this.user.id,
                    receiver_id: this.receiver_id,
                    bot_id: this.bot_id,
                });

                this.input.val('');
            }
        },
    },
    created() {
        this.user = this.$parent.auth;
    },
    mounted() {
        this.editor = $('#chat-message').val();
        this.input = $('#chat-message');
        this.input.keyup(this.keyup);
        this.input.keydown(this.keydown);
    },
};
</script>
