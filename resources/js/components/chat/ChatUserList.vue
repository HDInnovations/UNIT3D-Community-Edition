<template>
    <div class="messages">
        <ul class="list-group">
            <li class="sent" v-for="user in users">
                <div class="button-holder">
                    <div class="button-center">
                        <div class="text-left">
                            <a v-if="user.id !== 1" target="_blank" :href="`/users/${user.username}`">
                                <img
                                    class="chat-user-image"
                                    :style="
                                        user &&
                                        user.hasOwnProperty('chat_status') &&
                                        user.chat_status.hasOwnProperty('color')
                                            ? `border: 3px solid ${user.chat_status.color};`
                                            : ``
                                    "
                                    :src="user.image ? `/files/img/${user.image}` : '/img/profile.png'"
                                    alt=""
                                />
                            </a>
                            <h4 class="list-group-item-heading">
                                <span class="badge-user text-bold" :style="userStyles(user)">
                                    <i :class="user.primaryRole.icon"> </i>

                                    <a :style="roleColor(user)" @click="pmUser(user)">
                                        {{ user.username }}
                                    </a>
                                </span>
                            </h4>
                            <div :class="user.id === 1 ? 'system text-bright' : 'text-bright'"></div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
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
            return user && user.primaryRole && user.primaryRole.hasOwnProperty('color')
                ? `cursor: pointer; color: ${user.primaryRole.color}; background-image: ${user.primaryRole.effect};`
                : `cursor: pointer;`;
        },
        roleColor(user) {
            return user && user.primaryRole && user.primaryRole.hasOwnProperty('color')
                ? `color: ${user.primaryRole.color};`
                : `cursor: pointer;`;
        },
    },
};
</script>
