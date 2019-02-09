<template>
    <div class="messages">
        <ul class="list-group">
            <li class="sent" v-for="user in users">
                <div class="button-holder">
                    <div class="button-center">
                        <div class="text-left">
                <a v-if="user.id !== 1" target="_blank"
                   v-tooltip="`${user.username}'s profile`"
                   :href="`/${user.username}.${user.id}`">
                    <img class="chat-user-image"
                         :style="`border: 3px solid ${user.chat_status.color};`"
                         :src="user.image ? `/files/img/${user.image}` : '/img/profile.png'"
                         alt=""/>
                </a>
                <h4 class="list-group-item-heading">
                    <span class="badge-user text-bold">

                        <i v-tooltip="user.group.name"
                           :class="user.group.icon">

                        </i>

                        <a v-tooltip="$parent.auth.id !== user.id ? `Private Message` : user.username"
                           @click="pmUser(user)"
                           :style="userStyles(user)">
					        {{ user.username }}
                        </a>

					</span>
                </h4>
                <div :class="(user.id === 1 ? 'system text-bright' : 'text-bright')">

                </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>
<script>
  import pmMethods from './mixins/pmMethods'

  export default {
    props: {
      users: {required: true},
    },
    mixins: [
      pmMethods
    ],
    methods: {
      userStyles (user) {
        return `cursor: pointer; color: ${user.group.color}; background-image: ${user.group.effect};`
      }
    }
  }
</script>