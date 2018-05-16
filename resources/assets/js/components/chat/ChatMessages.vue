<template>
    <div class="messages">
        <ul class="list-group">
            <li v-if="canView(message)"
                :class="['sent', message.receiver ? 'pm' : null]"
                v-for="message in messages">

                <a v-if="!message.receiver && message.user.id !== 1" target="_blank"
                   v-tooltip="`${message.user.username}'s profile`"
                   :href="`/${message.user.username}.${message.user.id}`">
                    <img class="chat-user-image"
                         :style="`border: 3px solid ${message.user.chat_status.color};`"
                         :src="message.user.image ? `/files/img/${message.user.image}` : '/img/profile.png'"
                         alt=""/>
                </a>

                <h4 v-if="message.user.id !== 1" class="list-group-item-heading">

                    <span class="badge-user text-bold">

                        <i v-tooltip="message.user.group.name"
                           :class="message.user.group.icon">

                        </i>

                        <a v-tooltip="!message.receiver !== message.user.id ? `Private Message` : message.user.name"
                           @click="pmUser(message.user)"
                           :style="userStyles(message.user)">
					        {{ message.user.username }}
                        </a>

                        <!--<i v-if="canMod(message)"-->
                        <!--v-tooltip="`Edit Message`"-->
                        <!--@click="editMessage(message)"-->
                        <!--class="fa fa-edit text-blue">-->

                        <!--</i>-->

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

                <div :class="[message.receiver ? 'pm-container' : null, message.user.id === 1 ? 'system' : null]"
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
                &.fa {
                    &:first-child {
                        margin: 0;
                    }

                    margin-left: 5px;

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
    data () {
      return {
        editor: null
      }
    },
    methods: {
      canView (message) {
        /*
            Determine if the user can view this message
            If the message has no receiver it is a public message and all can see.
            Otherwise, only the sender and receiver can see the message

            This is only the first stage for Private Messaging
        */

        if (message.receiver === null) {
          return true
        }

        return (
          message.user.id === this.$parent.auth.id ||
          message.receiver.id === this.$parent.auth.id
        )
      },
      canMod (message) {
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
          this.$parent.auth.group.is_admin &&
          message.user.group.id !== 10 ||

          /* Mods CAN NOT mod other mods messages */
          this.$parent.auth.group.is_modo &&
          !message.user.group.is_modo
        )
      },
      pmUser (user) {
        if (user.id === this.$parent.auth.id) {
          return false
        }

        swal({
          title: `Send Private Message to ${user.username}`,
          input: 'textarea',
          width: '800px',
          height: '600px',
          inputAttributes: {
            autocapitalize: 'off'
          },

          showCancelButton: true,
          confirmButtonText: 'Send',
          showLoaderOnConfirm: true,

          onOpen: () => {
            this.editor = $('.swal2-textarea').wysibb({})
          },

          onClose: () => {
            this.editor = null
          },

          preConfirm: (msg) => {

            msg = this.editor.bbcode().trim()

            if (msg !== null && msg !== '') {

              this.$emit('pm-sent', {
                message: msg,
                save: true,
                user_id: this.$parent.auth.id,
                receiver_id: user.id
              })

              $('.wysibb-body').html('')
            }

            return user

          },

          allowOutsideClick: false

        }).then(result => {
          console.log(result)

          if (result.value) {
            swal({
              title: `Sent Private Message to ${result.value.username}`,
              timer: 1500,
              onOpen: () => {
                swal.showLoading()
              }
            }).then((result) => {
              if (result.dismiss === swal.DismissReason.timer) {

              }
            })
          }
        })
      },
      editMessage (message) {

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