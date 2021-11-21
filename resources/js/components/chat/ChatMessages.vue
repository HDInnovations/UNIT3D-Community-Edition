<template>
    <div class="messages">
        <ul class="list-group">
            <li class="sent" v-for="message in messages">
                <a target="_blank" :href="`/users/${message.user.username}`">
                    <img
                        v-if="message.user.id !== 1"
                        class="chat-user-image"
                        :style="`border: 3px solid ${message.user.chat_status.color};`"
                        :src="message.user.image ? `/files/img/${message.user.image}` : '/img/profile.png'"
                        alt=""
                    />
                </a>

                <h4 class="list-group-item-heading bot">
                    <span class="badge-user text-bold" :style="userStyles(message.user)">
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

                        <i
                            v-if="message.user.id != 1 && canMod(message)"
                            @click="deleteMessage(message.id)"
                            class="fa fa-times text-red"
                        >
                        </i>
                    </span>
                    <a
                        v-if="message.user && message.user.id > 1 && message.user.id != $parent.auth.id"
                        @click.prevent="$parent.forceMessage(message.user.username)"
                    >
                        <i class="fas fa-envelope pointee"></i>
                    </a>
                    <a
                        v-if="message.user && message.user.id > 1 && message.user.id != $parent.auth.id"
                        @click.prevent="$parent.forceGift(message.user.username)"
                    >
                        <i class="fas fa-gift pointee"></i>
                    </a>
                    <span v-if="message.user.id !== 1" class="text-muted">
                        {{ message.created_at | diffForHumans }}
                    </span>
                </h4>
                <div
                    @click="checkBot($event, message)"
                    :class="message.user.id === 1 ? 'system text-bright bot' : 'text-bright'"
                    v-html="message.message"
                ></div>
            </li>
        </ul>
    </div>
</template>
<style lang="scss" scoped>
.pointee {
    cursor: pointer;
}
.bot {
    display: inline-block;
    vertical-align: top;
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
