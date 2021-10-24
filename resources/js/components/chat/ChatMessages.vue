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

                        <i v-if="(message.user && message.user.id > 1) || (message.bot && message.bot.id >= 1)" :class="message.user.primary_role.icon">
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
                If Permission 'chat_can_moderate' is set true, user can moderate messages posted by any role with a lower position value
                OR if they are in primary role root then they can always moderate
                Users will also be able to moderate their own messages
            */

            return (
                this.$parent.permissions.can_moderate && message.user.primary_role.position < this.$parent.user.primary_role.position ||
                /* User can mod his own message */
                message.user.id === this.$parent.auth.id ||
                this.$parent.user.primary_role.slug === 'root'
            )
        },
        editMessage(message) {},
        deleteMessage(id) {
            axios.get(`/api/chat/message/${id}/delete`);
        },
        userStyles(user) {
            return `cursor: pointer; color: ${user.group.color}; background-image: ${user.group.effect};`;
        },
        userStyles (user) {
            return `cursor: pointer; color: ${user.primary_role.color}; background-image: ${user.primary_role.effect};`
        },
        groupColor (user) {
            return user && user.primary_role && user.primary_role.hasOwnProperty('color') ? `color: ${user.primary_role.color};` : `cursor: pointer;`
        }
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
