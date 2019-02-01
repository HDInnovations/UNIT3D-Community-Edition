<template>
    <div class="message-input">
        <div class="wrap">
            <div class="row info">
                <div class="col-md-6">
                    <span class="badge-extra"> <strong>SHIFT + ENTER</strong> to insert new line </span>

                    <span class="badge-extra"> Type <strong>:</strong> for emoji </span>

                    <span class="badge-extra"> BBcode Allowed </span>
                </div>

                <div class="col-md-3">
                    <div class="pull-right">
                        <span>Status: </span>
                        <i
                            v-for="status in $parent.statuses"
                            v-tooltip="status.name"
                            @click="$emit('changedStatus', status.id)"
                            :class="status.icon ? status.icon : 'fa fa-dot-circle-o'"
                            :style="`color: ${status.color}`"
                            >&nbsp;</i
                        >
                    </div>
                </div>

                <div class="col-md-3">
                    <chatrooms-dropdown
                        :current="user.chatroom.id"
                        :chatrooms="$parent.chatrooms"
                        v-tooltip="`Chatrooms`"
                        class="pull-right"
                        @changedRoom="$parent.changeRoom"
                    >
                    </chatrooms-dropdown>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <textarea id="chat-message" send="true" name="message" placeholder="Write your message..." cols="30" rows="5">
                    </textarea>
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>
.col-md-4,
.col-md-12 {
    padding: 0;
}

.info {
    .badge-extra {
        margin: 8px;
    }

    i {
        &.fa {
            margin: 8px;

            &:hover {
                cursor: pointer;
            }
        }
    }
}
</style>
<script>
import ChatroomsDropdown from './ChatroomsDropdown';

export default {
    props: ['user'],
    components: {
        ChatroomsDropdown,
    },
    data() {
        return {
            editor: null,
            input: null,
        };
    },

    methods: {
        keyup(e) {
            this.$emit('typing', this.user);
        },
        keydown(e) {
            if (e.which == 13 && !e.shiftKey) {
                e.stopImmediatePropagation();
                e.stopPropagation();
                e.preventDefault();
                this.sendMessage();
            }
        },
        sendMessage() {
            let msg = this.editor.bbcode().trim();

            if (msg !== null && msg !== '') {
                this.$emit('message-sent', {
                    message: msg,
                    save: true,
                    user_id: this.user.id,
                });

                this.input.html('');
            }
        },
    },

    mounted() {
        this.editor = $('#chat-message').wysibb();

        // Initialize emojis
        emoji.textcomplete();

        this.input = $('.wysibb-body');

        this.input.keyup(this.keyup);
        this.input.keydown(this.keydown);
    },
};
</script>
