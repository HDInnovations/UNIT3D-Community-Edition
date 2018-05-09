<template>
    <div class="messages">
        <ul class="list-group">
            <li class="sent" v-for="message in messages">

                <img v-if="message.user.id !== 1"
                     class="chat-user-image"
                     :style="`border: 3px solid ${message.user.chat_status.color};`"
                     :src="message.user.image ? message.user.image : '/img/profile.png'"
                     alt=""/>

                <h4 v-if="message.user.id !== 1" class="list-group-item-heading">

                    <span class="badge-user text-bold">

                        <i :class="message.user.group.icon"></i>

                        <a data-toggle="tooltip" :style="userStyles(message.user)">
					        {{ message.user.username }}
                        </a> - <a :href="`/${message.user.username}.${message.user.id}`">Profile</a>

					</span>

                    <span class="text-muted">
                        {{ message.created_at }}
                    </span>

                </h4>

                <p :class="message.user.id === 1 ? 'system' : null"
                   v-emoji-render:data="message.message"
                   v-html="message.message">

                </p>
            </li>
        </ul>
    </div>
</template>
<style lang="scss" scoped>

</style>
<script>
  export default {
    props: {
      messages: {required: true},
    },
    methods: {
      userStyles (user) {
        return `cursor: pointer; color: ${user.group.color}; background-image: ${user.group.effect};`
      }
    }
  }
</script>