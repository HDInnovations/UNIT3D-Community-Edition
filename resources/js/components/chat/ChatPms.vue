<template>
    <div class="messages">
        <ul class="list-group">
            <li class="sent" v-for="pm in pms">
                <h4 class="list-group-item-heading">
                    <span class="badge-user text-bold" :style="userStyles(pm.user)">
                        <i :class="pm.user.group.icon"> </i>

                        <a :style="groupColor(pm.user)">
                            {{ pm.user.username }}
                        </a>

                        <i v-if="canMod(pm)" @click="deleteMessage(pm.id)" class="fa fa-times text-red"> </i>
                    </span>

                    <span class="text-muted">
                        {{ pm.created_at | diffForHumans }}
                    </span>
                </h4>

                <div :class="['pm-container', pm.user.id === 1 ? 'system' : null]" v-html="pm.message"></div>
            </li>
        </ul>
    </div>
</template>
<script>
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';

export default {
    props: {
        pms: { required: true },
    },
    data() {
        return {
            editor: null,
        };
    },
    methods: {
        canMod(pm) {
            /*
            A user can Mod his own messages
        */

            return pm.user.id === this.$parent.auth.id;
        },
        editMessage(pm) {},
        deleteMessage(id) {
            axios.post(`/api/chat/message/${id}/delete`);
        },
        userStyles(user) {
            return `cursor: pointer; color: ${user.group.color}; background-image: ${user.group.effect};`;
        },
        groupColor(user) {
            return `color: ${user.group.color};`;
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
