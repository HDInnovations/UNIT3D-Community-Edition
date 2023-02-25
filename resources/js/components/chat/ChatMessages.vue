<template>
    <ul class="chatbox__messages">
        <li :class="message.user.id === 1 ? 'chatbox-message chatbox-message--bot' : 'chatbox-message'" v-for="message in messages">
            <a target="_blank" :href="`/users/${message.user.username}`" class="chatbox-message__link">
                <img
                    v-if="message.user.id !== 1"
                    class="chatbox-message__avatar"
                    :style="`border: 3px solid ${message.user.chat_status.color};`"
                    :src="message.user.image ? `/files/img/${message.user.image}` : '/img/profile.png'"
                    alt=""
                />
            </a>
            <address class="chatbox-message__header">
                <span class="chatbox-message__author" :style="userStyles(message.user)">
                    <i
                        v-if="(message.user && message.user.id > 1) || (message.bot && message.bot.id >= 1)"
                        :class="message.user.group.icon"
                    >
                    </i>
                    <i
                        v-if="message.user && message.user.id <= 1 && (!message.bot || message.bot.id < 1)"
                        class="fas fa-bell"
                    >
                    </i>
                    <a
                        v-if="message.user && message.user.id > 1"
                        @click="pmUser(message.user)"
                        :style="groupColor(message.user)"
                    >
                        {{ message.user.username }}
                    </a>
                    <a
                        v-if="message.bot && message.bot.id >= 1 && (!message.user || message.user.id < 2)"
                        :style="groupColor(message.user)"
                    >
                        {{ message.bot.name }}
                    </a>
                </span>
                <menu class="chatbox-message__actions">
                    <button
                        v-if="message.user.id != 1 && canMod(message)"
                        @click="deleteMessage(message.id)"
                        class="form__standard-icon-button form__standard-icon-button--short form__standard-icon-button--skinny"
                    >
                        <i class="fa fa-times text-red"></i>
                    </button>
                    <button
                        v-if="message.user && message.user.id > 1 && message.user.id != $parent.auth.id"
                        @click.prevent="$parent.forceMessage(message.user.username)"
                        class="form__standard-icon-button form__standard-icon-button--short form__standard-icon-button--skinny"
                    >
                        <i class="fas fa-envelope"></i>
                    </button>
                    <button
                        v-if="message.user && message.user.id > 1 && message.user.id != $parent.auth.id"
                        @click.prevent="$parent.forceGift(message.user.username)"
                        class="form__standard-icon-button form__standard-icon-button--short form__standard-icon-button--skinny"
                    >
                        <i class="fas fa-gift"></i>
                    </button>
                </menu>
                <span v-if="message.user.id !== 1" class="chatbox-message__timestamp">
                    {{ message.created_at | diffForHumans }}
                </span>
            </address>
            <div
                @click="checkBot($event, message)"
                class="chatbox-message__content bbcode-rendered"
                v-html="message.message"
            ></div>
        </li>
    </ul>
</template>
<style lang="scss">
.chatbox__messages {
    padding: 10px 0;
    list-style-type: none;
    background-color: inherit !important; /* Overrides theming */
    display: flex;
    flex-direction: column-reverse;
    align-items: flex-start;
    row-gap: 10px;
    overflow-y: auto;
}

.chatbox-message {
    display: grid;
    grid-template-columns: 0 auto auto 1fr auto;
    grid-template-rows: min-content auto;
    grid-template-areas:
        'avatar author actions timestamp'
        'avatar content content content';
    gap: 0 4px;
    align-items: center;
    padding: 12px 16px;
    margin-left: 58px;
    border-radius: 20px;
    font-size: 13px;
    color: var(--message-bubble-fg, currentColor);
    background-color: var(--message-bubble-bg, inherit);
    box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.14), 0 2px 1px -1px rgba(0, 0, 0, 0.12), 0 1px 3px 0 rgba(0, 0, 0, 0.2);
    width: fit-content;
    max-width: 100%
}

.chatbox-message__header {
    display: contents;
}

.chatbox-message__link {
    display: contents;
}

.chatbox-message__avatar {
    grid-area: avatar;
    position: relative;
    left: -58px;
    bottom: -12px;
    border-radius: 50%;
    width: 36px;
    align-self: self-end;
    background-color: transparent;
}

.chatbox-message__author {
    grid-area: author;
    margin: 0;
    font-size: 13px;
    font-weight: 600;
}

.chatbox-message__timestamp {
    grid-area: timestamp;
    font-size: 11px;
    margin: 0 8px;
    white-space: nowrap;
}

.chatbox-message__actions {
    grid-area: actions;
    display: flex;
    justify-content: flex-end;
    gap: 4px;
    padding: 0;
    margin: 0;
}

.chatbox-message__content {
    grid-area: content;
    position: relative;
    margin-top: 0 !important; /* can be removed once site-wide `p` styling is removed */
    overflow-x: auto;

    img:not(.joypixels) {
        min-height: 150px;
        max-height: 300px;
        max-width: 500px;
    }
}
</style>
<script>
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import pmMethods from './mixins/pmMethods';

export default {
    props: {
        messages: { required: true },
    },
    data() {
        return {
            editor: null,
        };
    },
    mixins: [pmMethods],
    methods: {
        checkBot(e, message) {
            if (e.target.hasAttribute('trigger') && e.target.getAttribute('trigger') == 'bot') {
                e.preventDefault();
                let target = e.target.hash;
                const tmp = target.split('/');
                $('#chat-message').bbcode('/' + tmp[1] + ' ' + tmp[2] + ' ');
                $('#chat-message').htmlcode('/' + tmp[1] + ' ' + tmp[2] + ' ');
            }
        },
        canMod(message) {
            /*
                A user can Mod his own messages
                A user in a is_modo group can Mod messages
                A is_modo CAN NOT Mod another is_modo message
            */

            return (
                /* Owner can mod all */
                this.$parent.auth.group.id === 10 ||
                /* User can mod his own message */
                message.user.id === this.$parent.auth.id ||
                /* is_admin can mod messages except for Owner messages */
                (this.$parent.auth.group.is_admin && message.user.group.id !== 10) ||
                /* Mods CAN NOT mod other mods messages */
                (this.$parent.auth.group.is_modo && !message.user.group.is_modo)
            );
        },
        editMessage(message) {},
        deleteMessage(id) {
            axios.post(`/api/chat/message/${id}/delete`);
        },
        userStyles(user) {
            return `cursor: pointer; color: ${user.group.color}; background-image: ${user.group.effect};`;
        },
        groupColor(user) {
            return user && user.group && user.group.hasOwnProperty('color')
                ? `color: ${user.group.color};`
                : `cursor: pointer;`;
        },
    },
    created() {
        dayjs.extend(relativeTime);
        this.interval = setInterval(() => this.$forceUpdate(), 30000);
    },

    filters: {
        diffForHumans: (date) => {
            if (!date) {
                return null;
            }

            return dayjs(date).fromNow();
        },
    },
    beforeDestroy() {
        clearInterval(this.interval);
    },
};
</script>
