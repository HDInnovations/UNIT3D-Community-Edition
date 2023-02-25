<template>
    <ul class="chatbox__messages">
        <li class="chatbox-message" v-for="user in users">
            <a target="_blank" :href="`/users/${user.username}`" class="chatbox-message__link">
                <img
                    v-if="user.id !== 1"
                    class="chatbox-message__avatar"
                    :style="`border: 3px solid ${user.chat_status.color};`"
                    :src="user.image ? `/files/img/${user.image}` : '/img/profile.png'"
                    alt=""
                />
            </a>
            <address class="chatbox-message__header">
                <span class="chatbox-message__author" :style="userStyles(user)">
                    <i :class="message.user.group.icon"></i>
                    <a :style="groupColor(user)">
                        {{ user.username }}
                    </a>
                </span>
            </address>
        </li>
    </ul>
</template>
<script>
import pmMethods from './mixins/pmMethods';

export default {
    props: {
        users: { required: true },
    },
    mixins: [pmMethods],
    methods: {
        userStyles(user) {
            return user && user.group && user.group.hasOwnProperty('color')
                ? `cursor: pointer; color: ${user.group.color}; background-image: ${user.group.effect};`
                : `cursor: pointer;`;
        },
        groupColor(user) {
            return user && user.group && user.group.hasOwnProperty('color')
                ? `color: ${user.group.color};`
                : `cursor: pointer;`;
        },
    },
};
</script>
