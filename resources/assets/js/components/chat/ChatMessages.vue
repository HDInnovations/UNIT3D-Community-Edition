<template>
    <div class="messages">
        <ul class="list-group">
            <li class="sent" v-for="message in messages">

                <a target="_blank"
                   v-tooltip="`${message.user.username}${message.user.title ? ' ('+ message.user.title +')' : '\'s Profile'}`"
                   :href="`/${message.user.username}.${message.user.id}`">
                    <img v-if="message.user.id !== 1"
                         class="chat-user-image"
                         :style="`border: 3px solid ${message.user.chat_status.color};`"
                         :src="message.user.image ? `/files/img/${message.user.image}` : '/img/profile.png'"
                         alt=""/>
                </a>

                <h4 v-if="message.user.id !== 1" class="list-group-item-heading">

                    <span class="badge-user text-bold">

                        <i v-tooltip="`${message.user.group.name}`" :class="message.user.group.icon"></i>

                        <a data-toggle="tooltip" :style="userStyles(message.user)">
					        {{ message.user.username }}
                        </a>

                        <i v-if="canMod(message)"
                           v-tooltip="`Delete Message`"
                           @click="deleteMessage(message.id)"
                           class="fa fa-times text-red">

                        </i>

					</span>

                    <span class="text-muted">
                        {{ message.created_at | fromNow }}
                    </span>

                </h4>

                <div :class="['messages-container', message.user.id === 1 ? 'system' : null]"
                     v-html="message.message">

                </div>


            </li>
        </ul>
    </div>
</template>
<style lang="scss" scoped>
    .messages {
        h4 {
            i {
                &.fa-times {
                    margin-left: 10px;

                    &:hover {
                        cursor: pointer;
                    }
                }
            }
        }
    }
</style>
<script>
  import moment from 'moment'

  export default {
    props: {
      messages: {required: true},
    },
    methods: {
      canMod (message) {
        /*
            A user can Mod his own messages
            A user in a is_modo group can Mod messages
            A is_modo CAN NOT Mod another is_modo message
        */

        return (
          /* CAN NOT mod an automated message */
          !message.message.includes('Updated their status') &&

          (
              /* Owner can mod all */
              this.$parent.auth.group.id === 10 ||

              /* User can mod his own message */
              message.user.id === this.$parent.auth.id ||

              /* is_admin can mod messages except for Owner messages */
              this.$parent.auth.group.is_admin &&
              message.user.group.id !== 10 ||

              /* Mods CAN NOT mod other mods messages */
              this.$parent.auth.group.is_modo &&
              !message.user.group.is_modo
          )
        )
      },
      deleteMessage (id) {
        axios.get(`/api/chat/message/${id}/delete`)
      },
      userStyles (user) {
        return `cursor: pointer; color: ${user.group.color}; background-image: ${user.group.effect};`
      }
    },
    filters: {
      fromNow (dt) {
        return moment(String(dt)).fromNow()
      }
    },
    created () {
      this.interval = setInterval(() => this.$forceUpdate(), 30000)
    },
    beforeDestroy () {
      clearInterval(this.interval)
    }
  }
</script>